<template>
	<div class="[ notification-panel__notification ] [ notification ]">
		<div class="notification__icon">
			<icon-racing-excellence v-if="notification.type === 'racing-excellence'"></icon-racing-excellence>
		</div>
		
		<div class="notification__main">
            <div class="[ notification__message ] [ flow-vertical--1 ]">
                <p>{{ notification.body }}</p>
            </div>
            <div class="notification__footer">
                <div class="notification__datetime">
                    {{ notification.created_at }}
                </div>
                <div>
                    <button 
                    	type="button" 
                    	class="button button--primary"
                    	v-if="notification.linkUrl"
                    	@click.prevent="view"
                    >View</button>

                    <button 
                    	type="button" 
                    	class="button button--white"
                    	@click.prevent="dismiss"
                    >Dismiss</button>
                </div>
            </div>
        </div>		
	</div>

		<!-- <div class="card-body">
			<p>{{ notification.body }}</p>
			<p>{{ notification.created_at }}</p>
			<button 
				class="btn btn-primary" 
				v-if="notification.linkUrl"
				@click.prevent="view"
			>
				View
			</button>
			<a 
				href="#" 
				class="btn btn-link" 
				@click.prevent="dismiss"
			>Dismiss</a>
		</div>
	</div> -->
</template>

<script>
	import IconRacingExcellence from './IconRacingExcellence';
	
	export default {
		props: {
			notification: {
				required: true,
				type: Object
			}
		},
		components: {
			IconRacingExcellence
		},
		methods: {
			dismiss() {
				axios.put(`/notification/${this.notification.id}/dismiss`)
               	.then(() => {
               		// maybe add a class here to fade out the notification
                	this.$emit('dismissed', this.notification.id);
               	}, () => {
                   // handle failer.
               	}); 			
			},
			view() {
				axios.put(`/notification/${this.notification.id}/dismiss`)
               	.then(() => {
					window.location.href = this.notification.linkUrl;
				}, () => {
                   // handle failer.
               	}); 
			}
		}
	}
</script>