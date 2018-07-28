<template>
	<div class="modal modal-open fade show" id="exampleModalCenter" tabindex="-1" role="dialog" style="display: block; padding-right: 15px;" v-if="show">
    	<div class="modal-dialog modal-dialog-centered" role="document">
    		<div class="modal-content">
      			<div class="modal-header">
			        <h5 class="modal-title" id="exampleModalLongTitle">Confirm</h5>
			        <button type="button" class="close" aria-label="Close" @click="close">
			          <span aria-hidden="true">&times;</span>
			        </button>
	      		</div>
		      	<div class="modal-body">
		      		  <p>{{ message }}</p>
		      		  <button class="btn btn-default" @click="close">Cancel</button>  
		      		  <button class="btn btn-primary" @click="confirmed">OK</button>
		      	</div>
    		</div>
  		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				event: null,
				message: null,
				show: false
			}
		},
		mounted() {
			let confirmations = document.querySelectorAll('.js-confirmation');
        	confirmations.forEach((item) => {
	            item.addEventListener('submit', this.confirmationModal, false);
	        });
		},
		methods: {
			confirmationModal(el) {
				el.preventDefault();
				this.event = el;
				this.message = this.event.target.getAttribute('data-confirm') || 'Are you sure?';
				this.show = true;
			},
			close() {
				this.reset();
			},
			reset() {
				this.show = false;
				this.event = null;
				this.message = null;
			},
			confirmed() {
				this.event.target.submit();
			}
		}
	}
</script>