<template>   
    <select 
        v-model="selected"
        class="form-control" 
        name="location_id" 
        id="location_id"
    >     
        <option :value="null" disabled>Select Location</option>
        <option 
            v-for="location in locations"
            :value="location.id"
            :selected="selected == location.id"
        >
            {{ displayName(location) }}
        </option>
        <option
            value=""
            :selected="selected == 'other'"
        >
            Other
        </option>
    </select>
</template>

<script>
    import bus from '../../bus';
    
    export default {
        data() {
            return {
                locations: null,
                selected: null
            }
        },
        props: {
            locationsData: {
                required: true,
            },
            oldLocationId: {
                required: false,
                default: null
            },
            oldLocationName: {
                required: false,
                default: null
            }
        },
        mounted() {
            this.locations = JSON.parse(this.locationsData);

            if(this.oldLocationId) {
                this.selected = Number(this.oldLocationId);
            }
            if(this.oldLocationName) {
                this.selected = '';
            }
        },
        methods: {
            displayName(location) {
                return location.name.charAt(0).toUpperCase() + location.name.slice(1);
            }
        },
        watch: {
            selected: function(id) {
                if(id === '') {
                    bus.$emit('locationName:show');
                } else {
                    bus.$emit('locationName:hide');
                }
            }
        }

    }
</script>

