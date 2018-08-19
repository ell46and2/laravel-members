@extends('layouts.base')

@section('content')

	<div class="panel">
        <div class="panel__inner">
            <div class="panel__main">
                <h1 class="[ heading--1 ] [ mb-1 ]">JETS - PDP</h1>
                JETS Personal Development Plan
            </div>
        </div>
    </div>

    <jockey-select
        resource="{{ json_encode($jockeysResource) }}"
    ></jockey-select>


	<div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    JETS - PDP Archive
                </h2>
            </div>

            <div class="panel__main">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Jockey</th>
                            <th>PDP Name</th>
                            <th>Status</th>
                            <th>Completed Date</th>
                            <th>Last Modified</th>
                            <th>Next Review</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pdps as $pdp)
                            <tr>
                                <td>{{ $pdp->jockey->full_name }}</td>
                                <td>{{ $pdp->name }}</td>
                                <td>{{ $pdp->status }}</td>
                                <td>{{ $pdp->formattedSubmitted }}</td>
                                <td>{{ $pdp->formattedUpdatedAt }}</td>
                                <td>{{ $pdp->daysTillPerformanceReview }}</td>
                                <td class="text-right">
									<a href="{{ route('pdp.personal-details', $pdp) }}" class="button button--primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $pdps->links() }}

                {{-- <nav aria-label="Table Pagination" class="mt-2">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">2</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="panel__split-call-to-action">
                <a class="panel__call-to-action" tabindex="-1" disabled href=""></a>
                <a class="panel__call-to-action" href="">Next</a>
            </div> --}}
        </div>
    </div>

@endsection