require('./utils/bootstrap');
require('../sass/app.scss');
// import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import JwPagination from 'jw-vue-pagination';
import VueRouter from 'vue-router'
import VueEllipseProgress from 'vue-ellipse-progress'
import Toast, {
    POSITION
} from "vue-toastification"
import "vue-toastification/dist/index.css";
import App from './layouts/App.vue';
import {
    routes
} from './utils/routes'
import VueSidebarMenu from 'vue-sidebar-menu'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'
import VueMaterial from 'vue-material'
import 'vue-material/dist/vue-material.min.css'
import 'vue-material/dist/theme/default.css'
// import Vuelidate from 'vuelidate';

import MdModalDialog from 'vue-material-modal-dialog'


import { library } from '@fortawesome/fontawesome-svg-core'
import { faHatWizard } from '@fortawesome/free-solid-svg-icons'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

window.Vue = require('vue').default;
// Vue.use(IconsPlugin)
// Vue.use(BootstrapVue)
Vue.use(VueRouter)
Vue.use(VueSidebarMenu)
Vue.use(VueMaterial)
// Vue.use(Vuelidate)
Vue.use(MdModalDialog)

Vue.use(VueEllipseProgress)
Vue.use(Toast, {
    position: POSITION.BOTTOM_LEFT,

});
Vue.component('jw-pagination', JwPagination)
const router = new VueRouter({
    mode: 'history',
    routes,

})


library.add(faHatWizard)
Vue.component('font-awesome-icon', FontAwesomeIcon)

new Vue({
    el: '#app',
    components: {
        App
    },
    router
});
