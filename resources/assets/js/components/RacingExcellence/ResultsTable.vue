<template>
	<div>
		<table class="table table-hover" v-if="orderedDivision">
		  	<thead>
		    	<tr>
		    		<th></th>
                    <th>Jockey</th>
                    <th>Place points</th>
                    <th>Presentation points</th>
                    <th>Professionalism points</th>
                    <th>Coursewalk points</th>
                    <th>Riding points</th>
                    <th>Total points</th>
		    	</tr>
		  	</thead>
		  	<tbody>
		    	<tr v-for="participant in orderedDivision.participants" :key="participant.id">
		    		<td class="table__result-column">
			    		<div class="table__result-column-inner">
			    		    <span class="table__result-position">
			    		        {{ formattedPlace(participant) }}
			    		        <span class="table__result-position-suffix" v-if="participant.place">
			    		            {{ suffixByPlace(participant.place) }}
			    		        </span>
			    		    </span>

		    		        <span
		    		        	v-if="participant.place && participant.place <= 4" 
		    		        	:class="tableIconClass(participant.place)">
		    		            <icon-rosette></icon-rosette>
		    		        </span>
			    		</div>
			    	</td>
		      		<td>{{ participant.name }}</td>
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
import IconRosette from './IconRosette';

	export default {
		props: {
			division: {
				required: true,
				type: Object
			}
		},
		components: {
			IconRosette
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
			},
			suffixByPlace(place) {
				if(place === 1) return 'st';
		        if(place === 2) return 'nd';
		        if(place === 3) return 'rd';

		        return 'th';
			},
			tableIconClass(place) {
				let iconClass = 'table__icon';

				if(place === 1) return iconClass + ' table__icon--gold';
				if(place === 2) return iconClass + ' table__icon--silver';
				if(place === 3) return iconClass + ' table__icon--bronze';

				return iconClass;
			}
		},
		computed: {
			orderedDivision() {
				return this.order()
			}
		}
	}
</script>