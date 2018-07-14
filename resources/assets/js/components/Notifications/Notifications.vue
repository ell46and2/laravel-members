<template>
	<div class="notification-panel__main">

        <div class="notification-panel__header">
            <h2 class="notification-panel__heading">Your notifications</h2>
            <div class="text-right" v-if="data.notifications.length">
                <button class="notification-panel__mark-as-read" type="button" @click.prevent="markAllAsRead">
                    Mark all as read
                </button>
            </div>
        </div>

        <div class="notification-panel__notifications">
			<notification
				v-for="notification in data.notifications"
				:key="notification.id"
				:notification="notification"
				v-on:dismissed="removeDismissed"
			></notification>
        </div>
	</div>
	
		<!-- <span class="badge badge-danger">{{ data.notifications.length }}</span>
		<a 
			href="#" 
			class="btn btn-link" 
			@click.prevent="markAllAsRead"
		>Mark All As Read</a> -->

		
	
</template>

<script>
	import Notification from './Notification';
	import bus from '../../bus';

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
				// emit to badges the new number of notifications
				bus.$emit('notifications:count', this.data.notifications.length);
			},
			markAllAsRead() {
				axios.post(`/notification/${this.data.user_id}/dismiss-all`)
                   .then(() => {
                       this.data.notifications = [];
                   }, () => {
                       // handle failer.
                   }); 
                bus.$emit('notifications:count', 0);	
			}
		}
	}
</script>