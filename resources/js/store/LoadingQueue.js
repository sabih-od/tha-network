import _ from "lodash";

export default {
    namespaced: true,
    state() {
        return {
            loading: false,
            runningQueue: null,
            queues: []
        }
    },
    getters: {
        isDataInQueue: (state) => (data) => {
            return !_.isEmpty(_.find(state.queues, val => _.isEqual(val, data)))
        }
    },
    mutations: {
        setLoading(state, payload) {
            state.loading = payload
        },
        setRunningQueue(state, payload) {
            state.runningQueue = payload
        },
        addQueue(state, payload) {
            state.queues.push(payload)
        },
        removeQueue(state, payload) {
            _.remove(state.queues, function (val) {
                return _.isEqual(val, payload);
            })
        }
    },
    actions: {
        reInit({state, commit, dispatch}) {
            if (state.runningQueue) {
                commit('removeQueue', state.runningQueue)
                commit('setRunningQueue', null)
            }
            commit('setLoading', false)
            const item = _.first(state.queues)
            if (item) {
                dispatch('initQueue', item)
            }
        },
        initQueue({state, commit, getters}, data) {
            if (!data) return;
            if (state.loading) {
                if (!getters.isDataInQueue(data))
                    commit('addQueue', data)
                return
            }
            commit('setLoading', true)
            commit('setRunningQueue', data)
        }
    }
}
