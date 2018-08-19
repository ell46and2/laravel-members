@extends('layouts.base')

@section('content')

	<div class="panel">
        <div class="panel__inner">
            <div class="panel__main">
                <h1 class="[ heading--1 ] [ mb-1 ]">{{ $currentRole === 'jets' ? $jockey->full_name : 'JETS' }} - PDP</h1>
                JETS Personal Development Plan
            </div>

			@php

			@endphp
			@if($lastSubmitted)
				<div class="panel__alert panel__alert--has-icon">
	                <div>
	                     @svg('info-circle', 'icon')
	                    Name, in {{ $lastSubmitted->daysTillPerformanceReview }} days you have performance goals to review
	                </div>
	                <div class="alert__buttons">
	                    <a class="button button--white" href="{{ route('pdp.performance-goals', $lastSubmitted) }}">View My Recent Goals</a>
	                </div>
	            </div>
			@endif
            
        </div>
    </div>


	@if(!$currentPdp)
		<form method="POST" action="{{ route('pdp.create', $jockey) }}">
			{{ csrf_field() }}
			<button class="button button--primary button--block" type="submit">Create a{{ $previousPdps->count() ? ' Continuation' : '' }} PDP</button>
		</form>
	@endif

	<div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    JETS - PDP Archive
                </h2>
            </div>

            <div class="panel__main">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>PDP Name</th>
                            <th>Status</th>
                            <th>Completed Date</th>
                            <th>Last Modified</th>
                            <th>Goals Achived</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($previousPdps as $pdp)
                            <tr>
                                <td><a class="table__link" href="">{{ $pdp->name }}</a></td>
                                <td>{{ $pdp->status }}</td>
                                <td>{{ $pdp->formattedSubmitted }}</td>
                                <td>{{ $pdp->formattedUpdatedAt }}</td>
                                <td>{{ $pdp->goalsAchieved }}</td>
                                <td class="text-right">
                                    @if(!$pdp->submitted)
										<a href="{{ route('pdp.personal-details', $pdp) }}" class="button button--primary">Update</a>
									@else
										<a href="{{ route('pdp.personal-details', $pdp) }}" class="button button--primary">View</a>
									@endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $previousPdps->links() }}

                {{-- <nav aria-label="Table Pagination" class="mt-2">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">2</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="panel__split-call-to-action">
                <a class="panel__call-to-action" tabindex="-1" disabled href=""></a>
                <a class="panel__call-to-action" href="">Next</a>
            </div> --}}
        </div>
    </div>

@endsection