import queues from "./mixins/queues";

require('./bootstrap');

import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/inertia-vue3'
import Toast, {POSITION} from "vue-toastification";
import mitt from 'mitt'
import store from './store/index'
import Echo from "laravel-echo"
import {InertiaProgress} from '@inertiajs/progress'

import "vue-toastification/dist/index.css";
import notification_events from "./mixins/notification_events";

window.Pusher = require('pusher-js');

const emitter = mitt();

InertiaProgress.init()
createInertiaApp({
    resolve: name => import(`./Pages/${name}`),
    setup({el, App, props, plugin}) {

        const VueApp = createApp({
            mixins: [notification_events],
            render: () => h(App, props)
        });

        VueApp.config.globalProperties.$route = route;
        VueApp.config.globalProperties.$emitter = emitter;
        VueApp.config.globalProperties.$echo = new Echo({
            broadcaster: 'pusher',
            authEndpoint: process.env.MIX_BASE_URL + "broadcasting/auth",
            key: process.env.MIX_PUSHER_APP_KEY,
            wsHost: window.location.hostname,
            wssPort: 6001,
            wsPort: 6001,
            encrypted: process.env.NODE_ENV === 'production',
            forceTLS: process.env.NODE_ENV === 'production',
            disableStats: true,
            enabledTransports: ['ws', 'wss']
        })

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
