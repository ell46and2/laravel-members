<template>
	<div>
		<division
			division-num="1"
			:jockeys-resource="jockeysResource"
			:is-edit="isEdit"
			:external-avatar-url="externalAvatarUrl"
			:old="oldData.div1"
			:current-participants="current.div1.participants"
			:current-id="current.div1.id"
			ref="div1"
		></division>

		<division
			v-if="numDivisions === 2"
			division-num="2"
			:jockeys-resource="jockeysResource"
			:is-edit="isEdit"
			:external-avatar-url="externalAvatarUrl"
			:old="oldData.div2"
			:current-participants="current.div2.participants"
			:current-id="current.div2.id"
			ref="div2"
		></division>

		<a 
			href="#"
			class="btn btn-primary btn-block"
			@click.prevent="toggleSecondDivision"
		>{{ toggleButtonCopy }}</a>
	</div>
</template>

<script>
	import Division from './Division';

	export default {
		data() {
			return {
				numDivisions: 1,
				oldData: {
					div1: null,
					div2: null
				},
				current: {
					div1: {
						participants: null,
						id: null,
					},
					div2: {
						participants: null,
						id: null,
					},
				},
				raceId: null
			}
		},
		props: {
			jockeysResource: {
				type: String,
				required: true,
			},
			isEdit: {
				type: Boolean,
				default: false
			},
			externalAvatarUrl: {
				required: true,
				type: String
			},
			old: {
				required: false,
				default: null
			},
			raceResource: {
				type: String,
				required: false,
				default: ''
			}
		},
		components: {
			Division
		},
		mounted() {
			// console.log('old', JSON.parse(this.old)['1']);
		
				let old = JSON.parse(this.old);
				if(old) {
					if(old[1]) {
						this.oldData.div1 = old[1];
					}
					if(old[2]) {
						this.oldData.div2 = old[2];
						this.numDivisions = 2;
					}
				}
				
			
				if(this.raceResource) {
					let raceResource = JSON.parse(this.raceResource);

					this.raceId = raceResource.id;

					if(raceResource.divisions[0]) {
						this.current.div1.id = raceResource.divisions[0].id;
						this.current.div1.participants = raceResource.divisions[0].participants;
					}
					if(raceResource.divisions[1]) {
						this.current.div2.id = raceResource.divisions[1].id;
						this.current.div2.participants = raceResource.divisions[1].participants;
						this.numDivisions = 2;
					}
				}
		},
		methods: {
			toggleSecondDivision() {
				if(this.numDivisions === 1) {
					this.numDivisions = 2;
				} else {
					// All participants need to be removed from div2 first				
					if(this.$refs.div2.participants.length === 0) {
						this.numDivisions = 1;
					} else {
						alert('Division Two can only be removed once all jockeys have been removed from the division first!');
					}				
				}
				
			}
		},
		computed: {
			toggleButtonCopy() {
				if(this.numDivisions === 1) {
					return 'Add 2nd Division'
				}
				return 'Remove 2nd Division'
			}
		}
	}
</script>