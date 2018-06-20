<template>
	<li class="media mt-4 mb-4" :id="`comment-${comment.id}`">
		<img class="mr-3" :src="comment.author.avatar" :alt="comment.author.name">
		<div class="media-body">
			
			<template v-if="editing">
				<comment-edit 
					:comment="comment"
					:is-current-user-jockey="isCurrentUserJockey"
					:recipient-id="recipientId"
				/>
			</template>

			<template v-else>
				<p class="mb-2">
					<template v-if="comment.author.coach">
						Coach<br>
					</template>

					<template v-if="comment.private">
						Private<br>
					</template>

					<template v-if="!comment.read">
						Unread<br>
					</template>

					<template v-if="comment.attachment">
						Has {{ comment.attachment.filetype }} attachment {{ comment.attachment.filename }}<br>
						
						<attachment-thumbnail
							:attachment="comment.attachment"
						></attachment-thumbnail>
						<br>
					</template>

					<strong>{{ comment.author.name }}</strong>
					{{ comment.created_at }}
				</p>

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
	import AttachmentThumbnail from '../Attachments/AttachmentThumbnail';
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
			},
			isCurrentUserJockey: {
				required: true,
				type: String
			},
			recipientId: {
				required: false,
				type: String
			}
		},
		components: {
			CommentEdit,
			AttachmentThumbnail
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