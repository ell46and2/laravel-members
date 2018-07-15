@component('mail::message')
# Your account has been created

New coach account created

@component('mail::button', ['url' => route('coach.token-access', [
	'token' => $coach->access_token,
	'email' => $coach->email
])])
Go to site
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
