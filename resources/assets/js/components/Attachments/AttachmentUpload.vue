<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Attach Videos or Photos</div>

                    <div class="card-body" v-if="attachments">
                      <div class="attachment-upload__attachments">

                        <div 
                          v-if="attachments.length"
                          v-for="attachment in attachments"
                          class="attachment-upload__attachment"
                        >
                          <attachment :attachment="attachment"></attachment>
                          <a 
                            href="#"
                            v-if="canEditAttachment"
                            @click.prevent="remove(attachment.uid)"
                          >
                            Remove
                          </a>

                        </div>    
                      </div>

                      <template v-if="!attachments.length">
                        <p>No media currently added</p>
                      </template>

                      <template v-if="canEditAttachment">
                        <input 
                            type="file"
                            class="attachment-upload__input" 
                            name="attachment" 
                            id="attachment"
                            @change="fileInputChange"
                            v-if="!uploading"
                        >
                        <label class="btn btn-primary" for="attachment">Attach a Photo or Video</label>
                        <!-- <button class="btn btn-primary" @click="submit">Attach a Photo or Video</button> -->
                      </template>
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
    import Attachment from './Attachment';
    import bus from '../../bus';

    export default {
        data() {
            return {
                uploading: false,
                file: null,
                uploadingComplete: false,
                failed: false,
                fileProgress: 0,
                attachments: null,
                intervals: [],
                canEditAttachment: !!+this.canEdit
            }
        },
        props: {
            modelType: null,
            modelId: null,
            resource: {
              required: true,
              type: String
            },
            canEdit: {
              default: false
            }
        },
        components: {
          'attachment': Attachment
        },
        methods: {
            fileInputChange() {
                this.file = document.getElementById('attachment').files[0];

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
                   .then((res) => {
                      this.uploadingComplete = true;
                      let attachment = res.data.data;
                      this.attachments.push(attachment);

                       // Maybe set an interval here
                       // use uid as the key
                      this.intervals[attachment.uid] = setInterval(() => {
                        this.updateAttachment(attachment.uid);
                      }, 5000);

                   }, () => {
                       this.failed = true;
                   }); 
                }
            },
            uploadProgress(percent) {
                this.fileProgress = percent;
            },
            async updateAttachment(uid) {
              console.log('update');
              let result = await axios.get(`/attachment/${uid}`);
              let attachment = result.data.data;
              if (attachment.processed) {
                console.log('update - processed is true');
                // update attachment
                _.assign(_.find(this.attachments, { uid: uid }), attachment);
                // then remove interval
                clearInterval(this.intervals[uid]);
              }
            },
            remove(uid) {
              if(confirm('Are you sure you want to remove this attachment?')) {
                axios.delete(`/attachment/${uid}`)
                  .then(() => {
                    // remove any intervals
                    // remove attachment
                    this.attachments = this.attachments.filter((attachment) => {
                      return attachment.uid !== uid;
                    });
                  }, () => {
                    this.failed = true;
                  });
              }
            }
        },
        mounted() {
            this.attachments = JSON.parse(this.resource);


            window.onbeforeunload = () => {
                if(this.uploading && !this.uploadingComplete && !this.failed) {
                    return 'Are you sure you want to navigate away. Your attachment is currently uploading.'
                }
            }
        }
    }
</script>
