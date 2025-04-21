<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables; // Pastikan pakai ini

class UserController extends Controller
{
    public function __construct()
    {
       
    }

    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));    }
    
}
