<template>
	<datepicker 
		:name="name" 
		:placeholder="placeholder"
		:format="format"
		:value="date"
	></datepicker>
</template>

<script>
	import Datepicker from 'vuejs-datepicker';

	export default {
		components: {
			Datepicker
		},
		data() {
			return {
				date: null
			}
		},
		props: {
			name: {
				required: true,
				type: String
			},
			placeholder: {
				default: 'Select Date',
				type: String
			},
			old: {
				required: false
			},
			format: {
				required: false,
				default: 'dd/MM/yyyy'
			}
		},
		mounted() {
			// if(this.currentDate) {
			// 	// new Date('1975-12-25')
			// 	this.date = new Date(this.currentDate);
			// }
			
			// console.log('old', window.oldFormValues.start_date);
			// console.log(new Date(window.oldFormValues.start_date));
			
			// if(window.oldFormValues[this.name]) {
			// 	let oldDate = window.oldFormValues[this.name];
			// 	this.date = new Date(oldDate.split('/').reverse().join('/'));
			// }
			
			if(this.old) {
				console.log('old', this.old);
				if(this.format === 'dd-MM-yyyy') {
					this.date = new Date(this.old.split('-').reverse().join('/'));
				} else {
					this.date = new Date(this.old.split('/').reverse().join('/'));
				}
				
			}
		}

	}
</script>