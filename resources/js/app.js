require('./bootstrap');

import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/inertia-vue3'
import Toast, {POSITION} from "vue-toastification";
import mitt from 'mitt'
import store from './store/index'

import "vue-toastification/dist/index.css";

const emitter = mitt();

createInertiaApp({
    resolve: name => import(`./Pages/${name}`),
    setup({el, App, props, plugin}) {

        const VueApp = createApp({render: () => h(App, props)});

        VueApp.config.globalProperties.$route = route;
        VueApp.config.globalProperties.$emitter = emitter;

        VueApp.use(plugin)
            .use(store)
            .use(Toast, {
                position: POSITION.BOTTOM_RIGHT
            })
            .mount(el);

        /*createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)*/
    },
})

//  app.config.globalProperties.$route = route
