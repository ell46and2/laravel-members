
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('attachment-upload', require('./components/Attachments/AttachmentUpload.vue'));

Vue.component('video-player', require('./components/Attachments/VideoPlayer.vue'));

Vue.component('attachment-modal', require('./components/Attachments/AttachmentModal.vue'));

Vue.component('comments', require('./components/Comments/Comments.vue'));

Vue.component('racing-excellence-results', require('./components/RacingExcellence/RacingExcellenceResults.vue'));

Vue.component('notifications', require('./components/Notifications/Notifications.vue'));

Vue.component('autocomplete', require('./components/Search/Autocomplete.vue'));

Vue.component('users-selection', require('./components/Forms/UsersSelection.vue'));

Vue.component('coaches-selection', require('./components/Forms/CoachesSelection.vue'));

Vue.component('datepicker-component', require('./components/Forms/DatepickerComponent.vue'));

Vue.component('timepicker-component', require('./components/Forms/TimepickerComponent.vue'));

Vue.component('add-feedback', require('./components/Activity/AddFeedback.vue'));

Vue.component('divisions', require('./components/RacingExcellenceDivisions/Divisions.vue'));

Vue.component('location-selection', require('./components/Forms/LocationSelection.vue'));

Vue.component('location-name-input', require('./components/Forms/LocationNameInput.vue'));

Vue.component('country-select', require('./components/Forms/CountrySelect.vue'));

Vue.component('county-select', require('./components/Forms/CountySelect.vue'));

Vue.component('message-create', require('./components/Message/MessageCreate.vue'));

const app = new Vue({
    el: '#app'
});
