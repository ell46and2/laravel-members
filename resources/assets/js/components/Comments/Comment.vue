<template>
	<!-- <li class="media mt-4 mb-4" :id="`comment-${comment.id}`">
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
						
						<attachment
							:attachment="comment.attachment"
						></attachment>
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
	</li> -->

<div>
	<template v-if="editing">
				<comment-edit 
					:comment="comment"
					:is-current-user-jockey="isCurrentUserJockey"
					:recipient-id="recipientId"
				/>
	</template>

	<template v-else>
		<div :class="`comments__row comments__row--${position} comments__row--${theme}`">
		    <div class="comments__comment">
		        <div class="[ comments__avatar ] [ avatar ]">
		            <div class="avatar__image" :style="`background-image:url('${comment.author.avatar}');`"></div>
		        </div>
		        <div class="[ comments__inner ] [ flow-vertical--1 ]">

		            <p v-if="comment.body">{{ comment.body }}</p>

		            <template v-if="comment.attachment">
						<!-- Has {{ comment.attachment.filetype }} attachment {{ comment.attachment.filename }}<br> -->
						
						<attachment
							:attachment="comment.attachment"
						></attachment>
						<br>
					</template>

		            <div class="comments__comment-meta">
		                <span class="comments__comment-author">{{ comment.author.name }}</span>
		                <span class="comments__comment-time">{{ comment.created_at }}</span>
		            </div>

		            <ul v-if="comment.owner && !editing" class="comments__comment-options">
		                <li class="comments__comment-options-item">
		                    <a 
		                    	class="comments__comment-options-link" 
		                    	href="#" 
		                    	@click.prevent="editing = true"
		                    >Edit</a>
		                </li>
		                <li class="comments__comment-options-item">
		                    <a 
		                    	class="comments__comment-options-link" 
		                    	href="#"
		                    	@click.prevent="destroy"
		                   	>Delete</a>
		                </li>
		            </ul>
		        </div>
		    </div>
		</div>
	</template>
</div>
</template>

<script>
	import CommentEdit from './CommentEdit';
	import Attachment from '../Attachments/Attachment';
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
			Attachment
		},
		mounted() {
			bus.$on('comment:edit-cancelled', (comment) => {
				if(comment.id === this.comment.id) {
					this.editing = false;
				}		
			});
		},
		computed: {
			position() {
				return this.comment.isCoach ? 'left' : 'right';
			},
			theme() {
				return this.comment.isCoach ? 'light' : 'dark';
			}
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