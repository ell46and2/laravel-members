<template>
	<div>
		<p>{{ label }}</p>

		<div class="btn-group btn-group-toggle" data-toggle="buttons">
			<br><br>
	  		<label class="btn btn-secondary" :class="value === 0 ? 'active' : ''" @click="onClick(0)">
	    		<input 
	    			type="radio" 
	    			autocomplete="off" 
	    			> 0
	  		</label>
	  		<label class="btn btn-secondary" :class="value === 1 ? 'active' : ''" @click="onClick(1)">
	    		<input 
	    			type="radio" 
	    			autocomplete="off" 
	    		> 1
	  		</label>
	  		<label class="btn btn-secondary" :class="value === 2 ? 'active' : ''" @click="onClick(2)">
	    		<input 
	    			type="radio" 
	    			autocomplete="off"
	    		> 2
	  		</label>
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
