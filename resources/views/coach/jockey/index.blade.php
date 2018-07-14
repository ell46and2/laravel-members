@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Your Jockeys</div>

                <div class="card-body">


						@foreach($coach->jockeys as $jockey)
							<div>
								{{ $jockey->full_name }} <br>
								{{ $jockey->email }} <br>
								{{ $jockey->telephone }} <br>
								Last login {{ $jockey->formattedLastLogin }} <br>
								{{ $coach->lastActivityDateColourCode($jockey) }} <br>
								{{ $coach->trainingTimeWithJockeyThisMonth($jockey->id) }} Hours coaching this month
								<br>
								Total training time is {{ $jockey->trainingTimeThisMonth() }}
								<br>
								@if($jockey->coaches->count() > 1)
									{{ $jockey->coaches->count() }} Coaches Assigned
								@endif
							</div><br><br>
						@endforeach
					</div>
					<br>
					
				</div>			
            </div>
        </div>
    </div>
</div>

{{-- <notifications
	resource="{{ json_encode($notificationsResource) }}"
></notifications> --}}

@endsection