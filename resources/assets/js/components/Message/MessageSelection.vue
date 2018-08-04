<template>
	<div>
		<template v-if="jockeyResource">
			<p>Search For Jockey</p>
			<autocomplete
				:resource="jockeyResource"
				:exclude-ids="excludeIds"
				v-on:searched="addUser"
				placeholder="Search for Jockey..."
				btn-name="Add"
			></autocomplete>
		</template>

		<template v-if="coachResource">
			<p>Search For Coach</p>
			<autocomplete
				:resource="coachResource"
				:exclude-ids="excludeIds"
				v-on:searched="addUser"
				placeholder="Search for Coach..."
				btn-name="Add"
			></autocomplete>
		</template>

		<template v-if="jetsResource">
			<p>Search For JETS</p>
			<autocomplete
				:resource="jetsResource"
				:exclude-ids="excludeIds"
				v-on:searched="addUser"
				placeholder="Search for JETS..."
				btn-name="Add"
			></autocomplete>
		</template>

		<!-- 	<button
				@click.prevent="handleSendToAll"
				class="btn btn-primary"
			>
				{{ sendToAllBtn }}
			</button> -->

			<!-- <message-recipient
				v-for="user in users"
				:key="user.id"
				:recipient="user"
				v-on:remove="removeUser"
			></message-recipient> -->
		

		<!-- <template v-else>
			<p>{{ sendToAllCopy }}</p>
			<button class="btn btn-danger" @click.prevent="cancelSendAll">Cancel</button>
		</template> -->
	</div>
	
</template>

<script>
	import Autocomplete from '../Search/Autocomplete';
	import pluralize from 'pluralize';
	
	export default {
		data() {
			return {
				users: [],
				sendToAll: false
			}
		},
		props: {
			jockeyResource: {
				required: true
			},
			coachResource: {
				required: true
			},
			jetsResource: {
				required: true
			},
			excludeIds: {
				required: false
			}
		},
		components: {
			Autocomplete
		},
		computed: {
			// placeholder() {
			// 	return `Search for ${this.role}s`;
			// },
			// sendToAllBtn() {
			// 	return `Send to all ${this.role}s`;
			// },
			// sendToAllCopy() {
			// 	// NOTE: add pluralize so we get coaches, instead of coachs
			// 	return `Sending to ${this.resource.length} ${pluralize(this.role, this.resource.length)}`;
			// }
		},
		methods: {
			pluralize, // adds the pluralize filter method from the import.
			addUser(id) {
				// need route to get any user - messages/user/${id}
				// let result = await axios.post(`/messages/user/${id}`);

				// let user = result.data.data;

				// this.users.push(user);

				// add jockey to participants array
				// add jockeys id to the excludeIdsFromSearch array
				// this.excludeIdsFromSearch.push(jockey.id);
				
				// emit to parent.
				this.$emit(`addUser`, id);
			},
			// removeUser(id) {
			// 	// this.users = this.users.filter(recipient => recipient.id !== id);

			// 	this.$emit('removeUser', id);
			// },
			// handleSendToAll() {
			// 	this.sendToAll = true;
			// 	this.users = [];
			// 	this.$emit('sendToAll');
			// },
			// cancelSendAll() {
			// 	this.sendToAll = false;
			// 	this.$emit('sendToAllCancelled');
			// }
		}
	}
</script>