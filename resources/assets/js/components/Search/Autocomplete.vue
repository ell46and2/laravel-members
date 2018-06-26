<template>
	<div class="autocomplete">
    	<input
    		class="autocomplete__input"
    		type="text"
			v-model="search"
			@input="onChange"
			@keyup.down="onArrowDown"
    		@keyup.up="onArrowUp"
    		@keyup.enter="onEnter"
    	/>
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
      		>
      			{{ result.name }}
      		</li>
    	</ul>
  	</div>
</template>

<script>
	
	export default {
		props: {
			resource: {
				type: String,
				required: true,
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
		mounted() {
			this.data = JSON.parse(this.resource);

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
					console.log(user.name);
					return user.name.toLowerCase().indexOf(this.search.toLowerCase()) > -1;
				});
				console.log(this.results);
			},
			setResult(result) {
				this.search = result.name;
				this.setResultId(result.id);
				this.isOpen = false;
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
				console.log('counter', this.arrowCounter);
			},
			onArrowUp() {
				if(this.arrowCounter > 0) {
					this.arrowCounter = this.arrowCounter - 1;
				}
				console.log('counter', this.arrowCounter);
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
      		}
    
			// Do we have an add button that will emit the id to the parent?
			// We can disable the button until this.resultId doesn't equal null
		}
	}
</script>