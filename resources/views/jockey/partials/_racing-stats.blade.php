{{-- BHA Racing Stats --}}
<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                BHA Racing Stats
{{--                 <div class="text--color-base text--size-base font-weight-normal mt-1">
                    Control your login, password and notification settings.
                </div> --}}
            </h2>
        </div>
        <div class="panel__main flow-vertical--2">
        	@if(auth()->user()->isAdmin())
        		<form method="POST" action="{{ route('jockey.set-api', $jockey) }}">
        			{{ csrf_field() }}
		            <div class="form-group flow-vertical--1">
		                <label for="api_id" class="text--color-blue text--size-sm">Racing Post URL</label>
		                @include('form.partials._input', [
				            'field' => 'api_id',
				            'type' => 'text',
				            'errors' => $errors,
				            'value' => $jockey->api_id,
				            'placeholder'=> "Enter Racing Post URL…"
				        ])
		            </div>
		            <button class="button button--primary button--block" type="submit">Update</button>
		        </form>
		    @endif

            <dl class="definition-list">
                <dt>Licence/Permit Type</dt>
                <dd>{{ $jockey->licence_type ?? '-' }}</dd>

                <dt>Wins</dt>
                <dd>{{ $jockey->wins ?? '-' }}</dd>

                <dt>Rides</dt>
                <dd>{{ $jockey->rides ?? '-' }}</dd>

                <dt>Lowest Riding Weight</dt>
                <dd>{{ $jockey->lowest_riding_weight ?? '-' }}</dd>

                <dt>Price Money</dt>
                <dd>{{ $jockey->formattedPrizeMoney }}</dd>

                <dt>Associated Content</dt>
                <dd>{!! $jockey->formattedAssociatedContent !!}</dd>

                <dt>Sex</dt>
                <dd>{{ $jockey->formattedGender }}</dd>

                <dt>Age</dt>
                <dd>{{ $jockey->age }}</dd>

                <dt>Win to Rides</dt>
                <dd>{{ $jockey->formattedWinsToRides }}</dd>
            </dl>

        </div>
    </div>
</div>



{{-- <div>
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
</div>  --}}