import _ from "lodash";

export default {
    namespaced: true,
    state() {
        return {
            chat_active_channel: null,
            channels_notifications_counts: [],
            channels: [],
            loading: false,
            next_page_url: null
        }
    },
    getters: {
        getChannelNotificationsCount(state) {
            return (channel_id) => {
                const notification_data = _.find(state.channels_notifications_counts, {id: channel_id})
                if (notification_data)
                    return notification_data.count

                const channel = _.find(state.channels, {id: channel_id})
                if (channel)
                    return channel?.notifications_count ?? 0

                return 0
            }
        }
    },
    mutations: {
        setChatActiveChannel(state, payload) {
            state.chat_active_channel = payload
        },
        setChannels(state, payload) {
            state.channels = payload
        },
        setLoading(state, payload) {
            state.loading = payload
        },
        setNextPageUrl(state, payload) {
            state.next_page_url = payload
        },
        setChannelsNotificationsCounts(state, payload) {
            let notification_data = _.find(state.channels_notifications_counts, {id: payload.id})
            if (notification_data) {
                notification_data = _.cloneDeep(notification_data)
                notification_data['count'] = payload.count
                _.set(
                    state.channels_notifications_counts,
                    _.findIndex(state.channels_notifications_counts, {id: payload.id}),
                    notification_data
                )
            } else {
                if (_.find(state.channels, {id: payload.id})) {
                    state.channels_notifications_counts.push({
                        id: payload.id,
                        count: payload.count
                    })
                }
            }
        }
    },
    actions: {
        loadChatListing({state, commit, rootGetters, dispatch}, url = null) {
            if (state.loading) return;

            let isLoadMore = !!(url)
            url = url ?? rootGetters['Utils/baseUrl']

            commit('setLoading', true)
            dispatch('HttpUtils/getReq', {
                url: url,
                only: ['channels'],
            }, {root: true}).then(res => {
                commit('setNextPageUrl', res?.channels?.next_page_url ?? null)
                let data = []
                if (isLoadMore)
                    data = [
                        ...state.channels,
                        ...(res?.channels?.data ?? [])
                    ]
                else {
                    data = res?.channels?.data ?? []
                    commit('setChatActiveChannel', null)
                }
                commit('setChannels', data)
            }).finally(() => {
                commit('setLoading', false)
            })
        }
    }
}
