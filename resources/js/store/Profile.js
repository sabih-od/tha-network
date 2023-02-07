export default {
    namespaced: true,
    state() {
        return {
            is_another: false,
            data: {}
        }
    },
    mutations: {
        setIsAnother(state, payload) {
            state.is_another = payload
        },
        setProfile(state, payload) {
            if (payload) {
                state.data = {
                    ...state.data,
                    ...payload
                }
            }
        }
    }
}
