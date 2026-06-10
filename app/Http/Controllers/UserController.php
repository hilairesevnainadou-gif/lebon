<?php

namespace App\Http\Controllers;

use App\Mail\UserInvitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('is_admin', 'desc')
            ->orderBy('name')
            ->paginate(20);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create', ['user' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'is_admin' => ['nullable', 'boolean'],
        ], [
            'name.required'  => 'Le nom est obligatoire.',
            'email.required' => "L'email est obligatoire.",
            'email.unique'   => 'Cette adresse email est déjà utilisée.',
        ]);

        $token = Str::random(64);

        $user = User::create([
            'name'             => $data['name'],
            'email'            => $data['email'],
            'password'         => Hash::make(Str::random(32)),
            'is_admin'         => $request->boolean('is_admin'),
            'invitation_token' => $token,
        ]);

        $activationUrl = route('invitation.show', $token);
        Mail::to($user->email)->send(new UserInvitation($user, $activationUrl));

        return redirect()->route('users.index')
            ->with('success', "Un email d'activation a été envoyé à {$data['email']}.");
    }

    public function edit(User $user): View
    {
        return view('users.create', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->numbers()],
            'is_admin' => ['nullable', 'boolean'],
        ], [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => "L'email est obligatoire.",
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min'       => 'Le mot de passe doit faire au moins 8 caractères.',
        ]);

        $user->name     = $data['name'];
        $user->email    = $data['email'];
        $user->is_admin = $request->boolean('is_admin');

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', "L'utilisateur {$user->name} a été mis à jour.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "L'utilisateur {$name} a été supprimé.");
    }
}
