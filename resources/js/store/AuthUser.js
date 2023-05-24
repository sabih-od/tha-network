export default {
    namespaced: true,
    state() {
        return {
            loading: false,
            data: {}
        }
    },
    getters: {

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
