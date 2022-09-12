<template>
    <div class="dropdown nav-icons">
        <a class="dropdown-toggle" type="button" id="profileDropDown" data-toggle="dropdown" aria-expanded="false">
            <i class="fal fa-bell"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="profileDropDown">
            <span v-if="notifications.length == 0" class="dropdown-item">No new messages</span>
            <Link v-else v-for="notification in notifications" class="dropdown-item" replace @click.prevent="chatWithProfile(notification.sender.id)">New message from
                <strong>
                    {{ notification.sender.profile.first_name + ' ' + notification.sender.profile.last_name }}
                </strong>
            </Link>
        </div>
    </div>
</template>

<script>
import {Link, useForm, usePage} from '@inertiajs/inertia-vue3'
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "ChatMessagesCounterButton",
    components: {
        Link
    },
    data() {
        return {
            notifications: [],
            read_notifications: [],
            channelForm: useForm({
                chat_type: 'individual',
                user_id: null
            }),
        }
    },
    computed: {
        totalCount() {
            return this.$store.state.Notification.total_count
        },
        user() {
            return usePage().props.value?.auth ?? null
        }
    },
    mounted() {
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('NewNotification', this.addNotification);

        this.fetchNotificationData();

        this.$emitter.on('request_for_notifications', this.sendNotificationData);
        this.$emitter.on('request_chat_with_profile', this.requestChatWithProfile);
    },
    methods: {
        addNotification(data) {
            this.notifications = [
                ...this.notifications,
                data
            ]
            this.$emitter.emit('unread_notifications_updated', this.notifications);
        },
        chatWithProfile(profile_id) {
            this.$store.commit('Chat/setActiveUserId', profile_id);

            this.channelForm.user_id = profile_id;
            this.channelForm
                .post(this.$route('channelStore'), {
                    replace: true,
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.form.user_id = null
                    },
                    onError: () => {
                        this.$store.dispatch('Utils/showErrorMessages')
                    }
                })
        },
        requestChatWithProfile(sender_id) {
            this.chatWithProfile(sender_id);
        },
        fetchNotificationData() {
            let url = this.$store.getters['Utils/baseUrl'];
            Inertia.get(url, {

            }, {
                replace: true,
                preserveScroll: true,
                preserveState: true,
                only: ['unread_notifications', 'read_notifications'],
                onStart: () => {
                    // this.loading = true
                },
                onSuccess: res => {
                    this.notifications = [
                        ...this.notifications,
                        ...(res.props?.unread_notifications ?? [])
                    ];
                    this.read_notifications = [
                        ...this.read_notifications,
                        ...(res.props?.read_notifications ?? [])
                    ];

                    this.$emitter.emit('unread_notifications_updated', this.notifications);
                    this.$emitter.emit('read_notifications_updated', this.read_notifications);
                    // this.next_page_url = res.props?.channels?.next_page_url ?? null
                    // if (isLoadMore)
                    //     this.channels = [
                    //         ...this.channels,
                    //         ...(res.props?.channels?.data ?? [])
                    //     ]
                    // else
                    //     this.channels = res.props?.channels?.data ?? []
                },
                onFinish: () => {
                    // this.loading = false
                    // window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                },
            })
        },
        sendNotificationData() {
            this.$emitter.emit('unread_notifications_updated', this.notifications);
            this.$emitter.emit('read_notifications_updated', this.read_notifications);
        }
    }
}
</script>

<style scoped>

</style>