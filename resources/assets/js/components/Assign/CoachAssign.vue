<template>
	<div class="[ panel__main ] [ flow-vertical--3 ]">
		<autocomplete
			:resource="resource"
			:exclude-ids="excludeIdsFromSearch"
			v-on:searched="assignCoach"
			placeholder="Search for Coach"
			v-if="canAssignCoaches"
		></autocomplete>
		
		<div class="five-col-grid">
			<user
				v-for="user in coaches"
				:user="user"
				:key="user.id"
				v-on:remove="unassignCoach"
				:can-remove-user="canAssignCoaches"
			></user>
		</div>
	</div>
</template>

<script>
	import Autocomplete from '../Search/Autocomplete';
	import User from './User';

	export default {
		data() {
			return {
				excludeIdsFromSearch: [],
				coaches: [],
				allCoaches: []
			}
		},
		props: {
			jockeyId: {
				required: true,
				type: Number
			},
			resource: {
				required: true,
				type: String
			},
			current: {
				required: true
			},
			canAssignCoaches: {
				required: true
			}
		},
		components: {
			Autocomplete,
			User
		},
		mounted() {
			this.allCoaches = JSON.parse(this.resource);

			this.addCurrentCoaches();
		},
		methods: { 
			async assignCoach(id) {
				console.log(id);
				let result = await axios.post(`/coach/${id}/assign-jockey`, {jockey_id: this.jockeyId});
				result = result.data.data;

				this.addCoach(id);

				this.excludeIdsFromSearch.push(id);
			},
			async unassignCoach(id) {
				let result = await axios.post(`/coach/${id}/unassign-jockey`, {jockey_id: this.jockeyId});
				result = result.data.data;

				this.coaches = this.coaches.filter(coach => coach.id !== id);

				this.excludeIdsFromSearch = this.excludeIdsFromSearch.filter(item => item !== id);
			},
			addCurrentCoaches() {
				this.excludeIdsFromSearch = this.current;

				this.current.forEach((coachId) => {
					this.addCoach(coachId);
				});
			},
			addCoach(id) {
				let coach =  _.find(this.allCoaches, { id: id });

				this.coaches.push(coach);
			}

		}
		
	}
</script>
