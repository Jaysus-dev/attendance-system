<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,teacher',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'pending',
        ]);

        if ($user->role === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'employee_number' => 'TCH-' . $user->id,
                'fullname' => $user->name,
                'email' => $user->email,
                'course_id' => null,
                'position' => 'Instructor',
    ]);
}

    if ($user->role === 'student') {
        Student::create([
            'user_id' => $user->id,
            'student_number' => 'STU-' . $user->id,
            'fullname' => $user->name,
            'email' => $user->email,
            'parent_email' => null,
            'course_id' => null,     // TEMP fallback (or assign later)
            'section_id' => null,    // TEMP fallback
            'year_level' => null,
    ]);
}


        event(new Registered($user));

   Auth::logout();
    return redirect('/login')->with('message', 'Waiting for admin approval');
    }
}
