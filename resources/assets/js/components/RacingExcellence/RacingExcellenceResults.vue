<template>
	<div>
		{{ data.id }}

		<racing-division
			v-for="division in data.divisions"
			:key="division.id"
			:division="division"
		></racing-division>
	</div>
</template>

<script>
import RacingDivision from './RacingDivision';

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
			}
		},
		components: {
			RacingDivision
		},
		mounted() {
			console.log('test', this.data);
		}
	}
</script>