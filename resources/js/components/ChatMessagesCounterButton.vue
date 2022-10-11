<template>
    <div class="dropdown nav-icons">
        <a class="dropdown-toggle" type="button" id="profileDropDown" data-toggle="dropdown" aria-expanded="false">
            <i class="fal fa-bell"></i>
            <span v-if="notifications.length > 0" class="button__badge"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="profileDropDown">
            <span v-if="notifications.length == 0" class="dropdown-item">No new messages</span>
            <Link v-else v-for="notification in notifications" class="dropdown-item" replace @click.prevent="chatWithProfile(notification.sender.id)">
                <strong v-if='notification.sender.id != user.id'>
                    New message from {{ notification.sender.profile.first_name + ' ' + notification.sender.profile.last_name }}
                </strong>
                <p v-else style="white-space: pre; font-size: 14px;" v-html="notification.body">

                </p>
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

        //after registration app promotion
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('AfterRegistrationAppPromotion', this.addNotification);

        //weekly notification
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('WeeklyRankingNotification', this.addNotification);

        //unable to meet weekly goal notification
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('UnableToMeetWeeklyGoal', this.addNotification);

        //no referrals for the day
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('NoReferralsForTheDay', this.addNotification);

        //referrals sent
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('ReferralSent', this.addNotification);

        //referrals completed
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('ReferralCompleted', this.addNotification);

        this.fetchNotificationData();

        this.$emitter.on('request_for_notifications', this.sendNotificationData);
        this.$emitter.on('request_chat_with_profile', this.requestChatWithProfile);
    },
    methods: {
        addNotification(data) {
            console.log('data', data);
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
                        this.channelForm.user_id = null
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
                    let map = new Map();
                    // this.notifications.forEach((item, i) => {
                    //     const value = item.sender_id;
                    //     if (map.has(value)) {
                    //         map.get(value).push(i);
                    //     } else {
                    //         map.set(value, [i])
                    //     }
                    // });
                    // this.notifications = this.notifications.filter((item, i) => map.get(item.sender_id).length === 1);

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
    .button__badge {
        background-color: #fa3e3e;
        border-radius: 2px;
        color: white;

        padding: 1px 3px;
        font-size: 10px;

        position: absolute; /* Position the badge within the relatively positioned button */
        top: 0;
        right: 0;

        min-width: 10px;
        min-height: 10px;
    }
</style>
