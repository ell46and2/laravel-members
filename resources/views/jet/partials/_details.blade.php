<div class="panel__header">
    <h2 class="panel__heading">
        Details
    </h2>
</div>

<div class="panel__main flow-vertical--2">
    
    @include('user.partials._detail', [
        'label' => 'First name',
        'value' => $jet->first_name
    ])

    @include('user.partials._detail', [
        'label' => 'Last name',
        'value' => $jet->last_name
    ])

    @include('user.partials._detail', [
        'label' => 'Telephone',
        'value' => $jet->telephone
    ])

    @include('user.partials._detail', [
        'label' => 'Twitter handle',
        'value' => $jet->twitter_handle
    ])

    @include('user.partials._detail', [
        'label' => 'Email Address',
        'value' => $jet->email
    ])

</div>