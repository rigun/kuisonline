


require('./bootstrap');

window.Vue = require('vue');
import Buefy from 'buefy';
import router from './routes.js';
import Vuetify from 'vuetify';
window.VueRouter = require('vue-router').default;
import AppLayout from './components/appLayout.vue';

Vue.use(Buefy);
Vue.use(Vuetify);

Vue.config.productionTip = false;

new Vue(
    Vue.util.extend(
        {router},
        AppLayout,
    )
).$mount('#mainLayout');

