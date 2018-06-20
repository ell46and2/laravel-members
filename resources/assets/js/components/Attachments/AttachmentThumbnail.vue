<template>
	<img 
		class="mr-3" 
		:src="attachment.thumbnail" 
		alt="comment attachment"
		@click="onClick"
	>
</template>

<script>
	export default {
		props: {
			attachment: {
				required: true,
				type: Object
			}
		},
		mounted() {
			console.log('thumbnail mounted');
		},
		methods: {
			onClick() {
				// At desktop
				// probably have an attachment modal component and pass it the attachment
				// use bus to pass the attachment and have the attachmentModal on the comments component.
				// 
				// At tablet and lower
				// open attachment images in new tab
				
				alert('This will open a modal or new tab if at tablet or lower.'); 
			}
		}
	}
</script>