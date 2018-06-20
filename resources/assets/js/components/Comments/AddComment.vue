<template>
	<div class="mb-5">
		<a 
			href="#"
			class="btn btn-primary btn-block"
			@click.prevent="active = true"
			v-if="!active"
		>
			Add comment
		</a>

		<template v-if="active">
			<form @submit.prevent="submit">
				<div class="form-group">
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

				<div class="form-group">
					<input 
	                    type="file" 
	                    name="attachment" 
	                    :id="fileInputId"
	                    @change="fileInputChange"
	                    v-if="!uploading"
	                >
	            </div>

	            <template v-if="uploading && !failed">
                    <div class="progress" v-if="!uploadingComplete">
                        <div class="progress-bar" v-bind:style="{width: fileProgress + '%' }"></div> 
                    </div>
                </template>

				<div class="form-group">
					<button type="submit" class="btn btn-primary">Save</button>
					<a 
						href="#" 
						class="btn btn-link" 
						@click.prevent="cancel"
					>Cancel</a>
				</div>
			</form>
		</template>
	</div>
</template>

<script>
	import axios from 'axios';
	import bus from '../../bus';

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

				bus.$emit('comment:stored', comment.data.data);

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

                       bus.$emit('comment:stored', res.data.data);

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
		}
	}
</script>