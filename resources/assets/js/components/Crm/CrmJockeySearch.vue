<template>
		<div class="form-group pb-3 pt-2">
            <label class="form__label">Search for Jockey</label>   
			<autocomplete
				:resource="resource"
				v-on:searched="selectJockey"
				placeholder="Search for Jockey"
			></autocomplete>
		</div>
</template>

<script>
	import Autocomplete from '../Search/Autocomplete';

	export default {
		data() {
			return {
				jockeys: null
			}
		},
		props: {
			resource: {
				required: true,
				type: String
			}
		},
		components: {
			Autocomplete
		},
		mounted() {
			this.jockeys = JSON.parse(this.resource);
		},
		methods: { 
			selectJockey(id) {
				let jockey = _.find(this.jockeys, { id: id });

				console.log(jockey);
				if(jockey.type === 'jockey') {
					window.location.href = `/jets/crm/jockey/${id}`;
					return;
				} else {
					window.location.href = `/jets/crm/crm-jockey/${id}`;
					return;
				}
			}
		}
		
	}
</script>
