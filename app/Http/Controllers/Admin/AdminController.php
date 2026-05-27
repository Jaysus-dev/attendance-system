<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class AdminController extends Controller
{
    // LIST USERS
    public function users()
    {
        return Inertia::render('Admin/Users', [
            'users' => User::latest()->get(),
        ]);
    }

    // APPROVE USER
    public function approve(User $user)
    {
        $user->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'User approved successfully.');
    }

    // REJECT USER
    public function reject(User $user)
    {
        $user->delete();

        return back()->with('success', 'User rejected.');
    }
}