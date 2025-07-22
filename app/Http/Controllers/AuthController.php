<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Livewire\Auth\Register;
use App\Models\Log;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller {
    
    public function getLogin() {
        return Inertia::render('Auth/Login');
    }

    public function postLogin(LoginRequest $request) {
        if (!Auth::attempt($request->validated())) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
        Log::createLog('User logged in: ' . Auth::user()->id);
        return redirect()->intended(route('dashboard'))->with('success', 'Login realizado com sucesso!');

    }

    public function getRegister() {
        return Inertia::render('Auth/Register');
    }

    public function postRegister(RegisterRequest $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_type' => 'admin',
        ]);

        Auth::login($user);
        Log::createLog('User registered and logged in: ' . $user->id);

        return redirect()->intended(route('dashboard'))->with('success', 'Registro realizado com sucesso!');
        
    }
}
