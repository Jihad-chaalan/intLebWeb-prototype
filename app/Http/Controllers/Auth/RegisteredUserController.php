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

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'in:int-seeker,company'],

            // Internship Seeker fields
            'name' => ['required_if:role,int-seeker', 'string', 'max:255'],
            'email' => ['required_if:role,int-seeker', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required_if:role,int-seeker', 'confirmed', Rules\Password::defaults()],

            // Company fields - nullable so validation skips if empty unless required
            'company_name' => ['nullable', 'required_if:role,company', 'string', 'max:255'],
            'company_location' => ['nullable', 'required_if:role,company', 'string', 'max:255'],
            'company_document' => ['nullable', 'required_if:role,company', 'file', 'mimes:pdf,jpg,png,doc,docx'],

            'company_email' => ['nullable', 'required_if:role,company', 'string', 'email', 'max:255', 'unique:users,email'],
            'company_password' => ['nullable', 'required_if:role,company', 'confirmed', Rules\Password::defaults()],
        ]);

        // Handle company document upload if needed
        $documentPath = null;
        if ($request->role === 'company' && $request->hasFile('company_document')) {
            $documentPath = $request->file('company_document')->store('company_documents', 'public');
        }

        // Determine email and password based on role
        if ($request->role === 'company') {
            $email = $request->company_email;
            $password = $request->company_password;
            $name = $request->company_name;
        } else { // int-seeker
            $email = $request->email;
            $password = $request->password;
            $name = $request->name;
        }

        // Create user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $request->role,
        ]);

        // Create related profile record
        if ($request->role === 'int-seeker') {
            \App\Models\InternshipSeeker::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $email,
                'profile_photo' => null,
                'cover_photo' => null,
                'password' => Hash::make($request->password),
                // No need to store password here again, only in users table
            ]);
        } elseif ($request->role === 'company') {
            \App\Models\Company::create([
                'user_id' => $user->id,
                'name' => $request->company_name,
                'address' => $request->company_location,
                'registration_document' => $documentPath,
                'profile_photo' => null,
                'cover_photo' => null,
                'website' => null,
                'verified' => false,
                'email' => $request->company_email,
                'password' => Hash::make($request->company_password),
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        if ($user->role === 'company') {
            return redirect()->route('company');
        } elseif ($user->role === 'int-seeker') {
            return redirect()->route('seeker.profile');
        }

        return redirect()->route('login');
    }
}
