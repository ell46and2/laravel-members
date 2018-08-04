@if($numUnreadMessages)
	<div class="alert alert--error">
		<div class="alert__text alert__text--has-icon">
		    @svg('info-circle', 'icon')
		    <span class="alert__text-inner">
		        You have <span class="badge badge-pill badge-light">{{ $numUnreadMessages }} unread</span> {{ str_plural('message', $numUnreadMessages) }}
		    </span>
		</div>
		<div class="alert__buttons">
		    <a class="button button--white" href="{{ route('messages.index') }}">View Messages</a>
		</div>
	</div>
@endif