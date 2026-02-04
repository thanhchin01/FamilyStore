<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        $users = \App\Models\User::paginate(10);
        return view('admin.users.index', compact('users'));
    }
}
