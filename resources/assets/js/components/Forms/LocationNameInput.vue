<template>
	<input
		v-if="show"
		class="form-control"
		type="text" 
		name="location_name" 
		v-model="value" 
		placeholder="Enter Location Name..."
	>
</template>

<script>
	import bus from '../../bus';

	export default {
		data() {
			return {
				show: false,
				value: ''
			}
		},
		props: {
			oldLocationName: {
                required: false,
                default: null
            }
		},
		mounted() {
			bus.$on('locationName:show', () => {this.show = true});
			bus.$on('locationName:hide', () => {this.show = false});

			if(this.oldLocationName) {
				this.value = this.oldLocationName;
			}
		}
	}
</script>