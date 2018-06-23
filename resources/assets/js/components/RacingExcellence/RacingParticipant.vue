<template>
	<div>
		<p>{{ participant.name }}</p>

		<form @submit.prevent="submit">
			<div class="form-group">
			    <label for="exampleFormControlSelect1">Place</label>
			    <select 
			    	class="form-control"
			    	v-model="form.place" 
			    >
			    	<option disabled :selected="form.place == null" value="">Select Place</option>
			      	<option 
			      		v-for="i in numberOfParticipants" 
			      		:value="i"
			      		:selected="form.place == i"
			      	>{{ i }}</option>
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
					v-model="form.feedback"
				></textarea>
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-primary">
					{{ participant.place || !participant.completed_race ? 'Update' : 'Save' }}
				</button>
				<a 
					href="#" 
					class="btn btn-link" 
					@click.prevent="cancel"
				>Cancel</a>
			</div>
		</form>
	</div>
</template>

<script>
	import PointsRadios from './PointsRadios';
	import bus from '../../bus';

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
				},
				validationFailed: {
					place: false,
					presentation_points: false,
					professionalism_points: false,
					coursewalk_points: false,
					riding_points: false,
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
			},
			placeOnlyRequired: {
				required: true,
				type: Boolean
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
			async submit() {
				if(this.validate()) {
					let participant = await axios.put(`/racing-excellence/participant/${this.participant.id}`, this.form);

					bus.$emit('participant:updated', participant.data.data);
				}
			},
			validate() {
				if(this.form.place === null) {
					this.validationFailed.place = true;
				}
				// check all required fields are not null
				// if salisbury the radios can be null
				// feedback can always be null
				return true;
			}
		}
	}
</script>