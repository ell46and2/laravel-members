<template>
	<form>

		<input type="text" v-model.trim="form.subject" placeholder="Enter subject">
		<p style="color: red" v-if="validationFailed.subject">Please enter a subject</p>

		<br><br><br>
		
		<button 
			@click.prevent="sendToAllJockeys"
			class="btn btn-primary"
		>
			Send To All Jockeys
		</button>

		<button 
			@click.prevent="sendToAllCoaches"
			class="btn btn-primary"
			v-if="coachResource"
		>
			Send To All Coaches
		</button>

		<button 
			@click.prevent="deselectAll"
			class="btn btn-primary"
		>
			Deselect All
		</button>
		
		<message-selection
			v-if="jockeyResource"
			:jockey-resource="jockeyResource"
			:coach-resource="coachResource"
			:jets-resource="jetsResource"
			:exclude-ids="recipientIds"
			v-on:addUser="addUser"
		></message-selection>
		
		<br><br><br>

		<message-recipient
			v-for="user in form.recipients"
			:key="user.id"
			:recipient="user"
			v-on:remove="removeUser"
		></message-recipient>

	<!-- 	<message-selection
			v-if="coachResource"
			:resource="coachResource"
			:exclude-ids="form.recipients.coach"
			role="coach"
			v-on:addUser="addCoach"
			v-on:removeUser="removeCoach"
			v-on:sendToAll="sendToAllCoaches"
			v-on:sendToAllCancelled="sendToAllCoachesCancelled"
		></message-selection> -->

		<br><br><br>

		<textarea v-model.trim="form.body" cols="30" rows="10"></textarea>
		<p style="color: red" v-if="validationFailed.body">Please enter a message</p>

		<br><br><br>

		<button
			:disabled="disabledButton()"
			class="btn btn-primary" 
			type="submit"
			@click.prevent="handleSubmit"
		>
			Send Message
		</button>
	</form>
</template>

<script>
	import MessageSelection from './MessageSelection';
	import MessageRecipient from './MessageRecipient';

	export default {
		data() {
			return {
				
				jockeyResource: null,
				coachResource: null,
				jetsResource: null,		
				form: {
					recipients: [],
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
			MessageSelection,
			MessageRecipient
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
		computed: {
			recipientIds() {
				return _.map(this.form.recipients, 'id');
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
				if(this.form.body !== '') {
					this.validationFailed.body = false;
					return true;
				}
				this.validationFailed.body = true;
				return false;
			},
			validateSubject() {
				if(this.form.subject !== '') {
					this.validationFailed.subject = false;
					return true;
				}
				// show validation error for subject
				this.validationFailed.subject = true;
				return false;
			},
			validateRecipients() {
				if(this.form.recipients.length ||
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
				let data = {
					recipients: this.recipientIds,
					subject: this.form.subject,
					body: this.form.body,
					allJockeys: this.form.allJockeys,
					allCoaches: this.form.allCoaches,
					allJets: this.form.allJets,
				};

				let result = await axios.post('/messages', data);

				if(result.data === 'success') {
					window.location.href = '/messages';
				}
			},
			async addUser(id) {
				let result = await axios.post(`/messages/user/${id}`);

				let user = result.data.data;

				this.form.recipients.push(user);
			},
			removeUser(id) {
				this.form.recipients = this.form.recipients.filter(recipient => {
					return recipient.id != id;
				}); 
			},
			sendToAllJockeys() {
				this.form.recipients = this.form.recipients.filter(recipient => {
					return recipient.role != 'jockey';
				});
				this.form.allJockeys = true;
			},
			sendToAllJockeysCancelled() {
				this.form.allJockeys = false;
			},
			sendToAllCoaches() {
				this.form.recipients = this.form.recipients.filter(recipient => {
					return recipient.role != 'coach';
				});
				this.form.allCoaches = true;
			},
			sendToAllCoachesCancelled() {
				this.form.allCoaches = false;
			},
			deselectAll() {
				this.form.recipients = [];
			},
			disabledButton() {
				if((this.form.recipients.length || this.form.allJockeys || this.form.allCoaches || this.form.allJets) && this.form.subject && this.form.body) {
					return false;
				}

				return true;
			}
		}
	}

</script>