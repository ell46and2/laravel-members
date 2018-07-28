<template>
	<div>
		<input type="text" v-model="name" placeholder="Type Here to Add An External Jockey's name">
		<button 
    		class="btn btn-primary" 
    		:disabled="disableBtn"
    		@click.prevent="add"
    	>+</button>
	</div>
	
</template>

<script>
	
	export default {
		data() {
			return {
				name: ''
			}
		},
		methods: {
			add() {
				this.name = this.name.trim();
				if(this.name) {
					this.$emit('externalAdd', this.name);

					this.name = '';
				}
			}
		},
		computed: {
			disableBtn() {
				return this.name.trim() === '';
			}
		}
	}
</script>