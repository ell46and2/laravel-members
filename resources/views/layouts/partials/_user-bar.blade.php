<div class="[ user-bar ] [ flow-horizontal--1 ]">
    <div class="[ user-bar__avatar ] [ avatar ]">
        <div class="avatar__image" style="background-image:url({{ auth()->user()->getAvatar() }});"></div>
    </div>
    <span class="user-bar__name">{{ auth()->user()->full_name }}</span>
    <span class="user-bar__sign-out">
    	Not you? Sign out 
    	<a 
    		class="link--underlined font-bold" 
    		href="{{ route('logout') }}"
    		onclick="event.preventDefault();
            document.getElementById('logout-form').submit();"
    	>here</a>.
    </span>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>