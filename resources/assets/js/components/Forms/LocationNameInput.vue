<template>
	<div v-if="show">
		<label for="location_name">Location Name</label>
		<input type="text" name="location_name" v-model="value">
	</div>	
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