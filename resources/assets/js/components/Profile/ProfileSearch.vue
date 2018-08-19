<template>
	<div class="[ panel__main ] [ flow-vertical--3 ]">
		<div class="form-group">
            <label class="form__label">Search All Jockey</label>   
			<autocomplete
				:resource="resource"
				v-on:searched="selectJockey"
				placeholder="Search for Jockey"
			></autocomplete>
		</div>
	</div>
</template>

<script>
	import Autocomplete from '../Search/Autocomplete';

	export default {
		props: {
			resource: {
				required: true,
				type: String
			},
			userType: {
				required: true,
				type: String
			}
		},
		components: {
			Autocomplete
		},
		methods: { 
			selectJockey(id) {
				window.location.href = `/${this.userType}/${id}`;
			}
		}
		
	}
</script>
