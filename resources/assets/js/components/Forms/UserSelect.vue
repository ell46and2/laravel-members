<template>
	<!-- <div class="media mt-4 mb-4" :id="user.id" @click.prevent="handleSelect">
		<input type="checkbox" :name="`jockeys[${user.id}]`" :id="`jockeys[${user.id}]`" v-model="user.selected">
		<label 
			:for="`jockeys[${user.id}]`"
			class="media-body" 
			
		>
			{{ user.selected ? 'selected' : 'not selected' }}
			<img class="mr-3" :src="user.avatar" :alt="user.name">
			<p>{{ user.name }}</p>
		</label> -->
<!-- 		<div>
			<a href="#" v-if="canRemove && selected">remove</a>
		</div> -->
	<!-- </div> -->
	<div class="col-md-3">
		<div class="[ user-checkbox ]" :id="user.id" @click.prevent="handleSelect">
	        <input 
	        class="[ user-checkbox__input ] [ sr-only ]" 
	        type="checkbox" 
	        :name="`jockeys[${user.id}]`" 
	        :id="`jockeys[${user.id}]`" 
	        v-model="user.selected"
	        />
	        <label class="user-checkbox__label" :for="`jockeys[${user.id}]`">
	            <div class="[ user-checkbox__avatar ] [ avatar ]">
	                <div class="avatar__image" :style="`background-image:url('${user.avatar}');`" :alt="user.name"></div>
	            </div>
	            <span class="user-checkbox__name">{{ user.name }}</span>
	        </label>
	    </div>
	</div>
</template>


<script>
	export default {
		props: {
			user: {
				required: true,
				type: Object
			},
			// canRemove: {
			// 	default: false,
			// 	type: Boolean
			// }
		},
		mounted() {
			// this.selected = this.user.selected;
		},
		methods: {
			handleSelect() {
				this.$emit('userSelected', this.user.id);

				/*
				// this.selected = !this.selected;
				// if this selected 
					// if !this.canRemove - uncheck checkbox - emit unselected, id
					if(this.user.selected && !this.canRemove) {
						this.$emit('unselected', this.user.id);
					} else if(!this.user.selected) {
						this.$emit('selected', this.user.id);
					}
				// else check checkbox - emit selected, id
				*/
			},
			remove() {
				// uncheck checkbox - emit removed, id
			}
		}
	}
</script>