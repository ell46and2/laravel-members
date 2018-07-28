<div class="card">
    <div class="card-header">Profile</div>

    <div class="card-body">
        
        <p>First Name: {{ $coach->first_name }}</p>

        <p>Middle Name: {{ $coach->middle_name }}</p>

        <p>Last Name: {{ $coach->last_name }}</p>

        <p>Alias: {{ $coach->alias }}</p>

        <p>Address Line 1: {{ $coach->address_1 }}</p>

        <p>Address Line 2: {{ $coach->address_2 }}</p>

        <p>Country: {{ $coach->formattedCountry }}</p>

        <p>County: {{ $coach->formattedCounty }}</p>

        <p>Post Code: {{ $coach->postcode }}</p>

        <p>Telephone: {{ $coach->telephone }}</p>

        <p>Twitter Handle: {{ $coach->twitter_handle }}</p>

        <p>Email: {{ $coach->email }}</p>

        <p>Bio: {{ $coach->bio }}</p>
        
    </div>
</div>