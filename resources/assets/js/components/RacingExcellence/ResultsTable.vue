<template>
	<div>
		<table class="table" v-if="orderedDivision">
		  	<thead>
		    	<tr>
		    		<th scope="col">Name</th>
		      		<th scope="col">Place</th>
		      		<th scope="col">Place Points</th>
		      		<th scope="col">Presentation</th>
		      		<th scope="col">Professionalism</th>
		      		<th scope="col">Course Walk</th>
		      		<th scope="col">Riding</th>
		      		<th scope="col">Total</th>
		    	</tr>
		  	</thead>
		  	<tbody>
		    	<tr v-for="participant in orderedDivision.participants" :key="participant.id">
		      		<td>{{ participant.name }}</td>
		      		<td>{{ formattedPlace(participant) }}</td>
		      		<td>{{ participant.place_points }}</td>
		      		<td>{{ participant.presentation_points }}</td>
		      		<td>{{ participant.professionalism_points }}</td>
		      		<td>{{ participant.coursewalk_points }}</td>
		      		<td>{{ participant.riding_points }}</td>
		      		<td>{{ participant.total_points }}</td>
		    	</tr>
		  </tbody>
		</table>
	</div>
</template>

<script>
	export default {
		props: {
			division: {
				required: true,
				type: Object
			}
		},
		methods: {
			order() {
				console.log('order ran');
				let division = Object.assign({}, this.division);
				division.participants = _.orderBy(division.participants, ['place'], ['asc']);

				return division;
			},
			formattedPlace(participant) {
				if(participant.place) {
					return participant.place
				}

				if(participant.total_points) {
					return 'dnf';
				}
			}
		},
		computed: {
			orderedDivision() {
				return this.order()
			}
		}
	}
</script>