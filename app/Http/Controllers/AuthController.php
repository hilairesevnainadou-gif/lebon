<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\LoginOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'L\'email est obligatoire.',
            'email.email'       => 'L\'email est invalide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        /** @var User|null $user */
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Identifiants incorrects.']);
        }

        // Identifiants valides : on ne connecte pas tout de suite, on envoie l'OTP
        $code = $user->generateOtp();
        Mail::to($user->email)->send(new LoginOtp($user, $code));

        $request->session()->put('otp_user_id', $user->id);
        $request->session()->put('otp_remember', $request->boolean('remember'));
        $request->session()->put('otp_last_sent_at', now());

        return redirect()->route('otp.show');
    }

    public function showOtp(Request $request): View|RedirectResponse
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ], [
            'code.required' => 'Le code est obligatoire.',
        ]);

        $userId = $request->session()->get('otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        /** @var User|null $user */
        $user = User::find($userId);

        if (!$user) {
            $request->session()->forget(['otp_user_id', 'otp_remember', 'otp_last_sent_at']);
            return redirect()->route('login')->withErrors(['email' => 'Session expirée, veuillez vous reconnecter.']);
        }

        if (!$user->verifyOtp($request->input('code'))) {
            return back()->withErrors(['code' => 'Code invalide ou expiré.']);
        }

        $remember = (bool) $request->session()->pull('otp_remember', false);
        $request->session()->forget(['otp_user_id', 'otp_last_sent_at']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('ads.index'));
    }

    public function resendOtp(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('otp_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        /** @var User|null $user */
        $user = User::find($userId);

        if (!$user) {
            $request->session()->forget(['otp_user_id', 'otp_remember', 'otp_last_sent_at']);
            return redirect()->route('login');
        }

        $lastSentAt = $request->session()->get('otp_last_sent_at');

        if ($lastSentAt && now()->diffInSeconds($lastSentAt) < 60) {
            return back()->withErrors(['code' => 'Veuillez patienter avant de demander un nouveau code.']);
        }

        $code = $user->generateOtp();
        Mail::to($user->email)->send(new LoginOtp($user, $code));
        $request->session()->put('otp_last_sent_at', now());

        return back()->with('success', 'Un nouveau code vous a été envoyé.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
