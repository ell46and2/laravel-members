<template>
	<div class="five-col-grid__col">
	    <div class="user-card user-card--has-meta">
	        <div class="[ user-card__avatar ] [ avatar ]">
	            <div class="avatar__image" :style="`background-image:url('${user.avatar}');`"></div>
	        </div>
	        <div class="user-card__main">
	            <div class="user-card__name">{{ user.name }}</div>
	            <div class="user-card__meta">
	                {{ formattedRole }} <button v-if="canRemoveUser" class="[ button button--text ] [ user-card__button ]" type="button" @click.prevent="remove">Remove</button>
	            </div>
	        </div>
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
			canRemoveUser: {
				required: false,
				default: true
			}
		},
		methods: {
			remove() {
				this.$emit('remove', this.user.id);
			}
		},
		computed: {
			formattedRole() {
				return this.user.role.charAt(0).toUpperCase() + this.user.role.slice(1);
			}
		}
	}
</script>