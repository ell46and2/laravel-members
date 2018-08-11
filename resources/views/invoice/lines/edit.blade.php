@extends('layouts.base')

@section('content')

<div class="panel">
    <div class="panel__inner">
        <div class="panel__main">
            <h1 class="[ heading--1 ] [ mb-1 ]">Edit Activity Invoice Line</h1>
            If a jockey has gone over their allocated training allowance you can amend the amount here.
        </div>
    </div>
</div>

<form method="POST" action="{{ route('invoice-line.update', [$invoice, $invoiceLine]) }}">
    {{ csrf_field() }}
    @method('put')
        
    <div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    {{ $invoiceLine->activity->formattedType }}
                </h2>
            </div>

            <div class="panel__main">
                <div class="row">
  

                    <div class="col-md-4">
                        <dt>
                            <label class="text--color-blue" for="date">Date</label>
                        </dt>
                        <dd>
                           <p>{{ $invoiceLine->activity->formattedStartDayMonth }}</p>
                        </dd>
                    </div>

                    <div class="col-md-4">
                        <dt>
                            <label class="text--color-blue" for="date">Jockey</label>
                        </dt>
                        <dd>
                            @if($invoiceLine->activity->jockeys->count() === 1)
                                {{ $invoiceLine->activity->jockeys->first()->full_name }}
                            @else
                                <div class="d-flex align-items-center">
                                    <div class="table__icon">
                                        @svg( 'group', 'icon')
                                    </div>
                                    <div>
                                        Group ({{ $invoiceLine->activity->jockeys->count() }})
                                    </div>
                                </div>
                            @endif
                        </dd>
                    </div>

                    <div class="col-md-4">
                        @include('form.partials._input', [
                            'placeholder' => 'Enter Amount...',
                            'label' => 'Amount',
                            'field' => 'value',
                            'type' => 'number',
                            'attributes' => 'required',
                            'errors' => $errors,
                            'value' => $invoiceLine->value
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="button button--success button--block" type="submit">Update Invoice Line</button>
</form>

@endsection