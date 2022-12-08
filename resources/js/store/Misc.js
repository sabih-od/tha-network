import {usePage} from "@inertiajs/inertia-vue3";

export default {
    namespaced: true,
    state() {
        return {
            has_logged_out: false,
            is_newly_registered: false,
            people_in_my_network_flag: false,
            blocked_users_flag: false
        }
    },
    mutations: {
        setHasLoggedOut(state, payload) {
            state.has_logged_out = payload
        },
        setIsNewlyRegistered(state, payload) {
            state.is_newly_registered = payload
        },
        setPeopleInMyNetworkFlag(state, payload) {
            state.people_in_my_network_flag = payload
        },
        setBlockedUsersFlag(state, payload) {
            state.blocked_users_flag = payload
        }
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
        },
        getPeopleInMyNetworkFlag(state) {
            return state.people_in_my_network_flag;
        },
        getBlockedUsersFlag(state) {
            return state.blocked_users_flag;
        }
    },
}
