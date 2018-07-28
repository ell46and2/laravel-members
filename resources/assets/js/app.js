
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

Vue.component('comments', require('./components/Comments/Comments.vue'));

Vue.component('autocomplete', require('./components/Search/Autocomplete.vue'));

Vue.component('add-feedback', require('./components/Activity/AddFeedback.vue'));

Vue.component('message-create', require('./components/Message/MessageCreate.vue'));


/*
    Notifications
*/
Vue.component('notifications', require('./components/Notifications/Notifications.vue'));
Vue.component('notification-badge', require('./components/Notifications/NotificationBadge.vue'));

/*
    Attachments
 */
Vue.component('attachment-upload', require('./components/Attachments/AttachmentUpload.vue'));
Vue.component('video-player', require('./components/Attachments/VideoPlayer.vue'));
Vue.component('attachment-modal', require('./components/Attachments/AttachmentModal.vue'));


/*
    Forms
*/
Vue.component('users-selection', require('./components/Forms/UsersSelection.vue'));
Vue.component('coaches-selection', require('./components/Forms/CoachesSelection.vue'));
Vue.component('datepicker-component', require('./components/Forms/DatepickerComponent.vue'));
Vue.component('timepicker-component', require('./components/Forms/TimepickerComponent.vue'));
Vue.component('location-selection', require('./components/Forms/LocationSelection.vue'));
Vue.component('location-name-input', require('./components/Forms/LocationNameInput.vue'));
Vue.component('country-select', require('./components/Forms/CountrySelect.vue'));
Vue.component('county-select', require('./components/Forms/CountySelect.vue'));
Vue.component('range-slider-skills', require('./components/Forms/RangeSliderSkills.vue'));
Vue.component('range-slider-pdp', require('./components/Forms/RangeSliderPdp.vue'));
Vue.component('confirmation', require('./components/Forms/Confirmation.vue'));
Vue.component('coach-assign', require('./components/Assign/CoachAssign.vue'));
Vue.component('jockey-assign', require('./components/Assign/JockeyAssign.vue')); 


/*
    Racing Excellence
*/
Vue.component('racing-excellence-results', require('./components/RacingExcellence/RacingExcellenceResults.vue'));
Vue.component('assign-coach-to-race', require('./components/RacingExcellence/AssignCoachToRace.vue')); 

const app = new Vue({
    el: '#app',
    mounted() {
    	var invoiceableCheckboxes = document.getElementsByClassName('js-invoiceable-checkbox');

    	var selectAll = document.getElementsByClassName('js-select-all')[0];
    	var unselectAll = document.getElementsByClassName('js-unselect-all')[0];

    	if(selectAll && unselectAll) {
    		selectAll.addEventListener("click", function() {
				selectToggle(true)
			});

			unselectAll.addEventListener("click", function() {
				selectToggle(false)
			});
    	}
		
		function selectToggle(bool) {
			for(var i = 0; i < invoiceableCheckboxes.length; i++) {
			    invoiceableCheckboxes[i].checked = bool;   
			}	
		}


		/* */
		var app = document.querySelector('.js-app');
        var body = document.querySelector('body');

        /* Sidebar nav elements */
        var navToggles = document.querySelectorAll('.js-nav-toggle');
        var burger = document.querySelector('.js-nav-toggle--burger');
        var nav = document.querySelector('.js-site-nav');

        /* Notification panel elements */
        var notificationPanelToggles = document.querySelectorAll('.js-notification-panel-toggle');
        var notificationPanel = document.querySelector('.js-notification-panel');
        var headerNotificationPanelToggle = document.querySelector('.js-notification-panel-toggle--site-head');

        // On click of a nav toggle element
        navToggles.forEach(function(item) {
            item.addEventListener('click', function() {
                app.classList.toggle('is-active--left');
                nav.classList.toggle('is-active');
                burger.classList.toggle('is-active');
                body.classList.toggle('is-no-scroll');
            });
        });

        /* On click of a notification panel toggle */
        notificationPanelToggles.forEach(function(item) {
            item.addEventListener('click', function() {
                app.classList.toggle('is-active--right');
                notificationPanel.classList.toggle('is-active');
                headerNotificationPanelToggle.classList.toggle('is-active');
                body.classList.toggle('is-no-scroll');
            });
        });
    }
});
