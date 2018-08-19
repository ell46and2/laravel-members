<template>
	<select 
		v-model="selected"
		class="form-control custom-select" 
		name="county_id" 
		id="county_id" 
		required
	>
		<option value="" disabled>Select County</option>
        <option v-for="county in counties" :value="county.id">
            {{ displayName(county) }}
        </option>
    </select>
</template>


<script>
	import bus from '../../bus';
	
	export default {
		data() {
			return {
				allCounties: [],
				counties: [],
				selected: '',
			}
		},
		props: {
			resource: {
				required: true
			},
			countryId: {
				required: false
			},
			old: {
				required: false
			}
		},
		mounted() {
			this.allCounties = JSON.parse(this.resource);

			if(this.countryId) {
				this.showCountiesForCountry(this.countryId);
			}

			if(this.old) {
				console.log('test', this.old);
				this.selected = this.old;
				console.log(this.selected);
			}

			bus.$on('country:selected', this.handleCountrySelected);
		},
		methods: {
            displayName(county) {
                return county.name.charAt(0).toUpperCase() + county.name.slice(1);
            },
            handleCountrySelected(id) {
            	if(this.countryId && this.old) {
            		if(this.countryId != id) {
            			this.selected = '';
            		}
            	} else {
            		this.selected = '';
            	}
            	
            	this.showCountiesForCountry(id);
            },
            showCountiesForCountry(id) {
            	this.counties = this.allCounties.filter(county => {
            		return county.country_id == id;
            	});
            }

        }
	}
</script>