<template>
	<div 
		style="display: none;"
		:class="classes">
        {{ message }}
    </div>
</template>

<script>
	
	export default {
		data() {
			return {
				message: null,
				show: false,
			}
		},
		props: {
			success: {
				required: false,
				default: null
			},
			error: {
				required: false,
				default: null
			}
		},
		mounted() {
			if(this.success) {
				this.message = this.success;
			}

			if(this.error) {
				this.message = this.error;
			}

			if(this.message) {
				this.show = true;

				setTimeout(() => {
					this.show = false;
				}, 3000)
			}
		},
		computed: {
			classes() {
				return `alert alert--${this.success ? 'success' : 'error'} alert--centered alert--pinned`;
			}
		}
	}
</script>