@component('mail::message')
# New Jockey Registration

Jockey {{ $jockey->first_name }} {{ $jockey->last_name}} has registered

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
