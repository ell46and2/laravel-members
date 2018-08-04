<footer class="site-foot" role="contentinfo">
    <div class="site-foot__legal">
        &copy; {{ now()->year }} The British Horseracing Authority

        <ul class="site-foot__legal-links">
            <li class="site-foot__legal-link-item">
                <a class="site-foot__legal-link" href="">Privacy policy</a>
            </li>
        </ul>
    </div>
    <ul class="site-foot__icons">
        <li class="site-foot__icon">
            <a class="site-foot__icon-link" href="">
                <span class="sr-only">JETS</span>
                <img class="site-foot__icon-image" src="{{ asset('images/jets-logo.png') }}" aria-hidden="true" role="presentation">
            </a>
        </li>
        <li class="site-foot__icon">
            <a class="site-foot__icon-link" href="">
                <span class="sr-only">Professional Jockeys Association</span>
                <img class="site-foot__icon-image" src="{{ asset('images/pja-logo.png') }}" aria-hidden="true" role="presentation">
            </a>
        </li>
        <li class="site-foot__icon">
            <a class="site-foot__icon-link" href="">
                <span class="sr-only">The British Racing School</span>
                <img class="site-foot__icon-image" src="{{ asset('images/brs-logo.png') }}" aria-hidden="true" role="presentation">
            </a>
        </li>
        <li class="site-foot__icon">
            <a class="site-foot__icon-link" href="">
                <span class="sr-only">BHA</span>
                <img class="site-foot__icon-image" src="{{ asset('images/bha-logo.png') }}" aria-hidden="true" role="presentation">
            </a>
        </li>
    </ul>
</footer>