<template>
	<div>
		
		<template v-if="comments.length">
			<ul class="list-unstyled">
				<comment 
					v-for="comment in comments"
					:key="comment.id"
					:comment="comment"
				/>
			</ul>
		</template>

		<add-comment 
			:endpoint="endpoint"
			:recipient-id="recipientId"
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
			}
		},
		components: {
			Comment,
			AddComment
		},
		mounted() {
			this.loadComments();
			bus.$on('comment:stored', this.prependComment);
			bus.$on('comment:edited', this.editComment);
			bus.$on('comment:deleted', this.deleteComment);
		},
		methods: {
			async loadComments() {
				let comments = await axios.get(this.endpoint);

				this.comments = comments.data.data;
			},
			editComment(comment) {
				_.assign(_.find(this.comments, { id: comment.id }), comment);
			},
			deleteComment(comment) {
				this.comments = this.comments.filter((com) => com.id !== comment.id);
			},
			async prependComment(comment) {
				this.comments.push(comment);
			},
		}
	}
</script>