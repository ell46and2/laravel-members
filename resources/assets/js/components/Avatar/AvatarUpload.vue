<template>
	<div class="panel">
	    <div class="panel__inner">
	        <div class="panel__header">
	            <h2 class="panel__heading">
	                Profile Picture
	                <div class="text--color-base text--size-base font-weight-normal mt-1">
	                    Upload a profile picture of yourself in the following file formats, JPG or PNG.
	                </div>
	            </h2>
	        </div>

	        <div class="panel__main">
	            <div class="w-25 text-center">
	                <div class="[ avatar avatar--green ]">
	                    <div class="avatar__image" :style="`background-image:url('${avatarPath}');`"></div>
	                </div>
	                <a v-if="hasAvatar && canEdit" class="link--underlined" href="#" @click.prevent="remove">Remove</a>
	            </div>
	        </div>

	        <template v-if="uploading && !failed">
	            <div class="attachment-progress" v-if="!uploadingComplete">
	                <div class="attachment-progress__bar" v-bind:style="{width: fileProgress + '%' }"></div> 
	            </div>
	        </template>
			
			<template v-if="!hasAvatar && canEdit">
				<input 
		            type="file"
		            class="attachment-upload__input" 
		            name="avatar-input" 
		            id="avatar-input"
		            @change="fileInputChange"
		            v-if="!uploading"
		        >
				<label class="panel__call-to-action" for="avatar-input">Upload</label>
			</template>
	        
	    </div>
	</div>
</template>

<script>
	
	export default {
		data() {
			return {
				hasAvatar: false,
				avatarPath: this.avatarImage,
				uploading: false,
                file: null,
                uploadingComplete: false,
                failed: false,
                fileProgress: 0,
			}
		},
		props: {
			canEdit: {
				required: true,
			},
			userId: {
				required: true
			},
			avatarImage: {
				required: true
			},
			avatarFilename: {
				required: true
			}
		},
		mounted() {
			if(this.avatarFilename) {
				this.hasAvatar = true;
			}
		},
		methods: {
			fileInputChange() {
			    this.file = document.getElementById('avatar-input').files[0];

			    if(this.file !== null) {
			       this.uploading = true;
			       this.failed = false;

			       let form = new FormData();

			       form.append('avatar', this.file);
			       // form.append('modelType', this.modelType);
			       // form.append('modelId', this.modelId);
			       form.append('fileExtension', this.file.name.split('.').pop());

			       const uploadProgress = this.uploadProgress; 

			       let config = {
			           onUploadProgress(progressEvent) {
			               let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);

			               uploadProgress(percentCompleted);

			               return percentCompleted;
			           }
			       };

			       axios.post(`/avatar/${this.userId}`, form, config)
			       .then((result) => {
			          this.uploadingComplete = true;
			          this.avatarPath = result.data;
			          this.hasAvatar = true;

			          console.log(result.data);

			           

			       }, () => {
			           this.failed = true;
			       }); 
			    }
			},
			uploadProgress(percent) {
                this.fileProgress = percent;
            },
            async remove() {
            	let result = await axios.delete(`/avatar/${this.userId}`);

            	this.avatarPath = result.data;
			    this.hasAvatar = false;
            }
		}
	}
</script>