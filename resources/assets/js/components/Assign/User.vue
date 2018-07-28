<template>
	<div class="media mt-4 mb-4" >
		<div>
			<img class="mr-3" :src="user.avatar" :alt="user.name">
			<p>{{ user.name }}</p>
		</div>
		<div>
			<a href="#" @click.prevent="remove">remove</a>
		</div>
	</div>
</template>

<script>
	
	export default {
		props: {
			user: {
				required: true,
				type: Object
			}
		},
		methods: {
			remove() {
				this.$emit('remove', this.user.id);
			}
		}
	}
</script>