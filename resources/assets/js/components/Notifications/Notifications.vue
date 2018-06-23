<template>
	<div>
		<span class="badge badge-danger">{{ data.notifications.length }}</span>
		<a 
			href="#" 
			class="btn btn-link" 
			@click.prevent="markAllAsRead"
		>Mark All As Read</a>

		<notification
			v-for="notification in data.notifications"
			:key="notification.id"
			:notification="notification"
			v-on:dismissed="removeDismissed"
		></notification>
	</div>
</template>

<script>
	import Notification from './Notification';	

	export default {
		data() {
			return {
				data: {
					notifications: [],
					from: null,
					user_id: null,
				}			
			}
		},
		props: {
			resource: {
				required: true,
				type: String
			}
		},
		components: {
			Notification
		},
		mounted() {
			this.data = JSON.parse(this.resource);
			// this.notifications = data.notifications;
			// this.from = data.from;
		},
		methods: {
			removeDismissed(id) {
				this.data.notifications = this.data.notifications.filter((notification) => notification.id !== id);
			},
			markAllAsRead() {
				axios.post(`/notification/${this.data.user_id}/dismiss-all`)
                   .then(() => {
                       this.data.notifications = [];
                   }, () => {
                       // handle failer.
                   }); 	
			}
		}
	}
</script>