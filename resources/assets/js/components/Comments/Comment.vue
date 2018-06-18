<template>
	<li class="media mt-4 mb-4" :id="`comment-${comment.id}`">
		<img class="mr-3" :src="comment.author.avatar" :alt="comment.author.name">
		<div class="media-body">
			<p class="mb-2">
				<strong>{{ comment.author.name }}</strong>
				{{ comment.created_at }}
			</p>

			<template v-if="editing">
				<comment-edit :comment="comment" />
			</template>

			<template v-else>
				<p>{{ comment.body }}</p>
			</template>

			<ul v-if="comment.owner && !editing">
				<li class="list-inline-item">
					<a href="#" @click.prevent="editing = true">Edit</a>
				</li>
				<li class="list-inline-item">
					<a href="#" @click.prevent="destroy">Delete</a>
				</li>
			</ul>
		</div>
	</li>
</template>

<script>
	import CommentEdit from './CommentEdit';
	import bus from '../../bus';
	
	export default {
		data() {
			return {
				editing: false
			}
		},
		props: {
			comment: {
				required: true,
				type: Object
			}
		},
		components: {
			CommentEdit
		},
		mounted() {
			bus.$on('comment:edit-cancelled', (comment) => {
				if(comment.id === this.comment.id) {
					this.editing = false;
				}		
			});
		},
		methods: {
			async destroy() {
				if(confirm('Are you sure you want to delete this comment?')) {
					await axios.delete(`/comment/${this.comment.id}`);

					bus.$emit('comment:deleted', this.comment);
				}
			}
		}
	}
</script>