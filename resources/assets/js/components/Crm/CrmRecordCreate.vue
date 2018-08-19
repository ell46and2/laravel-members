<template>
	<form @submit.prevent="submit">
	  <div class="panel">
	      <div class="panel__inner">
	          <div class="panel__header">
	              <h2 class="panel__heading">
	                  {{ editRecord ? 'Edit' : 'Add a' }} CRM for {{ userName }}
	              </h2>
	          </div>

	          <div class="panel__main flow-vertical--3">

	              <div class="mt-2 form-group flow-vertical--1">
	                  <label class="text--color-blue">Location</label>
	                  <input 
	                      :class="`form-control${validationFailed.location ? ' is-invalid' : ''}`" 
	                      type="text" 
	                      v-model.trim="form.location" 
	                      placeholder="Enter name..."
	                  >
	                  <div v-if="validationFailed.location" class="invalid-feedback">Please add a location</div>
	              </div>

	              <div class="mt-2 form-group flow-vertical--1">
	                  <label class="text--color-blue">Comment</label>
	                  <textarea 
	                      :class="`form-control${validationFailed.comment ? ' is-invalid' : ''}`" 
	                      v-model.trim="form.comment"
	                      placeholder="Enter comment..."
	                      rows="6"
	                  ></textarea>
	                  <div v-if="validationFailed.comment" class="invalid-feedback">Please add a comment</div>
	              </div>
					
					<div class="mt-2 form-group flow-vertical--1">
	                	<label class="text--color-blue">Follow Up Date</label>
	              		<div class="form-group form-group--has-icon">
							<datepicker 
							name="review_date" 
							placeholder="placeholder"
							format="dd/MM/yyyy"
							v-model="form.review_date"
							input-class="form-control form-control--has-icon mt-1"
							></datepicker>
							<span class="form-group__input-icon" aria-hidden="true" role="presentation">
					            <icon-calendar></icon-calendar>
					        </span>
						</div>
	              	</div>

	              <template v-if="uploading && !failed">
	                  <div class="attachment-progress" v-if="!uploadingComplete">
	                      <div class="attachment-progress__bar" v-bind:style="{width: fileProgress + '%' }"></div> 
	                  </div>
	              </template>

	              <template v-if="file">
	                  <p><span class="text--color-blue">File Selected:</span> {{ file.name }}</p>
	                  <a href="#" @click.prevent="removeFile" class="btn btn-link">Remove</a>
	              </template>

	              <template v-if="!form.attachmentRemoved && editRecord && editRecord.document_filename">
	                  <p><span class="text--color-blue">Current Attachment:</span> {{ editRecord.document_filename }} <a href="#" @click.prevent="attachmentRemoved" class="[ button button--primary ] [ heading__button ]">Remove</a></p>
	                  
	              </template>

	              <div class="flow-vertical--1" v-if="!hasFile">

	                  <input 
	                      :class="`form-control attachment-upload__input${validationFailed.file ? ' is-invalid' : ''}`"
	                      type="file" 
	                      name="attachment" 
	                      id="attachment_upload"
	                      @change="fileInputChange"
	                      v-if="!uploading"
	                  >
	                  <div v-if="validationFailed.file" class="invalid-feedback">Please upload a document</div>
	                  <label class="panel__call-to-action" for="attachment_upload">Upload a{{ editRecord ? ' new' : '' }} Document</label>                  
	              </div>

	              <template v-if="uploading && fileProgress === 100">
	                  <p>Saving Document to storage...</p>
	              </template>
	          </div>

	      </div>
	  </div>

	  <button class="button button--primary button--block" type="submit">Save</button>
	</form>
</template>


<script>
	import Datepicker from 'vuejs-datepicker';
	import IconCalendar from '../Forms/IconCalendar';
	import moment from 'moment';
	
	export default {
		data() {
			return {
				uploading: false,
				file: null,
				uploadingComplete: false,
				failed: false,
				fileProgress: 0,
				form: {
				    location: '',
				    comment: '',
				    review_date: '',
				    attachmentRemoved: false
				},
				validationFailed: {
				    location: false,
				    comment: false,
				    review_date: false
				},
				editRecord: null,
				hasFile: false,
			}
		},
		props: {
			userId: {
				required: true
			},
			userName: {
				required: true
			},
			userType: {
				required: true // jockey or crm-jockey
			},
			oldRecord: {
				required: false,
				type: String
			}
		},
		components: {
			Datepicker,
			IconCalendar
		},
		mounted() {
			if(this.oldRecord) {
				this.prepopulateWithOld();
			}
		},
		computed: {
			createUrl() {
				return this.userType === 'jockey' ? `/jets/crm/create/${this.userId}` : `/jets/crm/create/${this.userId}/crm`;
			}
		},
		methods: {
			prepopulateWithOld() {
				this.editRecord = JSON.parse(this.oldRecord);

				this.form.location = this.editRecord.location;
				this.form.comment = this.editRecord.comment;

				if(this.editRecord.review_date) {
					this.form.review_date = new Date(this.editRecord.review_date.split('/').reverse().join('/'));
				}

				if(this.editRecord.document_filename) {
					this.hasFile = true;
				}
			},
			fileInputChange() {
                this.file = document.getElementById(`attachment_upload`).files[0];
                this.hasFile = true;
                console.log(this.file);
            },
            uploadProgress(percent) {
                console.log('percent', percent);
                this.fileProgress = percent;
            },
            removeFile() {
                this.file = null;
                this.hasFile = false;
            },
			submit() {
				if(!this.validate()) {
                    return;
                }

                this.uploading = true;
                this.failed = false;

                let form = new FormData();

                form.append('location', this.form.location);
                form.append('comment', this.form.comment);
                form.append('attachmentRemoved', this.form.attachmentRemoved);
                if(this.form.review_date !== '') {
                	form.append('review_date', moment(this.form.review_date).format('DD/MM/Y'));
                }
                

				if(!this.file) {
					if(this.editRecord) {
						axios.post(`/jets/crm/${this.editRecord.id}/update`, form)
							.then(res => {
								window.location.href = `/jets/crm/${res.data.crmRecordId}`;
							}, () => {
							// failed
						});
					} else {
						axios.post(this.createUrl, form)
						.then(res => {
							window.location.href = `/jets/crm/${res.data.crmRecordId}`;
						}, () => {
							// failed
						});
					}
					
				} else {
					form.append('document', this.file);

					const uploadProgress = this.uploadProgress;

                    let config = {
                        onUploadProgress(progressEvent) {
                           let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);

                           uploadProgress(percentCompleted);

                           return percentCompleted;
                        }
                    };

                    if(this.editRecord) {
						axios.post(`/jets/crm/${this.editRecord.id}/update`, form, config)
							.then(res => {
								window.location.href = `/jets/crm/${res.data.crmRecordId}`;
							}, () => {
							// failed
						});
					} else {
						axios.post(this.createUrl, form, config)
						.then(res => {
							window.location.href = `/jets/crm/${res.data.crmRecordId}`;
						}, () => {
							// failed
						});
					}

				}
			},
			validate() {
				let errors = 0;
                if(this.form.location === '') {
                    this.validationFailed.location = true;
                    errors++;
                } else {
                    this.validationFailed.location = false;
                }

                if(this.form.comment === '') {
                    this.validationFailed.comment = true;
                    errors++;
                } else {
                    this.validationFailed.comment = false;
                }

                if(errors > 0) {
                    return false;
                }

                return true;
			},
			attachmentRemoved() {
				this.form.attachmentRemoved = true;
				this.hasFile = false;

			}
		}
	}
</script>

<style>
.vdp-datepicker__calendar header {
	color: #00304E;
}
.vdp-datepicker__calendar header .prev:after
{
	border-right: 10px solid #00304E;
}
.vdp-datepicker__calendar header .next:after {
	border-left: 10px solid #00304E;
}
.day__month_btn,
.month__year_btn {
	color: #00304E;
	font-family: "DIN", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
}
.day-header {
	color: #1E70B7;
}
.cell.selected {
	color: white;
	background: #1E70B7 !important;
	border-radius: 4px;
}
.vdp-datepicker__calendar .cell:not(.blank):not(.disabled).day:hover, 
.vdp-datepicker__calendar .cell:not(.blank):not(.disabled).month:hover, 
.vdp-datepicker__calendar .cell:not(.blank):not(.disabled).year:hover {
	border: 1px solid #1E70B7;
	border-radius: 4px;
	background: #1E70B7 !important;
	color: white;
}
</style>