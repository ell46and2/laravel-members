@extends('layouts.app')

@section('content')

<div class="nummessages">
	{{-- pass auth()->user()->unreadMessagesCount() into layout partial via view composer --}}
	You have {{ auth()->user()->unreadMessagesCount() }} unread {{ str_plural('message', auth()->user()->unreadMessagesCount()) }}
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">

					<img src="{{ $admin->getAvatar() }}" alt="{{ $admin->full_name }}">
					Hi {{ $admin->full_name }}, Welcome back to your admin dashboard

					<br><br>
					<div>
						<h3>Jockeys Awaiting Approval</h3>

						@foreach($jockeysAwaitingApproval as $jockey)
							<div>
								<img src="{{ $jockey->getAvatar() }}" alt="{{ $jockey->full_name }}">
								{{ $jockey->full_name }} <br>
								Awaiting approval <br>
								{{ $jockey->formattedJoined }} 
					
							</div><br><br>
						@endforeach
					</div>
					<br><br>
					<div>
						<h3>Most Active Coaches</h3>
						@foreach($mostActiveCoaches as $coach)
							<div>
								<img src="{{ $coach->getAvatar() }}" alt="{{ $coach->full_name }}">
								{{ $coach->overallTrainingTimeThisMonth() }} Hours coaching this month
								<br>
								{{ $coach->jockeys->count() }} Jockeys Assigned
								<br>
								@if(optional($coach->pendingReviewInvoice)->count())
									1 pending invoice
								@endif
								
							</div>
							<br>
						@endforeach
					</div>


					<br><br><br>
					<h3>Invoices to Process</h3>
					@foreach($invoicesAwaitingReview->take(4) as $invoice)
						<p>{{ $invoice->coach->full_name }}</p>
						<p>{{ $invoice->submittedDate }}</p>
						<p>£{{ $invoice->overallValue }}</p>
					@endforeach
					<p>You have {{ $invoicesAwaitingReview->count() }} invoices ready for review</p>
				</div>			
            </div>
        </div>
    </div>
</div>

{{-- <notifications
	resource="{{ json_encode($notificationsResource) }}"
></notifications> --}}

@endsection