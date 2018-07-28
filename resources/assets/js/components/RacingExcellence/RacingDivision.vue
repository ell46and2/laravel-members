<template>
	<div>
		
		<racing-participant
			v-for="participant in participants"
			:participant="participant"
			:key="participant.id"
			:number-of-participants="division.participants.length"
			:place-only-required="placeOnlyRequired"
		></racing-participant>

	</div>
</template>

<script>
import RacingParticipant from './RacingParticipant';

	export default {
		props: {
			division: {
				required: true,
				type: Object
			},
			placeOnlyRequired: {
				required: true,
				type: Boolean
			}
		},
		components: {
			RacingParticipant
		},
		computed: {
			participants() {
				return _.orderBy(this.division.participants, ['place'], ['asc']);
			}
		}
	}
</script>