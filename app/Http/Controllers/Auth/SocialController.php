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

			// Check if user already signed up using another social auth
			$userExists = User::whereEmail($user->email)->whereNotNull('google_id')->first();

			if ($userExists) {
				return redirect(route('login'))->withErrors([
					'errors' => "Account already exists using Google authentication. Sign in with your email or Google"
				]);
			}

			// Check if user has previously signed up with Facebook
			$isUser = User::whereFacebookIdOrEmail($user->id, $user->email)->first();

			if ($isUser) {
				Auth::login($isUser);

				return redirect()->intended(RouteServiceProvider::HOME);
			} else {
				$this->createUser($user, 'facebook_id');
			}
		} catch (Exception $exception) {
			throw $exception;
			// dd($exception->getMessage());
		}
	}

	public function loginWithGoogle()
	{
		try {
			$user = Socialite::driver('google')->stateless()->user();

			// Check if user already signed up using another social auth
			$userExists = User::whereEmail($user->email)->whereNotNull('facebook_id')->first();

			if ($userExists) {
				return redirect(route('login'))->withErrors([
					'errors' => "Account already exists using Facebook authentication. Sign in with your email or Facebook"
				]);
			}

			// Check if user has previously signed up with Google
			$isUser = User::whereGoogleIdOrEmail($user->id, $user->email)->first();

			if ($isUser) {
				Auth::login($isUser);

				return redirect()->intended(RouteServiceProvider::HOME);
			} else {
				$this->createUser($user, 'google_id');
			}
		} catch (Exception $exception) {
			throw $exception;
			// dd($exception->getMessage());
		}
	}

	function createUser($user, $socialId)
	{
		$index  = mt_rand(111111, 999999);
		$userID = sprintf("%s%04s", 'MLM', ++$index);

		$createUser = User::create([
			'name'     => $user->name,
			'email'    => $user->email,
			'userID'   => $userID,
			$socialId  => $user->id,
			'password' => bcrypt('password'),
		]);

		Auth::login($createUser);

		return redirect()->intended(RouteServiceProvider::HOME);
	}

	public function checkIfSocialAuthExists($user, $socialId, $name)
	{
		// Check if user already signed up using another social auth
		$userExists = User::whereEmail($user->email)->whereNotNull($socialId)->first();

		if ($userExists) {
			return redirect(route('login'))->withErrors([
				'errors' => "Account already exists using {$name} authentication. Sign in with your email or Google"
			]);
		}

		return 'false';
	}
}
