@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">My Jockeys</h1>
            A list of jockeys you currently coach
        </div>
    </div>
</div>

<profile-search
	resource="{{ json_encode($jockeysResource) }}"
	user-type="jockey"
></profile-search>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                My Jockeys
            </h2>
        </div>

        <div class="panel__main">
            <table class="table table-hover table--stacked-xxs table--stacked-xs table--stacked-sm table--stacked-md">
                <thead>
                    <tr>
                        <th>Jockey</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th>Hours Coaching this month</th>
                        <th>Last Login</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jockeys as $jockey)
                    	@php 
                    		$trainingAllowance = $jockey->trainingAllowanceThisMonth();
                        	$trainingWithCoach = $coach->trainingTimeWithJockeyThisMonth($jockey->id);
                        	$trainingPercentage = getPercentage($trainingWithCoach->duration, $trainingAllowance);
                        	$overallTrainingPercentage = getPercentage($jockey->trainingTimeThisMonth(), $trainingAllowance);
                    	@endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="[ table__avatar ] [ avatar avatar--{{ $coach->lastActivityDateColourCode($jockey) }} ]">
                                        <div class="avatar__image" style="background-image:url('{{ $jockey->getAvatar() }}');"></div>
                                    </div>
                                    {{ $jockey->full_name }}
                                </div>
                            </td>
                            <td aria-label="Email">{{ $jockey->email }}</td>
                            <td aria-label="Telephone">{{ $jockey->telephone }}</td>
                            <td aria-label="Hours Coaching this month">
                                <div class="progress-bar">
                                    <span class="sr-only">Progress: {{ $trainingPercentage }}%</span>
                                    <div class="progress-bar__bar" aria-hidden="true" role="presentation">
                                        <div class="progress-bar__primary" style="width: {{ $trainingPercentage }}%;"></div>
                                        <div class="progress-bar__secondary" style="width: {{ $overallTrainingPercentage }}%;">
                                    </div>
                                    <div class="progress-bar__labels">
                                        <div>0 hours</div>
                                        <div>{{ $trainingAllowance }} hours</div>
                                    </div>
                                </div>
                            </td>
                            <td aria-label="Last Login">{{ $jockey->formattedLastLogin }}</td>
                            <td class="text-right">
                                <a class="button button--primary" href="{{ route('jockey.show', $jockey) }}">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        {{ $jockeys->links() }}
</div>



{{-- <div class="container">
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
								{{ $coach->trainingTimeWithJockeyThisMonth($jockey->id)->duration }} Hours coaching this month
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
</div> --}}


@endsection