import {usePage} from "@inertiajs/inertia-vue3";

export default {
    namespaced: true,
    state() {
        return {
            has_logged_out: false
        }
    },
    mutations: {
        setHasLoggedOut(state, payload) {
            state.has_logged_out = payload
        },
    },
    actions: {
        updateHasLoggedOut({dispatch, commit, state}) {
            commit('setHasLoggedOut', !state.has_logged_out);
        }
    },
    getters: {
        hasLoggedOut(state) {
            return state.has_logged_out;
        }
    },
}
