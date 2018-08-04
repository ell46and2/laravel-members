<div class="[ col-md-12 col-xl-4 ]">
    <div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    PDP Status
                </h2>
            </div>

            <div class="panel__main">
                <ul class="icon-list">
                    @foreach(config('jcp.pdp_fields') as $page)
                        <li class="icon-list__item{{ isCurrentPdpPage($page['field']) ? ' icon-list__item--has-emphasis' : '' }} ">
                            <div class="icon-list__icon" aria-hidden="true" role="presentation">
                                @if($pdp->{$page['field']. '_page_complete'})
                                   @svg('tick-circle', 'icon')
                                @else
                                    @svg('circle', 'icon') 
                                @endif             
                            </div>
                            <div class="icon-list__inner">
                                <a class="icon-list__link" href="{{ route(pdpFieldToLink($page['field']), $pdp) }}">{{ $page['label'] }}</a>
                            </div>
                        </li>
                        @if($pdp->status === 'In Progress' && $currentRole === 'jockey')
                            <li class="icon-list__item">
                                <div class="icon-list__icon" aria-hidden="true" role="presentation">
                                    @svg('circle', 'icon') 
                                </div>
                            <div class="icon-list__inner">
                                <a class="icon-list__link" href="{{ route('pdp.approve', $pdp) }}">Submit For Approval</a>
                            </div>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="panel__split-call-to-action">
                @php
                    $links = pdpNextPrevLinks(request()->path());
                    $nextLink = $links->next ? $links->next : null;

                    if(!$nextLink && $currentRole === 'jockey' && $pdp->status === 'In Progress') {
                        $nextLink = 'pdp.submit';
                    }
                @endphp
                <a 
                    class="panel__call-to-action" 
                    href="{{ $links->previous ? route($links->previous, $pdp) : '#' }}"
                    @if(!$links->previous)
                        disabled
                    @endif
                >
                    @if($links->previous)
                        Previous
                    @endif
                </a>
                <a 
                    class="panel__call-to-action" 
                    href="{{ $nextLink ? route($nextLink, $pdp) : '#' }}"
                >Next</a>
            </div>
        </div>
    </div>
</div>