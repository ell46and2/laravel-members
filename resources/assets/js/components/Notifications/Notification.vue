<template>
	<div class="card">
		<div class="card-body">
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
	</div>
</template>

<script>
	
	export default {
		props: {
			notification: {
				required: true,
				type: Object
			}
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