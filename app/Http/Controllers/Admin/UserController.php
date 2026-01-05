<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $users = User::latest()->paginate(25);
        return view('admin.users', compact('users'));
    }

    public function show(User $user)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $user->load('vendor');
        return view('admin.users-show', compact('user'));
    }

    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted');
    }
}
