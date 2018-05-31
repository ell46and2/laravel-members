@component('mail::message')
# New Jockey Registration

Jockey {{ $user->first_name }} {{ $user->last_name}} has registered

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
