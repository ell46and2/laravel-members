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
                        <li class="icon-list__item">
                            <div class="icon-list__icon{{ $pdp->{$page['field']. '_page_complete'} ? ' icon-list__icon--has-emphasis' : '' }}" aria-hidden="true" role="presentation">
                                @if($pdp->{$page['field']. '_page_complete'})

                                   @svg('tick-circle', 'icon')
                                @else
                                    @svg('circle', 'icon') 
                                @endif             
                            </div>
                            <div class="icon-list__inner{{ isCurrentPdpPage($page['field']) ? ' icon-list__inner--has-emphasis' : '' }}">
                                <a class="icon-list__link" href="{{ route(pdpFieldToLink($page['field']), $pdp) }}">{{ $page['label'] }}</a>
                            </div>
                        </li>                     
                    @endforeach

                    @if($pdp->status === 'In Progress' && $currentRole === 'jockey')
                        <li class="icon-list__item">
                            <div class="icon-list__icon" 
                                aria-hidden="true" 
                                role="presentation">
                                @svg('circle', 'icon') 
                            </div>
                        <div class="icon-list__inner{{ strpos(Request::url(), 'submit') !== false ? ' icon-list__inner--has-emphasis' : '' }}">
                            <a class="icon-list__link" href="{{ route('pdp.submit', $pdp) }}">Submit For Approval</a>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>

            <div class="panel__split-call-to-action">
                @php
                    $links = pdpNextPrevLinks(request()->path());
                    $nextLink = $links->next ? $links->next : null;
                    $prevLink = $links->previous ? $links->previous : null;

                    if(!$nextLink && $currentRole === 'jockey' && $pdp->status === 'In Progress') {
                        $nextLink = 'pdp.submit';
                    }

                    if(strpos(Request::url(), 'submit') !== false) {
                        $nextLink = null;
                        $prevLink = 'pdp.support-team';
                    }
                @endphp
                <a 
                    class="panel__call-to-action" 
                    href="{{ $prevLink ? route($prevLink, $pdp) : '#' }}"
                    @if(!$prevLink)
                        disabled
                    @endif
                >
                    @if($prevLink)
                        Previous
                    @endif
                </a>
                <a 
                    @if(!$nextLink)
                        disabled
                    @endif
                    class="panel__call-to-action" 
                    href="{{ $nextLink ? route($nextLink, $pdp) : '#' }}"
                >
                    @if($nextLink)
                        Next
                    @endif
                </a>
            </div>
        </div>
    </div>
</div>