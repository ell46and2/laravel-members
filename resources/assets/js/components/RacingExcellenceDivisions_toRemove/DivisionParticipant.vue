<template>
	<div class="media mt-4 mb-4" >
		<div @click.prevent="handleSelect">
			<input type="checkbox" :name="inputName" :id="inputName" v-model="selected">
			<label 
				:for="inputName"
				class="media-body" 
				
			>
				<img class="mr-3" :src="participant.avatar" :alt="participant.name">
				<p>{{ participant.name }}</p>
			</label>
		</div>
		<div>
			<a href="#" @click.prevent="remove">remove</a>
		</div>
	</div>
</template>

<script>

	export default {
		data() {
			return {
				selected: true
			}
		},
		props: {
			divisionNum: {
				required: true
			},
			participant: {
				required: true,
				type: Object
			}
		},
		computed: {
			inputName() {
				let id = this.participant.id !== null ? this.participant.id : this.participant.name;
				let participantType = isNaN(this.participant.id) ? 'external_participants' : 'jockeys';
				return `divisions[${this.divisionNum}][${participantType}][${id}]`;
			}
		},
		methods: {
			handleSelect() {
				// to stop user from unchecking checkbox.
				return;
			},
			remove() {
				this.$emit('remove', this.participant.id);
			}
		}
	}
</script>
