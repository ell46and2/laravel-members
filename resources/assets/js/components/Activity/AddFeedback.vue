<template>
	<form @submit.prevent="submit" class="flow-vertical--3">
		    <textarea 
		    	class="form-control" 
		    	name="name" 
		    	rows="8" 
		    	cols="80"  
		    	id="body"
		    	v-model="form.feedback"
		    >	
		    </textarea>

		    <button class="button button--success button--block" type="submit">Save</button>
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