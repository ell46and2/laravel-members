@extends('layouts.app')

@section('content')
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
                                    Competency Assessment
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
                        {{-- <input type="date" name="from" value="{{ request()->from ?? request()->from }}"> --}}
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="to">To</label>
                        <datepicker-component 
                            name="to" 
                            placeholder="Select Date"
                            format="dd-MM-yyyy"
                            old="{{ request()->to ?? request()->to }}"
                        ></datepicker-component>
                        {{-- <input type="date" name="to" value="{{ request()->to ?? request()->to }}"> --}}
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Filter
                            </button>
                            <a href="{{ route('coach.activity-list') }}" class="btn btn-outline">Reset</a>
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
                                    href="{{ route('coach.activity-list', array_merge(request()->query(), [
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
                                    {{-- Number of comments for or from current user--}}
                                    {{ $event->comments ? $event->comments->count() : '' }}
                                    {{-- any unread for current user --}}
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
@endsection
