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
				$this->createUser($user, 'facebook_id');
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
				$this->createUser($user, 'google_id');
			}
		} catch (Exception $exception) {
			throw $exception;
			dd($exception->getMessage());
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
}
