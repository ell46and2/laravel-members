<template>
	<!-- <div class="row justify-content-center"> -->
        <!-- <div class="col-md-8"> -->
            <div class="card">
				<coach-select 
					v-for="user in users" 
					:user="user" 
					:key="user.id"
					v-on:userSelected="handleSelected"
					:selected-id="selectedId"
				></coach-select>
            </div>
        <!-- </div> -->
    <!-- </div> -->
</template>

<script>
	import CoachSelect from './CoachSelect';
	import bus from '../../bus';

	export default {
		components: {
			CoachSelect
		},
		data() {
			return {
				users: null,
				selectedId: null,
			}
		},
		props: {
			resource: {
				required: true,
				type: String
			},
			old: {
				required: false,
				default: null
			}
			// endpoint - base url to post to for removing and adding i.e activity/1/jockey // methods create and destroy
		},
		mounted() {	
			this.users = JSON.parse(this.resource);

			this.users.forEach( (user) => {
				if(user.selected) {
					this.selectedId = user.id;
				}
			});

			if(this.old) {
				this.handleSelected(Number(this.old));
			}
		},
		methods: {
			handleSelected(id) {
				console.log('selected', id);
				let user = _.find(this.users, { id: id });
				
				if(id !== this.selectedId) {
					this.selectedId = id;
					this.unselectAllExcept(id);

					bus.$emit('coach:selected', id);
				}			
			},
			unselectAllExcept(id) {
				this.users.forEach((user) => {
					if(user.id === id) {
						user.selected = true;
					} else {
						user.selected = false;
					}
				});
			}
		}
	}
</script>