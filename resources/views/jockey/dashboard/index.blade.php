@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

					<img src="{{ $jockey->getAvatar() }}" alt="{{ $jockey->full_name }}">
					Hi {{ $jockey->full_name }}, this is your Jockey Dashboard

					<div>
						number of wins
					</div>
					<div>
						number of races
					</div>
					<div>
						{{ $jockey->trainingTimeThisMonth() }} Hours of coaching with all my coaches this month
					</div>
					<br><br>
					<div>
						<h3>Coaches</h3>

						@foreach($jockey->coaches as $coach)
							<div>
								{{ $coach->full_name }} <br>
								{{ $coach->trainingTimeWithJockeyThisMonth($jockey->id) }} hours coaching this month
							</div><br><br>
						@endforeach
					</div>
					<br>
					<div>
						<h3>Upcoming Activities</h3>
						{{-- Split out into a partial --}}
						@foreach($jockey->dashboardUpcomingActivities as $activity)
							{{ $activity->id }}<br>
							{{ $activity->type->name }}<br>
							{{ $activity->start->format('d/m/Y') }}<br>
							{{ $activity->start->format('H:i') }}<br>
							{{ $activity->location }}<br>
							<a href="{{ route('jockey.activity.show', $activity) }}">View</a>
							<br><br>
						@endforeach
					</div>
					<br>
					<div>
						<h3>Recent Activities</h3>
						{{-- Split out into a partial --}}
						@foreach($jockey->dashboardRecentActivities as $activity)
							{{ $activity->id }}<br>
							{{ $activity->type->name }}<br>
							{{ $activity->start->format('d/m/Y') }}<br>
							{{ $activity->start->format('H:i') }}<br>
							{{ $activity->location }}<br>
							<a href="{{ route('jockey.activity.show', $activity) }}">View</a>
							<br><br>
						@endforeach
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

@endsection