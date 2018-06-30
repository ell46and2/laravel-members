<template>
	<div>
		
		<template v-if="comments.length">
			<ul class="list-unstyled">
				<comment 
					v-for="comment in comments"
					:key="comment.id"
					:comment="comment"
					:is-current-user-jockey="isCurrentUserJockey"
					:recipient-id="recipientId"
				/>
			</ul>
		</template>

		<!-- need to not show if current user isn't assigned to the activity
			i.e they are an admin or another coach (not assigned coach)
		 -->
		<add-comment 
			v-if="canUserAddComments"
			:endpoint="endpoint"
			:recipient-id="recipientId"
			:is-current-user-jockey="isCurrentUserJockey"
			v-on:stored="prependComment"
		></add-comment>
	</div>
</template>

<script>
	import Comment from './Comment';
	import AddComment from './AddComment';
	import bus from '../../bus';

	export default {
		data() {
			return {
				comments: [],
				reply: null
			}
		},
		props: {
			endpoint: {
				required: true,
				type: String
			},
			recipientId: {
				required: false,
				type: String
			},
			jockeyId: {
				required: false,
				type: String
			},
			isCurrentUserJockey: {
				required: true,
				type: String
			},
			canUserAddComments: {
				required: true,
				type: String
			}
		},
		components: {
			Comment,
			AddComment
		},
		mounted() {
			this.loadComments();
			// bus.$on('comment:stored', this.prependComment);
			bus.$on('comment:edited', this.editComment);
			bus.$on('comment:deleted', this.deleteComment);
		},
		methods: {
			async loadComments() {
				let comments = await axios.get(`${this.endpoint}?jockey=${this.jockeyId}`);

				this.comments = comments.data.data;

				// this.setCommentsAsRead()
			},
			editComment(comment) {
				_.assign(_.find(this.comments, { id: comment.id }), comment);
			},
			deleteComment(comment) {
				this.comments = this.comments.filter((com) => com.id !== comment.id);
			},
			async prependComment(comment) {
				this.comments.push(comment);

				// set interval to grab actual thumbnail
			}
		}
	}
</script>