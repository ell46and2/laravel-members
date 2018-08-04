<template>
	<!-- <img                        
        :src="attachment.thumbnail" 
        alt="attachment thumbnail"
        class="attachment-upload__attachment__image"  
        @click.prevent="modalOpen"                       
    > -->
    <a 
    	class="image-thumbnail" 
    	:style="`background-image:url('${attachment.thumbnail}');`" 
    	href="#"
    	@click.prevent="modalOpen"
    >
        <span class="image-thumbnail__magnify-icon">
            <icon-magnify></icon-magnify>
        </span>
    </a>
</template>

<script>
	import IconMagnify from './IconMagnify';
	import bus from '../../bus';

	export default {
		props: {
			attachment: {
				required: true,
				type: Object
			}
		},
		components: {
			IconMagnify
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
