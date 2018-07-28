<template>
	<div>
		<template v-for="division in data.divisions">
			<!-- NOTE: need to add tabs -->
			<results-table				
				:key="resultsTableKey(division.id)"
				:division="division"
			></results-table>

			<racing-division
				v-if="coachId"
				:key="division.id"
				:division="division"
				:place-only-required="data.totalJustFromPlace"
			></racing-division>
		</template>
	</div>
</template>

<script>
	import RacingDivision from './RacingDivision';
	import ResultsTable from './ResultsTable';
	import bus from '../../bus';

	export default {
		data() {
			return {
				data: JSON.parse(this.resource)
			}
		},
		props: {
			resource: {
				required: true,
				type: String
			},
			coachId: {
				required: false,
				default: null
			}
		},
		components: {
			RacingDivision,
			ResultsTable
		},
		mounted() {
			bus.$on('participant:updated', this.updateParticipant);
		},
		methods: {
			updateParticipant(participant) {
				console.log('participant', participant);
				// find participant
				// update data
				
				// find division
				let participantsDivision = _.find(this.data.divisions, { id: participant.division_id });
				
				_.assign(_.find(participantsDivision.participants, { id: participant.id }), participant);
			},
			resultsTableKey(id) {
				return `results_${id}`;
			}
		}
	}
</script>