<template>
	<div>	
		<div>
			<h3>Assign Coach for Race</h3>
			<autocomplete
				:resource="resource"
				:exclude-ids="excludeIdsFromSearch"
				v-on:searched="assignCoach"
				placeholder="Search for Coach"
			></autocomplete>

			<form ref="form" :action="formAction" method="POST">
				<input type="hidden" name="_token" :value="csrf">
				<input type="hidden" name="coach_id" :value="coachId">
			</form>
		</div>
	</div>
</template>

<script>
	import Autocomplete from '../Search/Autocomplete';

	export default {
		data() {
			return {
				excludeIdsFromSearch: [],
				coachId: 0
			}
		},
		props: {
			resource: {
				required: true,
				type: String
			},
			racingExcellenceId: {
				required: true,
				type: Number
			}
		},
		components: {
			Autocomplete,
		},
		computed: {
			formAction() {
				return `/racing-excellence/${this.racingExcellenceId}/assign-coach`;
			},
			csrf() {
				return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
			}
		},
		methods: { 
			assignCoach(id) {
				this.coachId = id;

				setTimeout(() => {
					this.$refs.form.submit();
				}, 100);
				
				// submit the form
				
			}
		}
		
	}
</script>
