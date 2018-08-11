<template>
	<div class="timepicker">
		
		<div class="form-group form-group--has-icon mt-1" style="position: relative;">
			<input 
				class="form-control form-control--has-icon"
				type="text" 
				:name="name" 
				:placeholder="placeholder" 
				autocomplete="false"
				v-model="time"
				@click="toggleDropdown"
				readonly
			>
			<span class="form-group__input-icon" aria-hidden="true" role="presentation">
                <!-- {% include "svg/time.svg" %} -->
                <icon-time></icon-time>
            </span>

            <div class="timepicker__overlay" v-if="showDropdown" @click="toggleDropdown"></div>
            
            <div class="timepicker__dropdown" v-show="showDropdown">
            	<div class="timepicker__select-list">
            		<ul class="hours">
            			<li class="hint">Hour</li>
            			<li 
            				v-for="hr in hours"
            				:class="{active: hour === hr}" 
            				@click="select('hour', hr)"
            			>
            				{{ hr }}
            			</li>
            		</ul>
            		<ul class="minutes">
            			<li class="hint">Minute</li>
            			<li 
            				v-for="mm in minutes"
            				:class="{active: minute === mm}" 
            				@click="select('minute', mm)"
            			>
            				{{ mm }}
            			</li>
            		</ul>
            	</div>
            </div>
        </div>

		
	</div>
</template>

<script>
	import IconTime from './IconTime';

	export default {
		data() {
			return {
				hours: [],
				minutes: [],
				hour: '',
        		minute: '',
        		time: null,
        		showDropdown: false,
			}
		},
		components: {
			IconTime
		},
		props: {
			name: {
				required: false,
				default: 'start_time'
			},
			placeholder: {
				required: false,
				default: 'Select Start Time'
			},
			old: {
				required: false
			}
		},
		mounted() {
			this.renderHours();
			this.renderMinutes();

			if(this.old) {
				this.setOldTime();
			}
		},
		methods: {
			renderHours() {
				for (let i = 0; i < 24; i++) {
					this.hours.push(this.toDoubleDigits(i));
				}
			},
			renderMinutes() {
				for (let i = 0; i < 60; i++) {
					this.minutes.push(this.toDoubleDigits(i));
				}
			},
			toDoubleDigits(i) {
				return (i) < 10 ? '0' + (i) : String(i);
			},
			toggleDropdown() {
		        this.showDropdown = !this.showDropdown;
		    },
			select(type, value) {
				if (type === 'hour') {
		          this.hour = value;
		        } else {
		          this.minute = value;
		        }
		        this.displayTime();
			},
			displayTime() {
				let formatTime = 'HH:mm';
				if(this.hour) {
					formatTime = formatTime.replace(new RegExp('HH', 'g'), this.hour);
				}
				if(this.minute) {
					formatTime = formatTime.replace(new RegExp('mm', 'g'), this.minute);
				}

				this.time = formatTime;
			},
			setOldTime() {
				let splitTime = this.old.split(':');
				this.hour = splitTime[0];
				this.minute = splitTime[1];

				this.displayTime();
			}
		},
		
	}
</script>

<style scoped>

</style>