export default {
    namespaced: true,
    state() {
        return {
            modal: null,
            modalId: 'share_post_modal',
            post: null
        }
    },
    mutations: {
        setModal(state, docEl) {
            state.modal = new bootstrap.Modal(docEl, {
                keyboard: false,
                backdrop: 'static'
            })
        },
        setPost(state, payload) {
            state.post = payload
        }
    },
    actions: {
        showModal({state, commit}, post) {
            commit('setPost', post)
            state.modal.show()
        },
        hideModal({state, commit}) {
            state.modal.hide()
            commit('setPost', null)
        }
    }
}
