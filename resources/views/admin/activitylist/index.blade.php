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

                            <select class="form-control custom-select form-control--trans-light" name="coach" id="coach">
                                 <option 
                                    value=""
                                    {{ request()->coach ? ' selected="selected"' : '' }}
                                >
                                    Select Coach
                                </option>
                               @foreach($coaches as $coach)
                                    <option 
                                        value="{{ $coach->id }}"
                                        {{ request()->coach == $coach->id ? ' selected="selected"' : '' }}
                                    >
                                        {{ $coach->full_name }}
                                    </option>
                               @endforeach
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
                        <a href="{{ route('admin.activity-log') }}" class="[ filter-bar__filter-button ] [ button button--text-light ]">Reset</a>
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
                        <th>Coach</th>
                        <th>Jockey</th>
                        <th class="table__sortable-column">Date
                            <a 
                                class="table__sort-button {{ request()->order == 'desc' ? 'is-up' : 'is-down' }}"
                                href="{{ route('admin.activity-log', array_merge(request()->query(), [
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
                                <td aria-label="Coach">{{ $event->coach->full_name }}</td>
                                <td aria-label="Jockey">{{ $event->formattedJockeyOrGroup }}</td>
                                <td aria-label="Date">{{ $event->formattedStart }}</td>
                                <td aria-label="Time">{{ $event->formattedStartTime }}</td>
                                <td aria-label="Location">{{ $event->formattedLocation }}</td>
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