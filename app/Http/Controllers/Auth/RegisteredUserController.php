<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
	/**
	 * Display the registration view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return view('auth.register');
	}

	/**
	 * Handle an incoming registration request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request)
	{
		$request->validate([
			'name'     => ['required', 'string', 'max:255'],
			'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'username' => ['sometimes', 'string', 'max:255'],
			'phone'    => ['sometimes', 'string', 'digits_between:11,15'],
			'password' => ['required', 'confirmed', Rules\Password::defaults()],
		]);

        $index  = mt_rand(111111, 999999);
        $userID = sprintf("%s%04s", 'MLM', ++$index);

		$user = User::create([
			'name'     => $request->name,
			'email'    => $request->email,
			'username' => $request->username,
			'phone'    => $request->phone,
			'userID'   => $userID,
			'password' => Hash::make($request->password),
		]);

		event(new Registered($user));

		Auth::login($user);

		return redirect(RouteServiceProvider::HOME);
	}
}
