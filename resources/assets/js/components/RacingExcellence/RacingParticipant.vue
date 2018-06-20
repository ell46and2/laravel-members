<template>
	<div>
		<p>{{ participant.name }}</p>

		<form @submit.prevent="submit">
			<div class="form-group">
			    <label for="exampleFormControlSelect1">Place</label>
			    <select 
			    	class="form-control" 
			    >
			    	<option value="">Select Place</option>
			      	<option v-for="i in numberOfParticipants" :value="i">{{ i }}</option>
			    </select>
			</div>

			<points-radios 
				:currentValue="form.presentation_points"
				field="presentation_points"
				label="Presentation"
				v-on:valueChange="setPoints"
			></points-radios>

			<points-radios 
				:currentValue="form.professionalism_points"
				field="professionalism_points"
				label="Professionalism"
				v-on:valueChange="setPoints"
			></points-radios>

			<points-radios 
				:currentValue="form.coursewalk_points"
				field="coursewalk_points"
				label="Course Walk"
				v-on:valueChange="setPoints"
			></points-radios>

			<points-radios 
				:currentValue="form.riding_points"
				field="riding_points"
				label="Riding"
				v-on:valueChange="setPoints"
			></points-radios>

			<div class="form-group">
				<textarea 
					id="body" 
					rows="10"
					class="form-control"
					autofocus="autofocus"
					v-model="form.feedback"
				></textarea>
			</div>
		</form>
	</div>
</template>

<script>
	import PointsRadios from './PointsRadios';

	export default {
		data() {
			return {
				form: {
					place: this.participant.place,
					presentation_points: this.participant.presentation_points,
					professionalism_points: this.participant.professionalism_points,
					coursewalk_points: this.participant.coursewalk_points,
					riding_points: this.participant.riding_points,
					feedback: this.participant.feedback,
				}
			}
		},
		props: {
			participant: {
				required: true,
				type: Object
			},
			numberOfParticipants: {
				required: true,
				type: Number
			}
		},
		components: {
			PointsRadios
		},
		methods: {
			setPoints(value, field) {
				// alert(value);
				this.form[`${field}`] = value;
			},
			submit() {
				if(this.validate()) {
					// post form data
					// receive the new participant resource back
					// emit using bus to RacingExcellenceResults component
					// That component will then update the data.
				}
			},
			validate() {
				// check all required fields are not null
				// if salisbury the radios can be null
				// feedback can always be null
				return true;
			}
		}
	}
</script>