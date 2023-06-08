export default {
    namespaced: true,
    state() {
        return {
            loading: false,
            data: {}
        }
    },
    getters: {
        isAdmin(state) {
            return state.data?.role_id && state.data?.role_id === 1
        }
    },
    mutations: {
        setData(state, payload) {
            state.data = payload
        },
        setLoading(state, payload) {
            state.loading = payload
        }
    }
}
