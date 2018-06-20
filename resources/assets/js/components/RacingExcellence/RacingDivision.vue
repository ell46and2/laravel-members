<template>
	<div>
		
		<racing-participant
			v-for="participant in division.participants"
			:participant="participant"
			:key="participant.id"
			:number-of-participants="division.participants.length"
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
			}
		},
		components: {
			RacingParticipant
		}
	}
</script>