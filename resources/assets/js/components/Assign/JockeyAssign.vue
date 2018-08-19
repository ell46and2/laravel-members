<template>
	<div class="[ panel__main ] [ flow-vertical--3 ]">
		<autocomplete
			:resource="resource"
			:exclude-ids="excludeIdsFromSearch"
			v-on:searched="assignJockey"
			placeholder="Search for Jockey"
			v-if="canAssignJockeys"
		></autocomplete>
		
		<div class="five-col-grid">
			<user
				v-for="user in jockeys"
				:user="user"
				:key="user.id"
				v-on:remove="unassignJockey"
				:can-remove-user="canAssignJockeys"
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
				jockeys: [],
				allJockeys: []
			}
		},
		props: {
			coachId: {
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
			canAssignJockeys: {
				required: true
			}
		},
		components: {
			Autocomplete,
			User
		},
		mounted() {
			this.allJockeys = JSON.parse(this.resource);

			this.addCurrentJockeys();
		},
		methods: { 
			async assignJockey(id) {
				console.log(id);
				let result = await axios.post(`/coach/${this.coachId}/assign-jockey`, {jockey_id: id});
				result = result.data.data;

				this.addJockey(id);

				this.excludeIdsFromSearch.push(id);
			},
			async unassignJockey(id) {
				let result = await axios.post(`/coach/${this.coachId}/unassign-jockey`, {jockey_id: id});
				result = result.data.data;

				this.jockeys = this.jockeys.filter(jockey => jockey.id !== id);

				this.excludeIdsFromSearch = this.excludeIdsFromSearch.filter(item => item !== id);
			},
			addCurrentJockeys() {
				this.excludeIdsFromSearch = this.current;

				this.current.forEach((jockeyId) => {
					this.addJockey(jockeyId);
				});
			},
			addJockey(id) {
				let jockey =  _.find(this.allJockeys, { id: id });

				this.jockeys.push(jockey);
			}

		}
		
	}
</script>
