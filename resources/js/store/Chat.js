import moment from "moment";
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    namespaced: true,
    state() {
        return {
            active_user_id: null
        }
    },
    mutations: {
        setActiveUserId(state, payload) {
            state.active_user_id = payload
        }
    },
    getters: {
        activeUserId(state) {
            return state.active_user_id;
        }
    },
}
