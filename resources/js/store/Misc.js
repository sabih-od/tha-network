import {usePage} from "@inertiajs/inertia-vue3";

export default {
    namespaced: true,
    state() {
        return {
            has_logged_out: false,
            is_newly_registered: false
        }
    },
    mutations: {
        setHasLoggedOut(state, payload) {
            state.has_logged_out = payload
        },
        setIsNewlyRegistered(state, payload) {
            state.is_newly_registered = payload
        },
    },
    actions: {
        // updateHasLoggedOut({dispatch, commit, state}) {
        //     commit('setHasLoggedOut', !state.has_logged_out);
        // }
    },
    getters: {
        hasLoggedOut(state) {
            return state.has_logged_out;
        },
        isNewlyRegistered(state) {
            return state.is_newly_registered;
        }
    },
}
