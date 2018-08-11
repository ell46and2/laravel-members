<template>
	<div class="col-3">
		<p>{{ label }}</p>

		<div class="radio-switches">
			<br><br>
			<input class="[ radio-switches__input ] [ sr-only ]" :checked="value === 0" type="radio" :name="name" id="zero">
			<label class="radio-switches__label" @click="onClick(0)">Zero</label>

			<input class="[ radio-switches__input ] [ sr-only ]" :checked="value === 1" type="radio" :name="name" id="one">
			<label class="radio-switches__label" @click="onClick(1)">One</label>

			<input class="[ radio-switches__input ] [ sr-only ]" :checked="value === 2" type="radio" :name="name" id="one">
			<label class="radio-switches__label" @click="onClick(2)">Two</label>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				value: this.currentValue
			}
		},
		props: {
			currentValue: {
				required: true
			},
			field: {
				required: true
			},
			label: {
				required: true
			},
			participantId: {
				required: true
			}
		},
		computed: {
			name() {
				return `${this.field}-${this.participantId}`;
			}
		},
		methods: {
			onClick(value) {
				// set value to null if already selected.
				if(this.value === value) {
					this.value = null;
					this.$emit('valueChange', null, this.field);
				} else {
					this.value = value;
					this.$emit('valueChange', value, this.field);
				}		
			}
		}
	}
</script>
