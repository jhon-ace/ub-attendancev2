<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use App\Models\Admin\School; 

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $schools = School::all();
        return view('auth.register', compact('schools'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,employee,student,admin_staff,sao'],
            'school_id' => $request->role !== 'admin'
                ? ['required', 'integer']  // For non-admin roles, school_id is required
                : ['nullable', 'integer'],  // For admin, school_id is nullable
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'school_id' => $request->role === 'admin' ? null : $request->school_id,  
        ];

        $user = User::create($data);

        $user->assignRole($request->role);
        
        event(new Registered($user));

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect(route('admin.dashboard'))->with('success', 'Successful Login');
        } elseif ($user->role === 'admin_staff') {
            return redirect(route('admin_staff.dashboard'))->with('success', 'Successful Login');
        } elseif ($user->role === 'sao') {
            return redirect(route('sao.dashboard'))->with('success', 'Successful Login');
        }
    }
}
