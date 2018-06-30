<template>
	<div class="division">
		<h2>Division {{ divisionNumText }} - Choose Jockeys</h2>
		<span>Select which ...</span>

		<autocomplete
			:resource="jockeysResource"
			:exclude-ids="excludeIdsFromSearch"
			v-on:searched="addJockey"
			placeholder="Search for Jockeys"
		></autocomplete>

		<br>

		<add-external-participant
			v-on:externalAdd="addExternal"
		></add-external-participant>
	
		<!-- checkboxes -->
		<div>
			<division-participant
				v-for="(participant, index) in participants"
				:key="index"
				:participant="participant"
				:division-num="divisionNum"
				v-on:remove="removeParticipant"
			></division-participant>
		</div>
	</div>
</template>

<script>
	import Autocomplete from '../Search/Autocomplete';
	import DivisionParticipant from './DivisionParticipant';
	import AddExternalParticipant from './AddExternalParticipant';
	
	export default {
		data() {
			return {
				excludeIdsFromSearch: [],
				participants: [] 
			}
		},
		props: {
			divisionNum: {
				required: true
			},
			jockeysResource: {
				required: true
			},
			excludeIds: {
				required: false,
				default() {
					return [];
				}
			},
			externalAvatarUrl: {
				required: true,
				type: String
			},
			old: {
				required: false
			},
			currentParticipants: {
				required: false
			},
			currentId: {
				required: false
			},
			isEdit: {
				required: true,
				type: Boolean
			}
		},
		components: {
			Autocomplete,
			DivisionParticipant,
			AddExternalParticipant
		},
		mounted() {
			this.excludeIdsFromSearch = this.excludeIds;

			// add current participants from props to participants array.
			
			
			this.checkForCurrentParticipant();

			
			if(this.old) {	
				if(this.old.jockeys) {			
					this.addOldJockeys();
				}
				if(this.old.external_participants) {
					this.addOldExternal();
				}
			}
		},
		watch: {
			old(newVal, oldVal) {
				if(this.old.jockeys) {
					this.addOldJockeys();
				}

				if(this.old.external_participants) {
					this.addOldExternal();
				}
			},
			currentParticipants(newVal, oldVal) {
				this.checkForCurrentParticipant();
			}
		},
		computed: {
			divisionNumText() {
				return this.divisionNum == 1 ? 'One' : 'Two';
			}
		},
		methods: {
			async addJockey(id) {
				
				if(this.isEdit) {
					// axios post to with jockey id to create participant
					let result = await axios.post(`/racing-excellence/${this.currentId}/participant/create`, {id: id});
					let participant = result.data.data;

					this.participants.push(participant);

					this.excludeIdsFromSearch.push(participant.jockey_id);

				} else {
					let result = await axios.get(`/racing-excellence/jockey/${id}`)

					let jockey = result.data.data;

					this.participants.push(jockey);

					// add jockey to participants array
					// add jockeys id to the excludeIdsFromSearch array
					this.excludeIdsFromSearch.push(jockey.id);
				}
				
			},
			removeParticipant(id) {
				if(this.isEdit) {
					this.destroyParticipant(id);

				} else {
					this.participants = this.participants.filter(participant => participant.id !== id);

					this.excludeIdsFromSearch = this.excludeIdsFromSearch.filter(item => item !== id);
				}
				
			},
			addExternal(name) {

				if(this.isEdit) {
					this.createExternalParticipant(name);

				} else {
					let participant = {
						id: name,
						name: name,
						avatar: this.externalAvatarUrl
					}

					this.participants.push(participant);
				}
			},
			async createExternalParticipant(name) {
				let result = await axios.post(`/racing-excellence/${this.currentId}/participant/external-create`, {name: name});
				let participant = result.data.data;

				this.participants.push(participant);
			},
			destroyParticipant(id) {
				if(confirm('Are you sure you want to remove this jockey from the division?')) {
                	axios.delete(`/racing-excellence/participant/${id}`)
                  	.then(() => {
                  		let participant = _.find(this.participants, { id: id });

                  		if(participant.jockey_id) {
                  			this.excludeIdsFromSearch = this.excludeIdsFromSearch.filter(item => item !== participant.jockey_id);
                  		}

	                    this.participants = this.participants.filter(participant => participant.id !== id);

	                },() => {
	                    // handle failer
	                });
              }
			},
			addOldJockeys() {
				Object.keys(this.old.jockeys).forEach((id) => {
					console.log('id', id);
					this.addJockey(id);
				});
			},
			addOldExternal() {
				Object.keys(this.old.external_participants).forEach((name) => {
					console.log('name', name);
					this.addExternal(name);
				});
			},
			checkForCurrentParticipant() {
				if(this.currentParticipants) {
					this.currentParticipants.forEach((participant) => {
						this.participants.push(participant);

						if(participant.jockey_id) {
							this.excludeIdsFromSearch.push(participant.jockey_id);
						}
					});

					
				}
			}
		}
		/*
		participant {
			jockey_id,
			name,
			avatar,
			participant_id

		}
		*/
	}
</script>