<div>
	<h3>BHA Racing Stats</h3>
	@if(auth()->user()->isAdmin())
		<form method="POST" action="{{ route('jockey.set-api', $jockey) }}">
			{{ csrf_field() }}
			@include('form.partials._input', [
	            'label' => 'Jockey API ID',
	            'field' => 'api_id',
	            'type' => 'text',
	            'errors' => $errors,
	            'value' => $jockey->api_id
	        ])
	        <button class="btn btn-primary" type="submit">Update</button>
		</form>
	@endif
	<p>Licence/Permit Type {{ $jockey->licence_type ?? '-' }}</p>
	<p>Wins {{ $jockey->wins ?? '-' }}</p>
	<p>Rides {{ $jockey->rides ?? '-' }}</p>
	<p>Lowest Riding Weight {{ $jockey->lowest_riding_weight ?? '-' }}</p>
	<p>Prize Money {{ $jockey->formattedPrizeMoney }}</p>
	<p>Associated Content {!! $jockey->formattedAssociatedContent !!}</p>
	<p>Sex {{ $jockey->formattedGender }}</p>
	<p>Age {{ $jockey->age }}</p>
	<p>Wins to rides {{ $jockey->formattedWinsToRides }}</p>
</div>