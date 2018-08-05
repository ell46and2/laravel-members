<template>
	<div class="mb-5 pt-3">
		<div class="col-4">
			<a 
				href="#"
				class="button button--success button--block"
				@click.prevent="active = true"
				v-if="!active"
			>
				Add comment
			</a>
		</div>
		

		<template v-if="active">
			<form @submit.prevent="submit">
				<div class="form-control">
					<textarea 
						id="body" 
						rows="10"
						class="form-control"
						autofocus="autofocus"
						v-model="form.body"
					></textarea>
				</div>

				<div class="form-check" v-if="!isCurrentUserJockey">
				  	<input class="form-check-input" type="checkbox" v-model="form.private">
				  	<label class="form-check-label">
				    	Private
				  	</label>
				</div>

	            <template v-if="uploading && !failed">
                    <div class="attachment-progress" v-if="!uploadingComplete">
                        <div class="attachment-progress__bar" v-bind:style="{width: fileProgress + '%' }"></div> 
                    </div>
                </template>

                <template v-if="file">
		        	<p>File Selected: {{ file.name }}</p>
		        	<a href="#" @click.prevent="removeFile" class="btn btn-link">Remove</a>
		        </template>

				<template v-if="file === null">
					<input 
						class="attachment-upload__input"
	                    type="file" 
	                    name="attachment" 
	                    :id="fileInputId"
	                    @change="fileInputChange"
	                    v-if="!uploading"
	                >
	                <label class="panel__call-to-action" :for="fileInputId">Attach a Photo or Video</label>
	            </template>

	            

				<div class="form-group">
					<button type="submit" class="button button--success button--block">Save</button>
					<a 
						href="#" 
						class="button button--primary button--block"
						@click.prevent="cancel"
					>Cancel</a>
				</div>
			</form>
		</template>
	</div>
</template>

<script>
	import axios from 'axios';

	export default {
		data() {
			return {
				active: false,
				form: {
					body: '',
					private: false,
					recipient_id: this.recipientId
				},
				file: null,
				uploading: false,
				uploadingComplete: false,
                failed: false,
                fileProgress: 0,
			}
		},
		props: {
			endpoint: {
				required: true,
				type: String
			},
			recipientId: {
				required: false,
				type: String
			},
			isCurrentUserJockey: {
				required: true,
				type: String
			}
		},
		methods: {
			submit() {
				if(this.file === null) { 
					this.store();
				} else {
					this.storeWithAttachment();
				}
			},
			async store() {
				let comment = await axios.post(this.endpoint, this.form);

				this.$emit('stored', comment.data.data);

				this.resetForm();
			},
			storeWithAttachment() {
				this.uploading = true;
                this.failed = false;

                let form = new FormData();

                form.append('attachment', this.file);
                form.append('fileExtension', this.file.name.split('.').pop());
                form.append('body', this.form.body);
                form.append('private', this.form.private);
                form.append('recipient_id', this.form.recipient_id);

                const uploadProgress = this.uploadProgress;

                let config = {
                   	onUploadProgress(progressEvent) {
                       let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);

                       uploadProgress(percentCompleted);

                       return percentCompleted;
                   	}
               	};

               	let comment = axios.post(this.endpoint, form, config)
                   	.then((res) => {
                       this.uploadingComplete = true;

                       this.$emit('stored', res.data.data);

                       this.resetForm();
                       
                   	}, () => {
                       this.failed = true;
                   	}); 
			},
			cancel() {
				this.resetForm();
			},
			resetForm() {
				this.active = false;
				this.form.body = '';
				this.form.private = false;
			},
			fileInputChange() {
                this.file = document.getElementById(`attachment_${this.recipientId}`).files[0];
            },
            uploadProgress(percent) {
                console.log('percent', percent);
                this.fileProgress = percent;
            }
		},
		computed: {
			fileInputId() {
				return `attachment_${this.recipientId}`;
			}
		},
		mounted() {
			window.onbeforeunload = () => {
                if(this.uploading && !this.uploadingComplete && !this.failed) {
                    return 'Are you sure you want to navigate away. Your attachment is currently uploading.'
                }
            }
		}
	}
</script>