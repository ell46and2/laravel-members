@component('mail::message')
# Thank you for registering

Dear {{ $jockey->first_name }} {{ $jockey->last_name}}

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
