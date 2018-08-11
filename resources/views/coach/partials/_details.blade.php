<div class="panel__header">
    <h2 class="panel__heading">
        Details
    </h2>
</div>

<div class="panel__main flow-vertical--2">

    @include('user.partials._detail', [
        'label' => 'First name',
        'value' => $coach->first_name
    ])

    @include('user.partials._detail', [
        'label' => 'Last name',
        'value' => $coach->last_name
    ])

    @include('user.partials._detail', [
        'label' => 'Middle name',
        'value' => $coach->middle_name
    ])

    @include('user.partials._detail', [
        'label' => 'Alias',
        'value' => $coach->alias
    ])

    @include('user.partials._detail', [
        'label' => 'Address',
        'value' => $coach->fullAddress
    ])

    @include('user.partials._detail', [
        'label' => 'Telephone',
        'value' => $coach->telephone
    ])

    @include('user.partials._detail', [
        'label' => 'Twitter handle',
        'value' => $coach->twitter_handle
    ])

    @include('user.partials._detail', [
        'label' => 'Email Address',
        'value' => $coach->email
    ])

    @include('user.partials._detail', [
        'label' => 'Bio',
        'value' => $coach->bio
    ])

</div>