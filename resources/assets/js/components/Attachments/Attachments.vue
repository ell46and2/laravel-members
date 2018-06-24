<template>
	<button class="btn btn-primary" @click="onClick">Open Modal</button>
	<!-- will contain the attachment thumbs -->
</template>

<script>
	import bus from '../../bus';

	export default {
		methods: {
			onClick() {
				bus.$emit('modal:open', '15b2ecfae64fce'); // with the uid
			}
		}
	}
</script>
