<template>
	<form @submit.prevent="submit">
		<div class="form-group">
			<textarea 
				id="body" 
				rows="10"
				class="form-control"
				autofocus="autofocus"
				v-model="form.feedback"
			></textarea>
		</div>

		<div class="form-group">
			<button type="submit" class="btn btn-primary">{{ submitName }}</button>
		</div>
	</form>
</template>

<script>
	
	export default {
		data() {
			return {
				form: {
					feedback: null
				},
				update: false
			}
		},
		props: {
			activityId: {
				required: true
			},
			jockeyId: {
				required: true
			},
			currentFeedback: {
				required: false,
				default: ''
			}
		},
		mounted() {
			if(this.currentFeedback) {
				this.form.feedback = this.currentFeedback;
				this.update = true;
			}
		},
		methods: {
			submit() {
				console.log(this.form.body);
				axios.post(`/activity/${this.activityId}/feedback/${this.jockeyId}`, this.form)
               	.then((res) => {
                   this.update = true;
                   
               	}, () => {
                   // handle failer
               	}); 
			}
		},
		computed: {
			submitName() {
				return this.update ? 'Update' : 'Save';
			}
		}
	}
</script>