<template>
	<div class="form-group form-group--has-icon">
		<datepicker 
		:name="name" 
		:placeholder="placeholder"
		:format="format"
		:value="date"
		:input-class="inputClasses"
		></datepicker>
		<span class="form-group__input-icon" aria-hidden="true" role="presentation">
            <!-- {% include "svg/calendar.svg" %} -->
            <icon-calendar></icon-calendar>
        </span>
	</div>
	
</template>

<script>
	import Datepicker from 'vuejs-datepicker';
	import IconCalendar from './IconCalendar';

	export default {
		components: {
			Datepicker,
			IconCalendar
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
			},
			overwriteClasses: {
				required: false,
				type: String
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
		},
		computed: {
			inputClasses() {
				if(this.overwriteClasses) return this.overwriteClasses;

				return 'form-control form-control--has-icon mt-1';
			}
		}

	}
</script>

<style>
.vdp-datepicker__calendar header {
	color: #00304E;
}
.vdp-datepicker__calendar header .prev:after
{
	border-right: 10px solid #00304E;
}
.vdp-datepicker__calendar header .next:after {
	border-left: 10px solid #00304E;
}
.day__month_btn,
.month__year_btn {
	color: #00304E;
	font-family: "DIN", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
}
.day-header {
	color: #1E70B7;
}
.cell.selected {
	color: white !important;
	background: #1E70B7 !important;
	border-radius: 4px;
}
.vdp-datepicker__calendar .cell:not(.blank):not(.disabled).day:hover, 
.vdp-datepicker__calendar .cell:not(.blank):not(.disabled).month:hover, 
.vdp-datepicker__calendar .cell:not(.blank):not(.disabled).year:hover {
	border: 1px solid #1E70B7;
	border-radius: 4px;
	background: #1E70B7 !important;
	color: white;
}
.cell.day, .cell.month, .cell.year {
	color: #6f6f6f;
}
</style>