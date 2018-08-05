<template>
	<form @submit.prevent="submit">
		<div class="form-control">
			<textarea
				id="body"
				rows="4"
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

		<template v-if="(!comment.attachment && file === null) || form.attachmentRemoved">
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

		<template v-if="comment.attachment && !form.attachmentRemoved">
			<img 
				class="mr-3 mb-3 mt-2" 
				:src="comment.attachment.thumbnail" 
				alt="comment attachment"
			>
			<a href="#" @click.prevent="form.attachmentRemoved = true" class="btn btn-link">Remove</a>
		</template>

		<template v-if="uploading && !failed">
            <div class="progress" v-if="!uploadingComplete">
                <div class="progress-bar" v-bind:style="{width: fileProgress + '%' }"></div> 
            </div>
        </template>	        

		<div class="form-group">
			<button class="button button--success button--block" type="submit">Save</button>
			<a href="#" @click.prevent="cancel" class="button button--primary button--block">Cancel</a>
		</div>
	</form>
</template>

<script>
	import bus from '../../bus';

	export default {
		data() {
			return {
				form: {
					body: this.comment.body,
					private: this.comment.private,
					attachmentRemoved: false,
				},
				file: null,
				uploading: false,
				uploadingComplete: false,
                failed: false,
                fileProgress: 0,
			}
		},
		props: {
			comment: {
				required: true,
				type: Object
			},
			isCurrentUserJockey: {
				required: true,
				type: String
			}
		},
		methods: {
			submit() {
				if(this.file === null) {
					this.patch();
				} else {
					this.patchWithAttachment();
				}
			},
			async patch() {
				let comment = await axios.post(`/comment/${this.comment.id}`, this.form);

				bus.$emit('comment:edited', comment.data.data);

				this.cancel();
			},
			patchWithAttachment() {
				this.uploading = true;
                this.failed = false;

                let form = new FormData();

                form.append('attachment', this.file);
                form.append('fileExtension', this.file.name.split('.').pop());
                form.append('body', this.form.body);
                form.append('private', this.form.private);
                form.append('attachmentRemoved', this.form.attachmentRemoved);

                const uploadProgress = this.uploadProgress;

                let config = {
                   	onUploadProgress(progressEvent) {
                       let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);

                       uploadProgress(percentCompleted);

                       return percentCompleted;
                   	}
               	};

               	let comment = axios.post(`/comment/${this.comment.id}`, form, config)
                   	.then((res) => {
                       this.uploadingComplete = true;

                       bus.$emit('comment:edited', res.data.data);

                       this.cancel();
                       
                   	}, () => {
                       this.failed = true;
                   	}); 
			},
			cancel() {
				bus.$emit('comment:edit-cancelled', this.comment);
			},
			fileInputChange() {
                this.file = document.getElementById(`attachment_${this.recipientId}_edit`).files[0];
                console.log(this.file);
            },
            uploadProgress(percent) {
                console.log('percent', percent);
                this.fileProgress = percent;
            },
            removeFile() {
            	this.file = null;
            }
		},
		computed: {
			fileInputId() {
				return `attachment_${this.recipientId}_edit`;
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