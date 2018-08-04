@include('layouts.partials._messages-alert')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Complete PDP</h1>
            Personal Development Plan
        </div>

        <div class="panel__alert panel__alert--has-icon">
            <div>
                @svg('info-circle', 'icon')
                @if($currentRole === 'jockey')
                	{{ $jockey->first_name }}, you have completed 
                @else
                	Completed
                @endif
                {{ $pdp->percentageComplete }}% of this PDP
            </div>
        </div>
    </div>
</div>

@if($pdp->status === 'Awaiting Review' && $currentRole === 'jets')
    <form method="POST" action="{{ route('pdp.complete', $pdp) }}">
        {{ csrf_field() }}
        <button class="button button--primary button--block" type="submit">Approve PDP</button>
    </form>
@endif

<div class="row [ flow-vertical--3 ]">
    <div class="[ col-md-12 col-xl-8 mb-2 mb-xl-0 ]">
		{{ $slot }}
	</div>

	@include('pdp.partials._status')

</div>
