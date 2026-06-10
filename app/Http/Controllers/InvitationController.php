<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function show(string $token): View|RedirectResponse
    {
        $user = User::where('invitation_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Ce lien d\'activation est invalide ou a déjà été utilisé.');
        }

        return view('auth.invitation', compact('user', 'token'));
    }

    public function activate(Request $request, string $token): RedirectResponse
    {
        $user = User::where('invitation_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Ce lien d\'activation est invalide ou a déjà été utilisé.');
        }

        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min'       => 'Le mot de passe doit faire au moins 8 caractères.',
        ]);

        $user->update([
            'password'          => Hash::make($request->password),
            'invitation_token'  => null,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('ads.index')
            ->with('success', 'Votre compte est activé. Bienvenue ' . $user->name . ' !');
    }
}
