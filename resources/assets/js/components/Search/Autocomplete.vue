<template>
	<div class="autocomplete">
		<div class="form-group form-group--has-icon">
	    	<input
	    		class="autocomplete__input [ form-control form-control--has-icon ]"
	    		type="text"
				v-model="search"
				@input="onChange"
				@keyup.down="onArrowDown"
	    		@keyup.up="onArrowUp"
	    		@keydown.enter.prevent="onEnter"
	    		:placeholder="placeholder"
	    	/>
	    	<span class="form-group__input-icon" aria-hidden="true" role="presentation">
                <icon-binoculars></icon-binoculars>
            </span>
		</div>


    	<!-- disable until resultId  - onclick emit to parent the id -->
    	<!-- <button 
    		class="btn btn-primary" 
    		:disabled="resultId === null"
    		@click.prevent="submit"
    	>+</button> -->

    	<ul 
    		class="autocomplete__results"
    		v-show="isOpen"
    	>
      		<li
				v-for="(result, i) in results"
				:key="i"
      			class="autocomplete__result"
      			@click="setResult(result)"
      			:class="{ 'is-active': i === arrowCounter }"
      			style="display:flex; justify-content: space-between;"
      		>
      			{{ result.name }}
      			<button class="button button--primary" type="button">{{ btnName }}</button>
      		</li>
    	</ul>
  	</div>
</template>

<script>
	import IconBinoculars from './IconBinoculars';
	
	export default {
		props: {
			resource: {
				required: true,
			},
			excludeIds: {
				required: false,
				default() {
					return [];
				}
			},
			placeholder: {
				required: false
			},
			btnName: {
				required: false,
				default: 'Select'
			}
		},
		data() {
			return {
				search: '',
				resultId: null,
				results: [],
				isOpen: false,
				data: [],
				arrowCounter: -1,
			}
		},
		components: {
			IconBinoculars
		},
		mounted() {
			if(typeof this.resource === 'string') {
				this.data = JSON.parse(this.resource);
			} else {
				this.data = this.resource;
			}
			

			document.addEventListener('click', this.handleClickOutside);
		},
		destroyed() {
		    document.removeEventListener('click', this.handleClickOutside);
		},
		methods: {
			onChange() {
				this.isOpen = true;
				this.filterResults();
				this.clearResultId();
			},
			filterResults() {
				this.results = this.data.filter(user => {
					// console.log('user.name', user.name);
					// exclude those ids in excludeIds array.
					return user.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1 && !this.excludeIds.includes(user.id);
				});
				// console.log(this.results);
			},
			setResult(result) {
				this.search = result.name;
				this.setResultId(result.id);
				this.isOpen = false;
				this.submit();
			},
			clearResultId() {
				this.resultId = null;
				// emit to clear id from parent
			},
			setResultId(id) {
				this.resultId = id;
				// emit to sent id to parent
			},
			onArrowDown() {
				if(this.arrowCounter < this.results.length) {
					this.arrowCounter = this.arrowCounter + 1;
				}
				// console.log('counter', this.arrowCounter);
			},
			onArrowUp() {
				if(this.arrowCounter > 0) {
					this.arrowCounter = this.arrowCounter - 1;
				}
				// console.log('counter', this.arrowCounter);
			},
			onEnter() {
				if(this.arrowCounter >= 0) {
					this.setResult(this.results[this.arrowCounter]);
				}
				this.arrowCounter = -1;				
			},
			handleClickOutside(evt) {
      			if (!this.$el.contains(evt.target)) {
        			this.isOpen = false;
        			this.arrowCounter = -1;
      			}
      		},
      		submit() {
      			if(this.resultId !== null) {
      				this.$emit('searched', this.resultId);
      				this.clearResultId();
      				this.search = '';
      			}
      		}
    
			// Do we have an add button that will emit the id to the parent?
			// We can disable the button until this.resultId doesn't equal null
		}
	}
</script>