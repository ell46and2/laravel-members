<template>
	<form @submit.prevent="patch">
		<div class="form-group">
			<textarea
				id="body"
				rows="4"
				class="form-control"
				autofocus="autofocus"
				v-model="form.body"
			></textarea>
		</div>

		<div class="form-group">
			<button type="submit" class="btn btn-primary">
				Edit				
			</button>
			<a href="#" @click.prevent="cancel" class="btn btn-link">Cancel</a>
		</div>
	</form>
</template>

<script>
	import bus from '../../bus';

	export default {
		data() {
			return {
				form: {
					body: this.comment.body
				}
			}
		},
		props: {
			comment: {
				required: true,
				type: Object
			}
		},
		methods: {
			async patch() {
				let comment = await axios.put(`/comment/${this.comment.id}`, this.form);

				bus.$emit('comment:edited', comment.data.data);

				this.cancel();
			},
			cancel() {
				bus.$emit('comment:edit-cancelled', this.comment);
			}
		}
	}
</script>