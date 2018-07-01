<template>
	<!-- <div class="row justify-content-center"> -->
        <!-- <div class="col-md-8"> -->
            <div class="card">
				<user-select 
					v-for="user in users" 
					:user="user" 
					:key="user.id"
					v-on:userSelected="handleSelected"
				></user-select>
            </div>
        <!-- </div> -->
    <!-- </div> -->
</template>

<script>
	import UserSelect from './UserSelect';
	import bus from '../../bus';

	export default {
		components: {
			UserSelect
		},
		data() {
			return {
				users: null,
				selectedIds: [],
			}
		},
		props: {
			resource: {
				required: false,
				type: String
			},
			group: {
				default: false
			},
			old: {
				required: false,
				default: null
			}
			// endpoint - base url to post to for removing and adding i.e activity/1/jockey // methods create and destroy
		},
		mounted() {
			if(this.resource) {
				this.users = JSON.parse(this.resource);

				this.users.forEach( (user) => {
					if(user.selected) {
						this.selectedIds.push(user.id);
					}
				});

				let old = JSON.parse(this.old);
				console.log('old user select', old);
				if(old) {
					Object.keys(old).forEach((id) => {
						id = Number(id);
						this.selectedIds.push(id);
						let user = _.find(this.users, { id: id });
						console.log('user', user);
						user.selected = true;
					});
				}
			}			
			// on remove send delete request - update user in users
			// on select if edit - update user in users
			
			bus.$on('coach:selected', this.getCoachesJockeys);
		},
		methods: {
			handleSelected(id) {
				console.log('selected', id);
				let user = _.find(this.users, { id: id });
				if(this.group) {
					this.handleSelectedGroup(id, user);
				} else {
					this.handleSelectedSingle(id);
				}
			},
			handleSelectedSingle(id) {
				if(id !== this.selectedIds[0]) {
					this.selectedIds[0] = id;
					this.unselectAllExcept(id)
				}
			},
			handleSelectedGroup(id, user) {
				if(this.existsInSelectedIds(id)) {
					this.removeFromSelectedIds(id);
					user.selected = false;
				} else {
					this.addToSelectedIds(id);
					user.selected = true;
				}
			},
			existsInSelectedIds(id) {
				return this.selectedIds.includes(id);
			},
			addToSelectedIds(id) {
				this.selectedIds.push(id);
			},
			removeFromSelectedIds(id) {
				this.selectedIds = this.selectedIds.filter(item => item !== id);
			},
			unselectAllExcept(id) {
				this.users.forEach((user) => {
					if(user.id === id) {
						user.selected = true;
					} else {
						user.selected = false;
					}
				});
			},
			async getCoachesJockeys(coachId) {
				let jockeys = await axios.get(`/admin/jockey-resource/${coachId}`);

				this.users = jockeys.data.data;
			}
		}
	}
</script>