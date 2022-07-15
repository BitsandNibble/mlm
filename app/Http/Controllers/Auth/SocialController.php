<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
	public function redirect($redirect)
	{
		return Socialite::driver($redirect)->stateless()->redirect();
	}

	public function loginWithFacebook()
	{
		try {
			$user = Socialite::driver('facebook')->stateless()->user();

			// Check if user already signed up/in using any social auth
			$isUser = User::whereFacebookIdOrGoogleIdOrEmail($user->id, $user->id, $user->email)->first();

			if ($isUser) {
				Auth::login($isUser);

				return redirect()->intended(RouteServiceProvider::HOME);
			} else {
				$createUser = User::create([
					'name'        => $user->name,
					'email'       => $user->email,
					'facebook_id' => $user->id,
					'password'    => bcrypt('password')
				]);

				Auth::login($createUser);

				return redirect()->intended(RouteServiceProvider::HOME);
			}
		} catch (Exception $exception) {
			throw $exception;
			dd($exception->getMessage());
		}
	}

	public function loginWithGoogle()
	{
		try {
			$user = Socialite::driver('google')->stateless()->user();

			// Check if user already signed up/in using any social auth
			$isUser = User::whereFacebookIdOrGoogleIdOrEmail($user->id, $user->id, $user->email)->first();

			if ($isUser) {
				Auth::login($isUser);

				return redirect()->intended(RouteServiceProvider::HOME);
			} else {
				$createUser = User::create([
					'name'      => $user->name,
					'email'     => $user->email,
					'google_id' => $user->id,
					'password'  => bcrypt('password')
				]);

				Auth::login($createUser);

				return redirect()->intended(RouteServiceProvider::HOME);
			}
		} catch (Exception $exception) {
			throw $exception;
			dd($exception->getMessage());
		}
	}
}
