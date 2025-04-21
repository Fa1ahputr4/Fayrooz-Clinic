<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagement extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;

    // Form fields
    public $userId;
    public $name;
    public $username;
    public $email;
    public $role = 'user';
    public $password;
    public $password_confirmation;

    protected $listeners = ['refreshUsers' => '$refresh'];

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.user-management', [
            'users' => $users,
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
        $this->resetErrorBag();
    }

    public function openDeleteModal($id)
    {
        $user = User::find($id);
        $this->username = $user->username;
        $this->userId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->userId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'userId',
            'name',
            'username',
            'email',
            'role',
            'password',
            'password_confirmation'
        ]);
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role;

        $this->isModalOpen = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->userId,
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->userId,
            'role' => 'required|in:admin,dokter,staff,resepsionis,apoteker',
        ];

        if (!$this->userId || $this->password) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            $user = User::find($this->userId);
            $user->update($data);
            $message = 'User updated successfully.';
        } else {
            User::create($data);
            $message = 'User created successfully.';
        }

        $this->dispatch('flash-message', type: 'success', message: $message);

        $this->closeModal();
    }

    public function deleteUser()
    {
        $user = User::find($this->userId);
        $user->delete();

        $this->dispatch('flash-message', type: 'success', message: 'User deleted successfully.');
        $this->closeDeleteModal();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }
}
