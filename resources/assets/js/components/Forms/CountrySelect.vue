<template>
	<select 
		v-model="selected"
		class="form-control custom-select" 
		name="country_id" 
		id="country_id" 
		required
	>
		<option value="" disabled>Select Country</option>
        <option v-for="country in countries" :value="country.id">
            {{ displayName(country) }}
        </option>
    </select>
</template>


<script>
	import bus from '../../bus';
	
	export default {
		data() {
			return {
				countries: [],
				selected: '',
			}
		},
		props: {
			resource: {
				required: true
			},
			old: {
				required: false
			}
		},
		mounted() {
			this.countries = JSON.parse(this.resource);

			if(this.old) {
				this.selected = this.old;
			}
		},
		methods: {
            displayName(country) {
                return country.name.charAt(0).toUpperCase() + country.name.slice(1);
            }
        },
        watch: {
            selected: function(id) {
                if(id !== '') {
                    bus.$emit('country:selected', id);
                }
            }
        }
	}
</script>