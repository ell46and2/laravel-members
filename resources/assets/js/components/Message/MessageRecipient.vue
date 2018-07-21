<template>
	<div class="media mt-4 mb-4" >
		<div @click.prevent="handleSelect">
			<input type="checkbox" :name="inputName" :id="inputName" v-model="selected">
			<label 
				:for="inputName"
				class="media-body" 
				
			>
				<img class="mr-3" :src="recipient.avatar" :alt="recipient.name">
				<p>{{ recipient.name }}</p>
				<p>{{ recipient.role }}</p>
			</label>
		</div>
		<div>
			<a href="#" @click.prevent="remove">remove</a>
		</div>
	</div>
</template>

<script>

	export default {
		data() {
			return {
				selected: true
			}
		},
		props: {
			recipient: {
				required: true,
				type: Object
			}
		},
		computed: {
			inputName() {
				return `recipients[${this.recipient.id}]`;
			}
		},
		methods: {
			handleSelect() {
				// to stop user from unchecking checkbox.
				return;
			},
			remove() {
				this.$emit('remove', this.recipient.id);
			}
		}
	}
</script>
