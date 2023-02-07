import {usePage} from "@inertiajs/inertia-vue3";
import _ from "lodash";
import {useToast} from "vue-toastification";

export default {
    watch: {
        auth(val, old) {
            if (old && !_.isEqual(old, val)) {
                this.$echo.leave('App.Models.User.' + old.id)
            }

            if (val && !_.isEqual(val, old)) {
                this.$echo.private('App.Models.User.' + val.id)
                    .listen('NewNotification', this.newNotification)
            }
        }
    },
    computed: {
        auth() {
            return usePage().props.value?.auth
        }
    },
    methods: {
        newNotification({body, type, id}) {
            if (type === 'channel') {
                const active_channel_id = this.$store.state.Channel.chat_active_channel
                if (!active_channel_id || (active_channel_id && active_channel_id !== id)) {
                    useToast().success(body)
                }
                this.channelNotificationCount(id)
                if (usePage().component.value === 'Chat') {
                    this.$store.dispatch('Channel/loadChatListing')
                }
            }
            this.$store.dispatch('Notification/updateTotalCount')
        },
        channelNotificationCount(id) {
            const channel_notification_count = this.$store.getters['Channel/getChannelNotificationsCount'](id)

            this.$store.commit('Channel/setChannelsNotificationsCounts', {
                id,
                count: channel_notification_count + 1
            })
        }
    }
}
