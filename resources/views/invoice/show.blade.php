@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        
       <a href="{{ route('invoice.add', $invoice) }}" class="btn btn-primary">Add Items</a>
    </div>
</div>
@endsection
