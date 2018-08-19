<template>
  <form @submit.prevent="submit">
    <div class="panel">
        <div class="panel__inner">
            <div class="panel__header">
                <h2 class="panel__heading">
                    {{ editDocument ? 'Edit' : 'Add a' }} Document
                    <div class="[ text--color-base text--size-base ] [ font-weight-normal ] [ mt-1 ]" v-if="!editDocument">Add a Document for all other users to access</div>
                </h2>
            </div>

            <div class="panel__main flow-vertical--3">

                <div class="mt-2 form-group flow-vertical--1">
                    <label class="text--color-blue">Document Name</label>
                    <input 
                        :class="`form-control${validationFailed.title ? ' is-invalid' : ''}`" 
                        type="text" 
                        v-model.trim="form.title" 
                        placeholder="Enter name..."
                    >
                    <div v-if="validationFailed.title" class="invalid-feedback">Please add a title</div>
                </div>

                <div class="mt-2 form-group flow-vertical--1">
                    <label class="text--color-blue">Description</label>
                    <input 
                        :class="`form-control${validationFailed.description ? ' is-invalid' : ''}`" 
                        type="text" 
                        v-model.trim="form.description"
                        placeholder="Enter description..."
                    >
                    <div v-if="validationFailed.description" class="invalid-feedback">Please add a description</div>
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

                <template v-if="!file && editDocument">
                    <p>Current Document: {{ editDocument.document_filename }}</p>
                </template>

                <div class="flow-vertical--1" v-if="file === null">

                    <input 
                        :class="`form-control attachment-upload__input${validationFailed.file ? ' is-invalid' : ''}`"
                        type="file" 
                        name="attachment" 
                        id="document_upload"
                        @change="fileInputChange"
                        v-if="!uploading"
                    >
                    <div v-if="validationFailed.file" class="invalid-feedback">Please upload a document</div>
                    <label class="panel__call-to-action" for="document_upload">Upload a{{ editDocument ? ' new' : '' }} Document</label>                  
                </div>

                <template v-if="uploading && fileProgress === 100">
                    <p>Saving Document to storage...</p>
                </template>
            </div>

        </div>
    </div>

    <button class="button button--primary button--block" type="submit">{{ editDocument ? 'Save' : 'Upload' }}</button>
  </form>

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
                form: {
                    title: '',
                    description: ''
                },
                validationFailed: {
                    title: false,
                    description: false,
                    file: false
                },
                editDocument: null
            }
        },
        props: {
            oldDocument: {
                required: false,
                type: String
            }
        },
        mounted() {
            if(this.oldDocument) {
                console.log(JSON.parse(this.oldDocument));
                this.editDocument = JSON.parse(this.oldDocument);
                this.form.title = this.editDocument.title;
                this.form.description = this.editDocument.description;
            }
            
        },
        methods: {
            fileInputChange() {
                this.file = document.getElementById('document_upload').files[0];
                console.log(this.file);
            },
            removeFile() {
                this.file = null;
            },
            uploadProgress(percent) {
                console.log('percent', percent);
                this.fileProgress = percent;
            },
            submit() {
                if(!this.validate()) {
                    return;
                }

                this.uploading = true;
                this.failed = false;

                let form = new FormData();

                form.append('title', this.form.title);
                form.append('description', this.form.description);

                if(this.editDocument && !this.file) {
                    axios.post(`/admin/documents/${this.editDocument.id}`, form)
                    .then((res) => {
                        window.location.href = '/documents';
                    }, () => {
                        //failed
                    }); 
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


                    if(this.editDocument) {
                        axios.post(`/admin/documents/${this.editDocument.id}`, form, config)
                        .then((res) => {
                            window.location.href = '/documents';
                        }, () => {
                            //failed
                        }); 
                    } else {
                       axios.post('/admin/documents', form, config)
                        .then((res) => {
                            window.location.href = '/documents';
                        }, () => {
                            //failed
                        }); 
                    }
                }    
                
            },
            validate() {
                let errors = 0;
                if(this.form.title === '') {
                    this.validationFailed.title = true;
                    errors++;
                } else {
                    this.validationFailed.title = false;
                }

                if(this.form.description === '') {
                    this.validationFailed.description = true;
                    errors++;
                } else {
                    this.validationFailed.description = false;
                }

                if(this.file === null && !this.editDocument) {
                    this.validationFailed.file = true;
                    errors++;
                } else {
                    this.validationFailed.file = false;
                }

                if(errors > 0) {
                    return false;
                }

                return true;
            }

        }
    }

</script>