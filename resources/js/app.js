


require('./bootstrap');

window.Vue = require('vue');
import Buefy from 'buefy';
import router from './routes.js';
import Vuetify from 'vuetify';
window.VueRouter = require('vue-router').default;
import AppLayout from './components/appLayout.vue';
import Editor from '@tinymce/tinymce-vue';
import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "3327b7d72b050a2a82b5",
    cluster: "ap1",
    encrypted: true
});

Vue.use(Buefy);
Vue.use(Vuetify);
const editor = Vue.component('editor', Editor)

Vue.config.productionTip = false;

new Vue(
    Vue.util.extend(
        {router},
        AppLayout,
    )
).$mount('#mainLayout');

