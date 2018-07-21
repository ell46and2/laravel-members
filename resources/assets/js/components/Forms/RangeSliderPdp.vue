<template>
	<div>
		<vue-slide-bar
			v-model="value"
			:min="1"
			:max="7"

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
				value: this.oldValue ? this.oldValue : 4,
				interactedWith: false
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
		mounted() {
			if(this.oldValue) {
				this.value = this.oldValue;
			}
		},
		computed: {
			processStyle() {
				let color = 'blue';

				switch(true) {
					case (this.value <= 2):
						color = '#D0021B'; // red
						break;
					case (this.value <= 5):
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