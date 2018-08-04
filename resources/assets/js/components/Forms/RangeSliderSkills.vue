<template>
	<div>
		<vue-slide-bar
			v-model="value"
			:min="0"
			:max="5"
			:data="slider.data"
			:processStyle="processStyle"
			:lineHeight="6"
			:is-disabled="isDisabled"
		>
		</vue-slide-bar>
		<input type="hidden" :name="name" :value="getValue">
	</div>
</template>

<script>
	import VueSlideBar from './VueSlider';

	export default {
		data() {
			return {
				value: this.oldValue ? this.oldValue : 0,
				slider: {
					data: [
						0,
						0.5,
						1,
						1.5,
						2,
						2.5,
						3,
						3.5,
						4,
						4.5,
						5
					]
				}
			}
		},
		props: {
			isDisabled: {
				required: false,
				default: false,
			},
			name: {
				required: true
			},
			oldValue: {
				required: false
			}
		},
		components: {
			VueSlideBar
		},
		computed: {
			processStyle() {
				let color = '#F57E23';

				switch(true) {
					case (this.value <= 1.5):
						color = '#D0021B'; // red
						break;
					case (this.value <= 3.5):
						color = '#F57E23'; // orange
						break;
					default:
						color = '#7ED321'; //green
						break;
				}

				return {
					backgroundColor: color
				}
			},
			getValue() {			
				return this.value;
			}
		}
	}
</script>