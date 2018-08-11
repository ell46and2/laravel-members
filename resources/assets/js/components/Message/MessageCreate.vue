<template>
	<form class="flow-vertical--3">

		<div class="panel">
		    <div class="panel__inner">
		        <div class="panel__header">
		            <h2 class="panel__heading">
		                Message Subject
		                <div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]">Give your message a title</div>
		            </h2>
		        </div>

		        <div class="panel__main">
		            <label class="form__label" for="subject">Subject</label>
		            <input type="text" v-model.trim="form.subject" class="form-control" placeholder="Enter subject">
		            <p style="color: red" v-if="validationFailed.subject">Please enter a subject</p>
		            <div v-if="validationFailed.subject" class="invalid-feedback">Please enter a subject</div>
		        </div>
		    </div>
		</div>

		<div class="panel" style="z-index:3;">
		    <div class="panel__inner">
		        <div class="panel__header">
		            <h2 class="panel__heading">
		                Select recipients
		                <div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]">Add users you wish to send this message to</div>
		            </h2>
		            <div class="panel__heading-meta">
		            	<template v-if="isAdmin">
		            		<button class="button button--primary" type="button" @click.prevent="sendToAllJets" v-if="!form.allJets">Select All JETs</button>
		            		<button class="button button--primary" type="button" @click.prevent="form.allJets = false" v-else>Deselect All JETs</button>

		                	<button class="button button--primary" type="button" @click.prevent="sendToAllCoaches" v-if="!form.allCoaches">Select All Coaches</button>
							<button class="button button--primary" type="button" @click.prevent="form.allCoaches = false" v-else>Deselect All Coaches</button>
		            	</template>
		            	

		                <button class="[ button button--primary ] [ mr-1 ]" type="button" @click.prevent="sendToAllJockeys" v-if="!form.allJockeys">Select all jockeys</button>
						<button class="[ button button--primary ] [ mr-1 ]" type="button" @click.prevent="form.allJockeys = false" v-else>Deselect all jockeys</button>

		                <button class="button button--primary" type="button" @click.prevent="deselectAll">Deselect all</button>
		            </div>
		        </div>
				
				<div class="panel__main flow-vertical--3">
			        <message-selection
						v-if="jockeyResource"
						:jockey-resource="jockeyResource"
						:coach-resource="coachResource"
						:jets-resource="jetsResource"
						:exclude-ids="recipientIds"
						v-on:addUser="addUser"
						:current-role="currentRole"
					></message-selection>
				
					<div class="five-col-grid">
						<message-recipient
							v-for="user in form.recipients"
							:key="user.id"
							:recipient="user"
							v-on:remove="removeUser"
						></message-recipient>

						<div class="five-col-grid__col d-flex" v-if="form.allJockeys">
                            <icon-coaches></icon-coaches>
                            <div class="user-card__main">
                                <div class="user-card__name">All {{ jockeyResource.length }} Jockeys</div>
                                <div class="user-card__meta">
                                    <button class="[ button button--text ] [ user-card__button ] pl-0" type="button" @click.prevent="form.allJockeys = false">Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="five-col-grid__col d-flex" v-if="form.allCoaches">
                            <icon-coaches></icon-coaches>
                            <div class="user-card__main">
                                <div class="user-card__name">All {{ coachResource.length }} Coaches</div>
                                <div class="user-card__meta">
                                    <button class="[ button button--text ] [ user-card__button ] pl-0" type="button" @click.prevent="form.allCoaches = false">Remove</button>
                                </div>
                            </div>
                        </div>

                        <div class="five-col-grid__col d-flex" v-if="form.allJets">
                            <icon-coaches></icon-coaches>
                            <div class="user-card__main">
                                <div class="user-card__name">All {{ jetsResource.length }} JETS</div>
                                <div class="user-card__meta">
                                    <button class="[ button button--text ] [ user-card__button ] pl-0" type="button" @click.prevent="form.allJets = false">Remove</button>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
		    </div>
		</div>

		<div class="panel">
		    <div class="panel__inner">
		        <div class="panel__header">
		            <h2 class="panel__heading">
		                Message
		                <div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]">Type your message in the input field below</div>
		            </h2>
		        </div>

		        <div class="panel__main">
		            <label class="form__label" for="message">Add your message</label>
		            <textarea class="form-control" v-model.trim="form.body" placeholder="Enter message" rows="6"></textarea>
		            <div class="invalid-feedback" v-if="validationFailed.body">Please enter a message</div>
		        </div>
		    </div>
		</div>

		<button
			:disabled="disabledButton()"
			class="button button--primary button--block" 
			type="submit"
			@click.prevent="handleSubmit"
		>
			Send Message
		</button>
	</form>


<!-- 
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
			:current-role="currentRole"
		></message-selection>
		
		<br><br><br>

		<message-recipient
			v-for="user in form.recipients"
			:key="user.id"
			:recipient="user"
			v-on:remove="removeUser"
		></message-recipient>

		<br><br><br>

		<div class="panel">
		    <div class="panel__inner">
		        <div class="panel__header">
		            <h2 class="panel__heading">
		                Message
		                <div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]">Type your message in the input field below</div>
		            </h2>
		        </div>

		        <div class="panel__main">
		            <label class="form__label" for="message">Add your message</label>
		            <textarea class="form-control" v-model.trim="form.body" placeholder="Enter message" rows="6"></textarea>
		            <div class="invalid-feedback" v-if="validationFailed.body">Please enter a message</div>
		        </div>
		    </div>
		</div>


		<br><br><br>

		<button
			:disabled="disabledButton()"
			class="button button--primary button--block" 
			type="submit"
			@click.prevent="handleSubmit"
		>
			Send Message
		</button>
	</form> -->
</template>

<script>
	import MessageSelection from './MessageSelection';
	import MessageRecipient from './MessageRecipient';
	import IconCoaches from './IconCoaches';

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
			},
			currentRole: {
				required: true,
				type: String
			},
			preselectRecipientId: {
				required: false
			}
		},
		components: {
			MessageSelection,
			MessageRecipient,
			IconCoaches
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

			if(this.preselectRecipientId) {
				this.addPreselected();
			}
		},
		computed: {
			recipientIds() {
				return _.map(this.form.recipients, 'id');
			},
			isAdmin() {
				return this.currentRole === 'admin';
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
			sendToAllCoaches() {
				this.form.recipients = this.form.recipients.filter(recipient => {
					return recipient.role != 'coach';
				});
				this.form.allCoaches = true;
			},
			sendToAllJets() {
				this.form.recipients = this.form.recipients.filter(recipient => {
					return recipient.role != 'jets';
				});
				this.form.allJets = true;
			},
			deselectAll() {
				this.form.recipients = [];
				this.form.allJockeys = false;
				this.form.allCoaches = false;
				this.form.allJets = false;
			},
			disabledButton() {
				if((this.form.recipients.length || this.form.allJockeys || this.form.allCoaches || this.form.allJets) && this.form.subject && this.form.body) {
					return false;
				}

				return true;
			},
			addPreselected() {
				this.addUser(this.preselectRecipientId);
			}
		}
	}

</script>