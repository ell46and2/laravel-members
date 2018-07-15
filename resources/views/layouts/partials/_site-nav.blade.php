<div class="site-nav js-site-nav">
    <div class="site-nav__inner">
        <div class="site-nav__header">
            <a class="site-nav__brand" href="{{ urlAppendByRole() . '/dashboard' }}">
                <img class="site-nav__brand-logo" src="http://jcp.local/images/jcp-logo.svg" alt="Jockey Coaching Programme">
            </a>
        </div>
        <ul class="site-nav__nav-items">
            <li class="site-nav__nav-item{{ isRoute('dashboard') ? ' is-active' : '' }}">
                <a class="site-nav__nav-link" href="{{ urlAppendByRole() . '/dashboard' }}">
                    <span class="site-nav__nav-icon">
                        @svg('nav-dashboard', 'icon')
                        {{-- {% include "svg/nav-dashboard.svg" %} --}}
                    </span>
                    Dashboard
                </a>
            </li>
            <li class="site-nav__nav-item">
                <a class="site-nav__nav-link" href="{{ route('messages.index') }}">
                    <span class="site-nav__nav-icon">
                        @svg('nav-messages', 'icon')
                        {{-- {% include "svg/nav-messages.svg" %} --}}
                    </span>
                    <span class="site-nav__nav-item-text">Messages</span>
                    @if($numUnreadMessages > 0)
                       <span class="[ site-nav__badge ] [ badge badge-pill badge-light ]">{{ $numUnreadMessages }} new</span> 
                    @endif                
                </a>
            </li>
            <li class="site-nav__nav-item{{ isRoute('log') ? ' is-active' : '' }}">
                <a class="site-nav__nav-link" href="{{ urlAppendByRole() . '/activity/log' }}">
                    <span class="site-nav__nav-icon">
                        @svg('nav-activity-log', 'icon')
                        {{-- {% include "svg/nav-activity-log.svg" %} --}}
                    </span>
                    <span class="site-nav__nav-item-text">Activity log</span>
                </a>
            </li>
            <li class="site-nav__nav-item">
                <a class="site-nav__nav-link" href="">
                    <span class="site-nav__nav-icon">
                        @svg('nav-my-coaches', 'icon')
                        {{-- {% include "svg/nav-my-coaches.svg" %} --}}
                    </span>
                    <span class="site-nav__nav-item-text">My Coaches</span>
                </a>
            </li>
            <li class="site-nav__nav-item">
                <a class="site-nav__nav-link" href="">
                    <span class="site-nav__nav-icon">
                        @svg('nav-jets-pdp', 'icon')
                        {{-- {% include "svg/nav-jets-pdp.svg" %} --}}
                    </span>
                    <span class="site-nav__nav-item-text">JETS - PDP</span>
                </a>
            </li>
            <li class="site-nav__nav-item">
                <a class="site-nav__nav-link" href="">
                    <span class="site-nav__nav-icon">
                        @svg('nav-racing-excellence', 'icon')
                        {{-- {% include "svg/nav-racing-excellence.svg" %} --}}
                    </span>
                    <span class="site-nav__nav-item-text">Racing Excellence</span>
                </a>
            </li>
            <li class="site-nav__nav-item">
                <a class="site-nav__nav-link" href="">
                    <span class="site-nav__nav-icon">
                        @svg('nav-skills-profile', 'icon')
                        {{-- {% include "svg/nav-skills-profile.svg" %} --}}
                    </span>
                    <span class="site-nav__nav-item-text">Skills Profile</span>
                </a>
            </li>
            <li class="site-nav__nav-item">
                <a class="site-nav__nav-link" href="">
                    <span class="site-nav__nav-icon">
                        @svg('nav-documents', 'icon')
                        {{-- {% include "svg/nav-documents.svg" %} --}}
                    </span>
                    <span class="site-nav__nav-item-text">Documents</span>
                </a>
            </li>
        </ul>
        <div class="site-nav__footer">
            <div class="site-nav__bha-logo">
                @svg('bha-logo-white', 'icon')
                {{-- {% include "svg/bha-logo-white.svg" %} --}}
            </div>
        </div>
    </div>
    <button class="[ site-nav__overlay-toggle ] [ js-nav-toggle ]">
        <span class="sr-only">Close the menu</span>
    </button>
</div>