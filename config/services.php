<?php

return [

	/*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

	'mailgun' => [
		'domain'   => env('MAILGUN_DOMAIN'),
		'secret'   => env('MAILGUN_SECRET'),
		'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
		'scheme'   => 'https',
	],

	'postmark' => [
		'token' => env('POSTMARK_TOKEN'),
	],

	'ses' => [
		'key'    => env('AWS_ACCESS_KEY_ID'),
		'secret' => env('AWS_SECRET_ACCESS_KEY'),
		'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
	],

	'facebook' => [
		'client_id'     => '719444015815396',
		'client_secret' => '07b959b7ad29a91c3127f0a0428a24e6',
		'redirect'      => 'http://localhost:8000/auth/callback/facebook',
	],

	'google' => [
		'client_id'     => '125109212140-sl3lj9vrtecm1bq2u1p489islb0lrh9l.apps.googleusercontent.com',
		'client_secret' => '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',
		'redirect'      => 'http://localhost:8000/auth/callback/google',
	],
];
