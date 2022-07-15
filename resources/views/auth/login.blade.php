<x-guest-layout>
	<a href="/" class="flex justify-center items-center">
		<x-application-logo class="w-20 h-20 fill-current text-gray-500" />
	</a>

	<!-- Session Status -->
	<x-auth-session-status class="mb-4" :status="session('status')" />

	<!-- Validation Errors -->
	<x-auth-validation-errors class="mb-4 mt-4" :errors="$errors" />

	<form method="POST" action="{{ route('login') }}" class="mt-4">
		@csrf

		<!-- Email Address -->
		<div>
			<x-label for="email" :value="__('Email')" />
			<x-input type="email" name="email" id="email" value="{{ old('email') }}" required
				autofocus />
		</div>

		<!-- Password -->
		<div class="mt-3">
			<x-label for="password" :value="__('Password')" />
			<x-input type="password" name="password" id="password" required
				autocomplete="current-password" />
		</div>

		<div class="flex justify-between mt-4">
			<!-- Remember Me -->
			<div>
				<label class="inline-flex items-center">
					<input type="checkbox" class="form-checkbox text-indigo-600" name="remember">
					<span class="mx-2 text-gray-600 text-sm">{{ __('Remember me') }}</span>
				</label>
			</div>

			<div>
				@if (Route::has('password.request'))
				<a class="block text-sm fontme text-indigo-700 hover:underline"
					href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
				@endif
			</div>
		</div>

		<div class="flex flex-col items-end mt-4">
			<x-button class="w-full">
				{{ __('Log in') }}
			</x-button>

			<div class="flex space-x-4 mt-4 mx-auto">
				<a href="{{ route('social.login', 'facebook') }}"
					class="p-2 rounded-full shadow-lg shadow-slate-600">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48"
						height="48">
						<path fill="none" d="M0 0h24v24H0z" />
						<path
							d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"
							fill="rgba(50,152,219,1)" />
					</svg>
				</a>

				<a href="{{ route('social.login', 'google') }}"
					class="p-2 rounded-full shadow-lg shadow-slate-600">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48"
						height="48">
						<path fill="none" d="M0 0h24v24H0z" />
						<path
							d="M3.064 7.51A9.996 9.996 0 0 1 12 2c2.695 0 4.959.99 6.69 2.605l-2.867 2.868C14.786 6.482 13.468 5.977 12 5.977c-2.605 0-4.81 1.76-5.595 4.123-.2.6-.314 1.24-.314 1.9 0 .66.114 1.3.314 1.9.786 2.364 2.99 4.123 5.595 4.123 1.345 0 2.49-.355 3.386-.955a4.6 4.6 0 0 0 1.996-3.018H12v-3.868h9.418c.118.654.182 1.336.182 2.045 0 3.046-1.09 5.61-2.982 7.35C16.964 21.105 14.7 22 12 22A9.996 9.996 0 0 1 2 12c0-1.614.386-3.14 1.064-4.49z"
							fill="rgba(231,76,60,1)" />
					</svg>
				</a>
			</div>

			<a class="mt-4 text-sm text-gray-600 underline hover:text-gray-900"
				href="{{ route('register') }}">
				{{ __('Not registered?') }}
			</a>
		</div>

	</form>
</x-guest-layout>