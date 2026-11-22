<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\AuthRegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class AuthRegisterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('auth/register', [
            'registrationAvailable' => config('auth.unesa_email_domains', []) !== [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AuthRegisterRequest $request): RedirectResponse
    {
        $email = $request->input('email');

        if (is_string($email)) {
            $request->merge(['email' => strtolower($email)]);
        }

        $validated = $request->validated();

        $domains = config('auth.unesa_email_domains', []);

        $emailDomain = strtolower((string) substr(strrchr($validated['email'], '@') ?: '', 1));

        if ($domains === [] || ! in_array($emailDomain, $domains, true)) {
            return back()
                ->withErrors(['email' => 'Gunakan alamat email dengan domain UNESA yang terdaftar.'])
                ->withInput($request->only('name', 'email'));
        }

        $user = new User;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role = Role::MEMBER->value;
        $user->save();

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }
}
