<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Upload</div>

                    <div class="card-body">
                        <input 
                            type="file" 
                            name="attachment" 
                            id="attachment"
                            @change="fileInputChange"
                            v-if="!uploading"
                        >
                        <button class="btn btn-primary" @click="submit">Upload</button>
                    </div>

                    <template v-if="uploading && !failed">
                        <div class="progress" v-if="!uploadingComplete">
                            <div class="progress-bar" v-bind:style="{width: fileProgress + '%' }"></div> 
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                uploading: false,
                file: null,
                uploadingComplete: false,
                failed: false,
                fileProgress: 0,
            }
        },
        props: {
            modelType: null,
            modelId: null,
        },
        methods: {
            fileInputChange() {
                this.file = document.getElementById('attachment').files[0];
            },
            submit() {
                console.log('submitted');
                if(this.file !== null) {
                   this.uploading = true;
                   this.failed = false;

                   let form = new FormData();

                   form.append('attachment', this.file);
                   form.append('modelType', this.modelType);
                   form.append('modelId', this.modelId);
                   form.append('fileExtension', this.file.name.split('.').pop());

                   const uploadProgress = this.uploadProgress; 

                   let config = {
                       onUploadProgress(progressEvent) {
                           let percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);

                           uploadProgress(percentCompleted);

                           return percentCompleted;
                       }
                   };

                   axios.post('/attachment', form, config)
                   .then(() => {
                       this.uploadingComplete = true;
                   }, () => {
                       this.failed = true;
                   }); 
                }            
            },
            uploadProgress(percent) {
                console.log('percent', percent);
                this.fileProgress = percent;
            }
        },
        mounted() {
            console.log(this.modelId);
            console.log('Component mounted.')
            console.log(this.modelType);
        }
    }
</script>
