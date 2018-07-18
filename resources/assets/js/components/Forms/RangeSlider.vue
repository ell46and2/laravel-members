<template>
	<div>
		<vue-slide-bar
			v-model="value"
			:min="0"
			:max="3"
			:data="slider.data"
			:tooltipStyles="{ backgroundColor: '#333', borderColor: '#333' }"
			:processStyle="processStyle"
			:lineHeight="8"
			:is-disabled="false"
		>
		</vue-slide-bar>
	</div>
</template>

<script>
	import VueSlideBar from './VueSlider';

	export default {
		data() {
			return {
				value: 4,
				slider: {
					data: [
						0,
						0.5,
						1,
						1.5,
						2,
						2.5,
						3
					]
				}
			}
		},
		components: {
			VueSlideBar
		},
		computed: {
			processStyle() {
				let color = 'blue';

				switch(true) {
					case (this.value <= 2):
						color = 'red';
						break;
					case (this.value <= 5):
						color = 'orange';
						break;
					default:
						color = 'green';
						break;
				}

				return {
					backgroundColor: color
				}
			}
		}
	}
</script>