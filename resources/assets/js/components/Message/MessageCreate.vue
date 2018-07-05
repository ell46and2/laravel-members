<template>
	<form @submit.prevent="handleSubmit">

		<input type="text" v-model="form.subject" placeholder="Enter subject">
		<p style="color: red" v-if="validationFailed.subject">Please enter a subject</p>

		<br><br><br>
		
		<message-selection
			v-if="jockeyResource"
			:resource="jockeyResource"
			:exclude-ids="form.recipients.jockey"
			role="jockey"
			v-on:addUser="addJockey"
			v-on:removeUser="removeJockey"
			v-on:sendToAll="sendToAllJockeys"
			v-on:sendToAllCancelled="sendToAllJockeysCancelled"
		></message-selection>
		
		<br><br><br>

		<message-selection
			v-if="coachResource"
			:resource="coachResource"
			:exclude-ids="form.recipients.coach"
			role="coach"
			v-on:addUser="addCoach"
			v-on:removeUser="removeCoach"
			v-on:sendToAll="sendToAllCoaches"
			v-on:sendToAllCancelled="sendToAllCoachesCancelled"
		></message-selection>

		<br><br><br>

		<textarea v-model="form.body" cols="30" rows="10"></textarea>
		<p style="color: red" v-if="validationFailed.body">Please enter a message</p>

		<br><br><br>

		<button class="btn btn-primary" type="submit">
			Send Message
		</button>
	</form>
</template>

<script>
	import MessageSelection from './MessageSelection';

	export default {
		data() {
			return {
				
				jockeyResource: null,
				coachResource: null,
				jetsResource: null,		
				form: {
					recipients: {
						jockey: [],
						coach: [],
						jets: []
					},
					subject: '',
					body: '',
					allJockeys: false,
					allCoaches: false,
					allJets: false,
				},
				validationFailed: {
					subject: false,
					body: false,
					recipients: false
				}
			}
		},
		props: {
			resources: {
				required: true
			}
		},
		components: {
			MessageSelection
		},
		mounted() {
			// split resources into jockey, coach, jet
			let resources = JSON.parse(this.resources);

			console.log('resources', resources.jockeys);

			this.jockeyResource = resources.jockeys;

			if(resources.coaches) {
				this.coachResource = resources.coaches;
			}

			if(resources.jets) {
				this.jetsResource = resources.jets;
			}
		},
		methods: {
			handleSubmit() {

				// validate subject and body not empty
				if(!this.validate()) {
					return;
				}
				// validate at least one participant or all of one is true
				
				// show confirmation
				// with number of jockeys its being sent to
				// with number of coaches its being sent to
				// with number of jets its being sent to
					// once confirmed ajax post form
					// show a sending messages spinner
					// on complete redirect to messages.index
					
					this.submitForm();
			},
			validate() {
				if(this.validateBody() && this.validateSubject() && this.validateRecipients()) {
					return true;
				}
				return false;
			},
			validateBody() {
				if(this.form.body.trim() !== '') {
					this.validationFailed.body = false;
					return true;
				}
				this.validationFailed.body = true;
				return false;
			},
			validateSubject() {
				console.log('subject', this.form.subject.trim());
				if(this.form.subject.trim() !== '') {
					this.validationFailed.subject = false;
					return true;
				}
				// show validation error for subject
				console.log('sub');
				this.validationFailed.subject = true;
				return false;
			},
			validateRecipients() {
				if(this.form.recipients.jockey.length ||
					this.form.recipients.coach.length ||
					this.form.recipients.jets.length ||
					this.form.allJockeys ||
					this.form.allCoaches ||
					this.form.allJets
				) {
					return true;
				}

				// show validation error for no recipients selected
				return false;

			},
			async submitForm() {
				let recipients = this.form.recipients.jockey.concat(this.form.recipients.coach);
				recipients = recipients.concat(this.form.recipients.jets);

				let data = {
					recipients: recipients,
					subject: this.form.subject,
					body: this.form.body,
					allJockeys: this.form.allJockeys,
					allCoaches: this.form.allCoaches,
					allJets: this.form.allJets,
				};

				console.log('data', data);
				let result = await axios.post('/messages', data);

				if(result.data === 'success') {
					window.location.href = '/messages';
				}
			},
			addJockey(id) {
				this.form.recipients.jockey.push(id);
			},
			removeJockey(id) {
				this.form.recipients.jockey = this.form.recipients.jockey.filter(recipient => {
					recipient.id !== id;
				}); 
			},
			addCoach(id) {
				this.form.recipients.coach.push(id);
			},
			removeCoach(id) {
				this.form.recipients.coach = this.form.recipients.coach.filter(recipient => {
					recipient.id !== id;
				}); 
			},
			sendToAllJockeys() {
				this.form.recipients.jockey = [];
				this.form.allJockeys = true;
			},
			sendToAllJockeysCancelled() {
				this.form.allJockeys = false;
			},
			sendToAllCoaches() {
				this.form.recipients.coach = [];
				this.form.allCoaches = true;
			},
			sendToAllCoachesCancelled() {
				this.form.allCoaches = false;
			}
		}
	}

</script>