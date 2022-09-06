import {usePage} from "@inertiajs/inertia-vue3";

export default {
    namespaced: true,
    state() {
        return {
            total_count: 0,
            debounce: null
        }
    },
    mutations: {
        setTotalCount(state, payload) {
            state.total_count = payload
        },
        setDebounce(state, payload) {
            state.debounce = payload
        }
    },
    actions: {
        updateTotalCount({dispatch, commit, state}) {
            clearTimeout(state.debounce)
            commit('setDebounce', setTimeout(() => {
                dispatch('HttpUtils/getReq', {
                    url: usePage().url.value,
                    only: 'notification_count'
                }, {root: true}).then(res => {
                    commit('setTotalCount', res?.notification_count ?? 0)
                })
            }, 1000))
        }
    }
}
