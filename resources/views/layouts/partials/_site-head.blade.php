<header class="site-head" role="banner">
    <button class="[ site-head__burger ] [ js-nav-toggle js-nav-toggle--burger ]" type="button">
        <span class="sr-only">Open menu</span>
        <span class="site-head__burger-decor" aria-hidden="true" role="presentation">
            <span class="site-head__burger-bars"></span>
        </span>
    </button>
    <div class="site-head__brand">
        <a class="site-head__brand-link" href="">
            @svg('jcp-logo-small', 'icon')
        </a>
    </div>
    <button class="[ site-head__notifications-toggle ] [ js-notification-panel-toggle js-notification-panel-toggle--site-head ]" type="button">
        @svg('bell', 'icon')
        <notification-badge
            class-prop="site-head__notifications-count"
            count="{{ $notificationCount }}"
        ></notification-badge>
    </button>
</header>