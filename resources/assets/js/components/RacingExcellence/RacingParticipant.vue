<template>
	<div>
		<p>{{ participant.name }}</p>

		<form @submit.prevent="submit">
			<div class="row">
				<points-radios 
					:currentValue="form.presentation_points"
					field="presentation_points"
					label="Presentation"
					v-on:valueChange="setPoints"
					:participant-id="participant.id"
				></points-radios>

				<points-radios 
					:currentValue="form.professionalism_points"
					field="professionalism_points"
					label="Professionalism"
					v-on:valueChange="setPoints"
					:participant-id="participant.id"
				></points-radios>

				<points-radios 
					:currentValue="form.coursewalk_points"
					field="coursewalk_points"
					label="Course Walk"
					v-on:valueChange="setPoints"
					:participant-id="participant.id"
				></points-radios>

				<points-radios 
					:currentValue="form.riding_points"
					field="riding_points"
					label="Riding"
					v-on:valueChange="setPoints"
					:participant-id="participant.id"
				></points-radios>
			</div>
			
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
					{{ participant.total_points ? 'Update' : 'Save' }}
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
					presentation_points: this.participant.presentation_points,
					professionalism_points: this.participant.professionalism_points,
					coursewalk_points: this.participant.coursewalk_points,
					riding_points: this.participant.riding_points,
					feedback: this.participant.feedback,
				},
				validationFailed: {
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
		mounted() {
			// if(!this.participant.place && this.participant.total_points) {
			// 	this.form.place = 'dnf';
			// }
		},
		methods: {
			setPoints(value, field) {
				// alert(field);
				this.form[`${field}`] = value;
			},
			async submit() {
				if(this.validate()) {
					let participant = await axios.put(`/racing-excellence/participant/${this.participant.id}`, this.form);

					bus.$emit('participant:updated', participant.data.data);
				}
			},
			validate() {
				// if(this.form.place === null) {
				// 	this.validationFailed.place = true;
				// }
				// check all required fields are not null
				// if salisbury the radios can be null
				// feedback can always be null
				return true;
			}
		}
	}
</script>