<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the callback from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Restrict to @tecnoilave.edu.pe domain
            if (!str_ends_with($googleUser->getEmail(), '@tecnoilave.edu.pe')) {
                return redirect('/')->with('error', 'You must use your institutional email to login.');
            }

            // Find or create user
            $user = User::firstOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name' => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt(str()->random(16)), // random password
                ]
            );

            Auth::login($user, true);

            return redirect('/chat');
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Failed to login with Google.');
        }
    }
}
