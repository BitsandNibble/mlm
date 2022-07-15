<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
	protected $loginField;
	protected $loginValue;

	protected function prepareForValidation()
	{
		//    $this->loginField = filter_var($this->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'school_id';

		//    login with email or username or phone number
		if (filter_var($this->input('login'), FILTER_VALIDATE_EMAIL)) {
			$this->loginField = 'email';
		// } elseif (filter_var($this->input('login'), FILTER_VALIDATE_FLOAT)) {
		// } elseif (preg_match('/^[0-9]{10}+$/', $this->input('login'))) {
		// 	$this->loginField = 'phone';
		} else {
			$this->loginField = 'username';
		}

		$this->loginValue = $this->input('login');

		$this->merge([$this->loginField => $this->loginValue]);
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'email'    => 'required_without:username|string|email|max:255',
			'username' => 'required_without:email|string',
			// 'phone'    => 'required_without_all:username,email',
			'password' => 'required|string',
		];
	}

	public function messages()
	{
		return [
			'email.required_without' => 'Insert email or',
			'username.required_without' => 'Insert username',
			// 'phone.required_without_all' => 'Insert phone number',
		];
	}

	/**
	 * Attempt to authenticate the request's credentials.
	 *
	 * @return void
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function authenticate()
	{
		$this->ensureIsNotRateLimited();

		if (!Auth::attempt($this->only($this->loginField, 'password'), $this->boolean('remember'))) {
			RateLimiter::hit($this->throttleKey());

			throw ValidationException::withMessages([
				'login' => trans('auth.failed'),
			]);
		}

		RateLimiter::clear($this->throttleKey());
	}

	/**
	 * Ensure the login request is not rate limited.
	 *
	 * @return void
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function ensureIsNotRateLimited()
	{
		if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
			return;
		}

		event(new Lockout($this));

		$seconds = RateLimiter::availableIn($this->throttleKey());

		throw ValidationException::withMessages([
			'email' => trans('auth.throttle', [
				'seconds' => $seconds,
				'minutes' => ceil($seconds / 60),
			]),
		]);
	}

	/**
	 * Get the rate limiting throttle key for the request.
	 *
	 * @return string
	 */
	public function throttleKey()
	{
		return Str::lower($this->input('email')) . '|' . $this->ip();
	}
}
