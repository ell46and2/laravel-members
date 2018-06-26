<template>
	<div class="modal modal-open fade show" id="exampleModalCenter" tabindex="-1" role="dialog" style="display: block; padding-right: 15px;" v-if="show">
    	<div class="modal-dialog modal-dialog-centered" role="document">
    		<div class="modal-content">
      			<div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
			        <button type="button" class="close" aria-label="Close" @click="close">
			          <span aria-hidden="true">&times;</span>
			        </button>
	      		</div>
		      	<div class="modal-body">
		      		<template v-if="attachment.filetype === 'video'">
		      			<video-player
							:video-uid="attachment.uid"
							:video-url="attachment.video_url"
							:thumbnail-url="attachment.thumbnail"
						></video-player>
		      		</template>	         	
		      	</div>
    		</div>
  		</div>
	</div>
</template>

<script>
	import bus from '../../bus';
	import VideoPlayer from './VideoPlayer';

	export default {
		data() {
			return {
				show: false,
				attachment: null,
			}			
		},
		components: {
			VideoPlayer
		},
		mounted() {
			bus.$on('modal:open', this.open);
			// listen for an emit that will be the attachments id
			// then send axios get for the attachments resource
			// work out if its a video or image
		},
		methods: {
			async open(id) {
				let attachment = await axios.get(`/attachment/${id}`);
				this.attachment = attachment.data.data;
				this.show = true;
			},
			close() {
				this.attachment = null;
				this.show = false;
			}
		}
	}
</script>