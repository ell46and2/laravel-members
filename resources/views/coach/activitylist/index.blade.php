@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Activity log</h1>
            Your Activity Log shows your recent and upcoming activities
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel__inner">
        <div class="panel__pre-header">
            <div class="panel__pre-header-primary">
                <form class="filter-bar" action="" method="GET">
                    <div class="filter-bar__filters-wrapper">
                        <div class="filter-bar__label">
                            Filter by
                        </div>
                        
                        <div class="filter-bar__filters">
                            {{-- Filter 1 --}}
                            <select class="form-control custom-select form-control--trans-light" name="type" id="type">
                                <option 
                                    value=""
                                    {{ request()->type ? ' selected="selected"' : '' }}
                                >
                                    Select Activity
                                </option>

                                @foreach($activityTypes as $type)
                                    <option 
                                        value="{{ $type->id }}"
                                        {{ request()->type == $type->id ? ' selected="selected"' : '' }}
                                    >
                                        {{ ucwords($type->name) }}
                                    </option>
                                @endforeach
                                
                                <option 
                                    value="re"
                                    {{ request()->type == 're' ? ' selected="selected"' : '' }}
                                >
                                    Racing Excellence
                                </option>
                                <option 
                                    value="ca"
                                    {{ request()->type == 'ca' ? ' selected="selected"' : '' }}
                                >
                                    Skills Profile
                                </option>
                            </select>
                            

                            {{-- Filter 2 --}}                        
                            <select class="form-control custom-select form-control--trans-light" name="jockey" id="jockey">
                                 <option 
                                    value=""
                                    {{ request()->jockey ? ' selected="selected"' : '' }}
                                >
                                    Select Jockey
                                </option>
                               @foreach($jockeys as $jockey)
                                    <option 
                                        value="{{ $jockey->id }}"
                                        {{ request()->jockey == $jockey->id ? ' selected="selected"' : '' }}
                                    >
                                        {{ $jockey->full_name }}
                                    </option>
                               @endforeach
                            </select>                           

                            {{-- filter 3 --}}
                            <datepicker-component
                                overwrite-classes="form-control form-control--has-icon form-control--trans-light"
                                name="from" 
                                placeholder="From Date"
                                format="dd-MM-yyyy"
                                old="{{ request()->from ?? request()->from }}"
                            ></datepicker-component>
                            
                            {{-- Filter 4 --}}
                            <datepicker-component 
                                overwrite-classes="form-control form-control--has-icon form-control--trans-light"
                                name="to" 
                                placeholder="To Date"
                                format="dd-MM-yyyy"
                                old="{{ request()->to ?? request()->to }}"
                            ></datepicker-component>
                        </div>
                    </div>

                    <div class="filter-bar__actions">
                        <a href="{{ route('coach.activity-log') }}" class="[ filter-bar__filter-button ] [ button button--text-light ]">Reset</a>
                        <button class="[ filter-bar__filter-button ] [ button button--primary button--white ]" type="submit">
                            Filter
                        </button>
                    </div>
                    
                </form>
            </div>
        </div>

       {{--  <div class="panel__header">
            <h2 class="panel__heading">
                Recent Activities
            </h2>
        </div> --}}

        <div class="panel__main">
            <table class="table table-hover table--stacked-md">
                <thead>
                    <tr>
                        <th>Activity Type</th>
                        <th>Jockey</th>
                        <th class="table__sortable-column">Date
                            <a 
                                class="table__sort-button {{ request()->order == 'desc' ? 'is-up' : 'is-down' }}"
                                href="{{ route('coach.activity-log', array_merge(request()->query(), [
                                    'order' => request()->order == 'desc' ? 'asc' : 'desc', 
                                    'page' => 1
                                ])) }}"
                            >
                                <span class="sr-only">Toggle sort direction</span>
                                <span class="table__sort-button-icon table__sort-button-icon--up">
                                    @svg('angle-up', 'icon')
                                </span>
                                <span class="table__sort-button-icon table__sort-button-icon--down">
                                    @svg('angle-down', 'icon')
                                </span>
                            </a>
                        </th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Comments</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @if($events->count())
                        @foreach($events as $event)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="table__icon">
                                            @svg( $event->icon, 'icon')
                                        </span>
                                        <a class="table__link" href="{{ $event->notificationLink }}">{{ $event->formattedType }}</a>
                                    </div>
                                    
                                </td>
                                <td aria-label="Jockey">{{ $event->formattedJockeyOrGroup }}</td>
                                <td aria-label="Date">{{ $event->formattedStart }}</td>
                                <td aria-label="Time">{{ $event->formattedStartTime }}</td>
                                <td aria-label="Location">{{ $event->formattedLocation }}</td>
                                <td aria-label="Comments">
                                    @if(optional($event->comments)->count())
                                        <div class="icon-notify">
                                            @svg('conversation', 'icon')
                                            <div class="icon-notify__badges">
                                                <span class="[ icon-notify__badge ] [ badge badge-pill badge-dark ]">{{ $event->comments->count() }}</span>
                                                @if(optional($event->unreadCommentsOnActivityForCurentUser)->count())
                                                    <span class="[ icon-notify__badge ] [ badge badge-pill badge-dark ]">NEW</span>
                                                @endif
                                                
                                            </div>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                               {{--  <td class="table__icon-column">
                                    <a class="table__icon-button" href="">
                                        {% include "svg/edit.svg" %
                                        @svg('edit', 'icon')
                                    </a>
                                </td> --}}
                                <td class="text-right">
                                    <a class="button button--primary" href="{{ $event->notificationLink }}">View</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <td class="text-center" colspan="6">
                            No items
                        </td>
                    @endif
                </tbody>
            </table>
            
        {{ $events->appends(request()->query())->links() }} 
</div>

@endsection

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Activity list</div>

                <form method="GET" action="">
                    
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="type">Activity Type</label>
                        
                        <div class="col-md-6">
                            <select class="form-control" name="type" id="type">
                                <option 
                                    value=""
                                    {{ request()->type ? ' selected="selected"' : '' }}
                                >
                                    Select Activity
                                </option>

                                @foreach($activityTypes as $type)
                                    <option 
                                        value="{{ $type->id }}"
                                        {{ request()->type == $type->id ? ' selected="selected"' : '' }}
                                    >
                                        {{ ucwords($type->name) }}
                                    </option>
                                @endforeach
                                
                                <option 
                                    value="re"
                                    {{ request()->type == 're' ? ' selected="selected"' : '' }}
                                >
                                    Racing Excellence
                                </option>
                                <option 
                                    value="ca"
                                    {{ request()->type == 'ca' ? ' selected="selected"' : '' }}
                                >
                                    Skills Profile
                                </option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="jockey">Jockey</label>
                        
                        <div class="col-md-6">
                            <select class="form-control" name="jockey" id="jockey">
                                 <option 
                                    value=""
                                    {{ request()->jockey ? ' selected="selected"' : '' }}
                                >
                                    Select Jockey
                                </option>
                               @foreach($jockeys as $jockey)
                                    <option 
                                        value="{{ $jockey->id }}"
                                        {{ request()->jockey == $jockey->id ? ' selected="selected"' : '' }}
                                    >
                                        {{ $jockey->full_name }}
                                    </option>
                               @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="from">From</label>
                        <datepicker-component 
                            name="from" 
                            placeholder="Select Date"
                            format="dd-MM-yyyy"
                            old="{{ request()->from ?? request()->from }}"
                        ></datepicker-component>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="to">To</label>
                        <datepicker-component 
                            name="to" 
                            placeholder="Select Date"
                            format="dd-MM-yyyy"
                            old="{{ request()->to ?? request()->to }}"
                        ></datepicker-component>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Filter
                            </button>
                            <a href="{{ route('coach.activity-log') }}" class="btn btn-outline">Reset</a>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <th scope="col">Id</th>
                            <th scope="col">Activity Type</th>
                            <th scope="col">Jockey</th>
                            <th scope="col">
                                Date
                                <a 
                                    href="{{ route('coach.activity-log', array_merge(request()->query(), [
                                        'order' => request()->order == 'desc' ? 'asc' : 'desc', 
                                        'page' => 1
                                    ])) }}" 
                                    class="btn btn-primary"
                                >Order</a>
                            </th>
                            <th scope="col">Time</th>
                            <th scope="col">Location</th>
                            <th scope="col">Comments</th>
                            <th scope="col">Btns</th>
                        </thead>
                    
                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td>{{ $event->id }}</td>
                                    <td>{{ $event->formattedType }}</td>
                                    <td>{{ $event->formattedJockeyOrGroup }}</td>
                                    <td>{{ $event->formattedStart }}</td>
                                    <td>{{ $event->formattedStartTime }}</td>
                                    <td>{{ $event->formattedLocation }}</td>
                                    <td>
                                    {{ $event->comments ? $event->comments->count() : '' }}
                                    {{ $event->unreadCommentsOnActivityForCurentUser ? $event->unreadCommentsOnActivityForCurentUser->count() > 0 ? '*new' : '' : '' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                   {{ $events->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
