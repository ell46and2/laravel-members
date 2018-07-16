@component('mail::message')
# Your account has been created

New JETS account created

@component('mail::button', ['url' => route('jet.token-access', [
	'token' => $jet->access_token,
	'email' => $jet->email
])])
Go to site
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
