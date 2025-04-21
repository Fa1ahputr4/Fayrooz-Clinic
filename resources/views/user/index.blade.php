@extends('layouts.app')
@section('content')
 {{-- Flash message di sini --}}
 @if (session()->has('status'))
 <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
     {{ session('status') }}
 </div>
@endif
@livewire('user-management')

@endsection
