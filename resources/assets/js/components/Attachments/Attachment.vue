<template>
	<img                        
        :src="attachment.thumbnail" 
        alt="attachment thumbnail"
        class="attachment-upload__attachment__image"  
        @click="modalOpen"                       
    >
</template>

<script>
	import bus from '../../bus';

	export default {
		props: {
			attachment: {
				required: true,
				type: Object
			}
		},
		methods: {
			modalOpen() {
              if(this.attachment.processed) {
                bus.$emit('modal:open', this.attachment.uid);
              }              
            },
		}
	}
</script>
