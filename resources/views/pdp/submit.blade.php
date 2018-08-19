@extends('layouts.base')

@section('content')

@component('pdp.components._pdp', compact('pdp', 'jockey'))

<div class="panel">
    <div class="panel__inner">

        <div class="panel__header">
            <h2 class="panel__heading">
                  Send for Review
                  <div class="text--color-base text--size-base">Your PDP will be reviewed by a member of JETS</div>
            </h2>
        </div>
        
        <div class="panel__main">
        	<form method="POST" action="{{ route('pdp.submit.store', $pdp) }}">
                @csrf
                
                <button type="submit" class="button button--primary button--block mt-3">
                    Send
                </button>             
            </form>
        </div>
    </div>
</div>

@endcomponent

@endsection