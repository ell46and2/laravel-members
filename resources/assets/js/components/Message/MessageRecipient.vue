<template>

    <div class="five-col-grid__col" @click.prevent="handleSelect">
        <div class="user-card user-card--has-meta">
            <div class="[ user-card__avatar ] [ avatar ]">
                <div class="avatar__image" :style="`background-image:url('${recipient.avatar}');`"></div>
            </div>
            <div class="user-card__main">
                <div class="user-card__name">{{ recipient.name }}</div>
                <div class="user-card__meta">
                    {{ recipient.role }} <button class="[ button button--text ] [ user-card__button ] pl-0" type="button" @click.prevent="remove">Remove</button>
                </div>
            </div>
        </div>
    </div>
	    



	<!-- <div class="media mt-4 mb-4" >
		<div @click.prevent="handleSelect">
			<input type="checkbox" :name="inputName" :id="inputName" v-model="selected">
			<label 
				:for="inputName"
				class="media-body" 
				
			>
				<img class="mr-3" :src="recipient.avatar" :alt="recipient.name">
				<p>{{ recipient.name }}</p>
				<p>{{ recipient.role }}</p>
			</label>
		</div>
		<div>
			<a href="#" @click.prevent="remove">remove</a>
		</div>
	</div> -->

</template>

<script>

	export default {
		data() {
			return {
				selected: true
			}
		},
		props: {
			recipient: {
				required: true,
				type: Object
			}
		},
		computed: {
			inputName() {
				return `recipients[${this.recipient.id}]`;
			}
		},
		methods: {
			handleSelect() {
				// to stop user from unchecking checkbox.
				return;
			},
			remove() {
				this.$emit('remove', this.recipient.id);
			}
		}
	}
</script>
