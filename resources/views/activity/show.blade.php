@extends('layouts.base')

@section('content')
<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <div class="row">
                <div class="col-6">
                    <h1 class="[ heading--1 heading--has-button ] [ mb-1 ]">{{ $activity->isGroup() ? 'Group' : '1:1' }} {{ $activity->formattedType }}                      
                        @if(($isAdmin || $isAssignedCoach) && $activity->canBeEdited())
                        	<a class="[ button button--primary ] [ heading__button ]" href="{{ route('activity.edit', $activity) }}">Edit</a>
							<form 
								method="POST" 
								action="{{ route('activity.delete', $activity) }}"
								class="[ js-confirmation ]"
							    data-confirm="Are you sure you want to delete this activity?"
							>
								{{ csrf_field() }}
					    		<input type="hidden" name="_method" value="delete" />
					    		<button 
					    			style="display: inherit;" 
					    			class="[ button button--primary ] [ heading__button ]" 
					    			type="submit"
					    		>Delete</button>
							</form>
						@endif
                    </h1>
                    Your Activity Log shows your recent and upcoming activities
                </div>
                <div class="col-6">
                    <div class="text-right text--color-blue-dark">
                        I.D {{ $activity->id }}
                    </div>
                    <div class="text-right text--color-blue-lighter text--size-sm">
                        Last Updated on {{ $activity->formattedUpdatedOn }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__pre-header">
            <div class="panel__pre-header-primary">
                <ul class="panel__pre-header-definition-list">
                    @if($isAdmin)
                        <li>
                            <dl>
                                <dt>Coach</dt>
                                <dd>{{ $activity->coach->full_name }}</dd>
                            </dl>
                        </li>
                    @endif
                    <li>
                        <dl>
                            <dt>Date</dt>
                            <dd>{{ $activity->formattedStart }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Start time</dt>
                            <dd>{{ $activity->formattedStartTimeAmPm }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Duration</dt>
                            <dd>{{ $activity->formattedDuration }}</dd>
                        </dl>
                    </li>
                    <li>
                        <dl>
                            <dt>Location</dt>
                            <dd>{{ $activity->formattedLocation }}</dd>
                        </dl>
                    </li>
                </ul>
            </div>
        </div>

        <div class="panel__header">
            <h2 class="panel__heading">
                {{ str_plural('Jockey', $activity->jockeys->count()) }}
            </h2>
        </div>

        <div class="panel__main">
            <div class="row row--grid">
                @foreach($activity->jockeys as $jockey)
                    <div class="col-md-3">
                        <div class="user-card">
                            <div class="[ user-card__avatar ] [ avatar ]">
                                <div class="avatar__image" style="background-image:url('{{ $jockey->getAvatar() }}');"></div>
                            </div>
                            <div class="user-card__main">
                                <div class="user-card__name">{{ $jockey->full_name }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__header">
            <h2 class="panel__heading">
                {{ $activity->isGroup() ? 'Group ' : '' }}Activity Information
            </h2>
        </div>

        <div class="panel__main">
            {!! $activity->information ?? '<i>no information</i>' !!}
        </div>

    </div>
</div>

<attachment-upload
    model-type="activity"
    model-id="{{ $activity->id }}"
    resource="{{ json_encode($attachmentsResource) }}"
    can-edit="{{ $isAdmin || $isAssignedCoach }}"
></attachment-upload>


<div class="[ tabs ] [ js-tabs ]">
    <ul class="tabs__nav">
        @foreach($activity->jockeys as $index => $jockey)
                {{-- For Jockey user --}}
        @if($isAssignedJockey)
            @if($jockey->id === $currentUser->id)
                <li class="tabs__item">
                    <button class="tabs__button{{ $index === 0 ? ' is-active' : '' }}" type="button" data-toggle="tab-{{ $index }}">
                        {{ $jockey->full_name }}
                    </button>
                </li>
            @endif
        @elseif($isAdmin || $isAssignedCoach)
            <li class="tabs__item">
                <button class="tabs__button{{ $index === 0 ? ' is-active' : '' }}" type="button" data-toggle="tab-{{ $index }}">
                    {{ $jockey->full_name }}
                </button>
            </li>
        @elseif($isCoach)
            @if($jockey->isAssignedToCoach($currentUser->id))
                <li class="tabs__item">
                    <button class="tabs__button{{ $index === 0 ? ' is-active' : '' }}" type="button" data-toggle="tab-{{ $index }}">
                        {{ $jockey->full_name }}
                    </button>
                </li>
            @endif
        @endif
            
        @endforeach
        {{-- <li class="tabs__item">
            <button class="tabs__button is-active" type="button" data-toggle="tab-1">Name Surname</button>
        </li>
        <li class="tabs__item">
            <button class="tabs__button" type="button" data-toggle="tab-2">Name Surname</button>
        </li>
        <li class="tabs__item">
            <button class="tabs__button" type="button" data-toggle="tab-3">Name Surname</button>
        </li>
        <li class="tabs__item">
            <button class="tabs__button" type="button" data-toggle="tab-4">Name Surname</button>
        </li> --}}
    </ul>
    <div class="tabs__content">
        @foreach($activity->jockeys as $index => $jockey)
            {{-- For Jockey user --}}
            @if($isAssignedJockey)
                @if($jockey->id === $currentUser->id)
                    <div id="tab-{{ $index }}" class="tabs__tab-pane{{ $index === 0 ? ' is-active' : '' }}">
                        <div class="panel">
                            <div class="panel__inner">
                                <div class="panel__header">
                                    <h2 class="panel__heading">
                                        Activity Feedback for {{ $jockey->full_name }}
                                    </h2>
                                    {{-- <div class="panel__header-meta">
                                        Last updated on 23/05/2018
                                    </div> --}}
                                </div>

                                <div class="panel__main">
                                   
                                        <add-feedback 
                                            activity-id="{{ $activity->id }}" 
                                            jockey-id="{{ $jockey->id }}"
                                            current-feedback="{{ $jockey->pivot->feedback }}"
                                        ></add-feedback>

                                        <div class="flow-vertical--3 pt-3">
                                            <h2 class="heading--2">Activity Comments</h2>
                                            <div class="comments">
                                                <comments
                                                    endpoint="{{ route('activity.comment.index', $activity) }}"
                                                    recipient-id="{{ $currentRole === 'jockey' ? $activity->coach_id : $jockey->id }}"
                                                    jockey-id="{{ $jockey->id }}"
                                                    is-current-user-jockey="{{ $currentUser->isJockey() }}"
                                                    can-user-add-comments="{{ $activity->isAssignedToUser($currentUser) }}"
                                                ></comments>
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @elseif($isAdmin || $isAssignedCoach)
                <div id="tab-{{ $index }}" class="tabs__tab-pane{{ $index === 0 ? ' is-active' : '' }}">
                    <div class="panel">
                        <div class="panel__inner">
                            <div class="panel__header">
                                <h2 class="panel__heading">
                                    Activity Feedback for {{ $jockey->full_name }}
                                </h2>
                                {{-- <div class="panel__header-meta">
                                    Last updated on 23/05/2018
                                </div> --}}
                            </div>

                            <div class="panel__main">
                               
                                    <add-feedback 
                                        activity-id="{{ $activity->id }}" 
                                        jockey-id="{{ $jockey->id }}"
                                        current-feedback="{{ $jockey->pivot->feedback }}"
                                    ></add-feedback>

                                    <div class="flow-vertical--3 pt-3">
                                        <h2 class="heading--2">Activity Comments</h2>
                                        <div class="comments">
                                            <comments
                                                endpoint="{{ route('activity.comment.index', $activity) }}"
                                                recipient-id="{{ $currentRole === 'jockey' ? $activity->coach_id : $jockey->id }}"
                                                jockey-id="{{ $jockey->id }}"
                                                is-current-user-jockey="{{ $currentUser->isJockey() }}"
                                                can-user-add-comments="{{ $activity->isAssignedToUser($currentUser) }}"
                                            ></comments>
                                        </div>
                                    </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($isCoach)
                @if($jockey->isAssignedToCoach($currentUser->id))
                    <div id="tab-{{ $index }}" class="tabs__tab-pane{{ $index === 0 ? ' is-active' : '' }}">
                        <div class="panel">
                            <div class="panel__inner">
                                <div class="panel__header">
                                    <h2 class="panel__heading">
                                        Activity Feedback for {{ $jockey->full_name }}
                                    </h2>
                                    {{-- <div class="panel__header-meta">
                                        Last updated on 23/05/2018
                                    </div> --}}
                                </div>

                                <div class="panel__main">
                                   
                                        <add-feedback 
                                            activity-id="{{ $activity->id }}" 
                                            jockey-id="{{ $jockey->id }}"
                                            current-feedback="{{ $jockey->pivot->feedback }}"
                                        ></add-feedback>

                                        <div class="flow-vertical--3 pt-3">
                                            <h2 class="heading--2">Activity Comments</h2>
                                            <div class="comments">
                                                <comments
                                                    endpoint="{{ route('activity.comment.index', $activity) }}"
                                                    recipient-id="{{ $currentRole === 'jockey' ? $activity->coach_id : $jockey->id }}"
                                                    jockey-id="{{ $jockey->id }}"
                                                    is-current-user-jockey="{{ $currentUser->isJockey() }}"
                                                    can-user-add-comments="{{ $activity->isAssignedToUser($currentUser) }}"
                                                ></comments>
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</div>

@endsection