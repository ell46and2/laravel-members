@extends('layouts.base')

@section('content')

	<div class="flow-vertical--2">
		<div class="panel">
	        <div class="panel__inner">
	            <div class="panel__main panel__main--3-columns">
	                <div class="profile-summary">
	                    <div class="[ profile-summary__avatar ] [ avatar avatar--red ]">
	                        <div class="avatar__image" style="background-image:url({{ auth()->user()->getAvatar() }});"></div>
	                    </div>
	                    <div class="profile-summary__main">
	                        <h2 class="profile-summary__greeting-primary">Hi {{ $jet->full_name }},</h2>
	                        <div class="profile-summary__greeting-secondary">Welcome back to your JETS dashboard</div>
	                        <div>
	                            <a class="button button--primary" href="">View profile</a>
	                        </div>
	                    </div>
	                </div>
	                <div>
	                    {{-- <dl class="definition-list">
	                        <dt>Licence type</dt>
	                        <dd>Apprentice</dd>

	                        <dt>Season rides</dt>
	                        <dd>51</dd>

	                        <dt>Season wins</dt>
	                        <dd>376</dd>

	                        <dt>Prize money</dt>
	                        <dd>&pound;306,146.25</dd>
	                    </dl> --}}
	                </div>
	                <div>
	                    {{-- <dl class="definition-list">
	                        <dt>Current trainer</dt>
	                        <dd>Name Surname</dd>

	                        <dt>Riding weight</dt>
	                        <dd>8st 5lbs</dd>

	                        <dt>Associated content</dt>
	                        <dd><a class="link--underlined" href="">Stewards enquiries/reports</a></dd>
	                    </dl> --}}
	                </div>
	            </div>
	        </div>
	    </div>

	    @include('layouts.partials._messages-alert')
	</div>

@endsection