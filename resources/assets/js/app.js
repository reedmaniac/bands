
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

axios.defaults.baseURL = '/api/';

Vue.component('albums', require('./components/Albums.vue'));
Vue.component('edit-album', require('./components/EditAlbum.vue'));
Vue.component('view-album', require('./components/ViewAlbum.vue'));

Vue.component('bands', require('./components/Bands.vue'));
Vue.component('view-band', require('./components/ViewBand.vue'));
Vue.component('edit-band', require('./components/EditBand.vue'));

const app = new Vue({
    el: '#app'
});
