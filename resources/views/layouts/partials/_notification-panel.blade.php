<div class="[ notification-panel ] [ js-notification-panel ]">
    <button class="[ notification-panel__toggle ] [ js-notification-panel-toggle ]">
        <div class="notification-panel__status-icon">
            @svg('bell', 'icon')
            {{-- {% include "svg/bell.svg" %} --}}
            {{-- <span class="notification-panel__status-icon-count">3</span> --}}
            <notification-badge
                class-prop="notification-panel__status-icon-count"
                count="{{ $notificationCount }}"
            ></notification-badge>
        </div>

        <span class="notification-panel__toggle-handle">
            <span class="sr-only">Click to view your notifications</span>
        </span>
    </button>

    <notifications
        resource="{{ json_encode($notificationsResource) }}"
    ></notifications>

    <button class="[ notification-panel__close-button ] [ js-notification-panel-toggle ]">
        <span class="sr-only">Close the notifications panel</span>
        @svg('times', 'icon')
        {{-- {% include "svg/times.svg" %} --}}
    </button>

    <button class="[ notification-panel__overlay-toggle ] [ js-notification-panel-toggle ]" aria-hidden="true" role="presentation">
        @svg('times', 'icon')
        {{-- {% include "svg/times.svg" %} --}}
    </button>
</div>
