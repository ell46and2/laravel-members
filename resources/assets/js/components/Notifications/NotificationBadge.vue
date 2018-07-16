<template>
	<span
		:class="classProp"
	>
		{{ numberOfNotifications }}
	</span>
</template>

<script>
	import bus from '../../bus';

	export default {
		data() {
			return {
				numNotifications: 0
			}
		},
		props: {
			classProp: {
				required: true,
				type: String
			},
			count: {
				required: true
			}
		},
		mounted() {
			this.numNotifications = this.count;
			bus.$on('notifications:count', (count) => {
				this.numNotifications = count;	
			});	
		},
		computed: {
			numberOfNotifications() {
				return this.numNotifications;
			}
		}
	}
</script>