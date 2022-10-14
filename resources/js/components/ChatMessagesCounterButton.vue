<template>
    <div class="dropdown nav-icons">
        <!--bell icon and badge-->
        <a class="dropdown-toggle" type="button" id="profileDropDown" data-toggle="dropdown" aria-expanded="false">
            <i class="fal fa-bell"></i>
            <span v-if="notifications.length > 0" class="button__badge"></span>
        </a>
        <!--notifications-->
        <div class="dropdown-menu" aria-labelledby="profileDropDown">
            <span v-if="notifications.length == 0" class="dropdown-item">No new messages</span>
            <Link v-else v-for="notification in notifications" class="dropdown-item" replace
                  @click.prevent="notification.sender.id != user.id ? chatWithProfile(notification.sender.id) : ''">
                <strong v-if='notification.sender.id != user.id'>
                    New message from
                    {{ notification.sender.profile.first_name + ' ' + notification.sender.profile.last_name }}
                </strong>
                <p v-else v-html="notification.body">

                </p>
            </Link>
            <Link v-if="notifications.length != 0" class="dropdown-item" replace @click.prevent="clearNotifications()">
                Mark all as read
            </Link>
        </div>

    </div>
</template>

<script>
import {Link, useForm, usePage} from '@inertiajs/inertia-vue3'
import {Inertia} from "@inertiajs/inertia";
import {useToast} from "vue-toastification";

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

        //new member signup
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('NewMemberSignup', this.addNotification);

        //after registration app promotion
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('AfterRegistrationAppPromotion', this.addNotification);

        //lets set weekly goals
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('SetWeeklyGoal', this.addNotification);

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

        //referrals completed and a new connection is added to your connections
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('ReferralCompleted', this.addNotification);

        //When a user send you friend request.
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('FriendRequestReceived', this.addNotification);
        //When a friend request is accepted.
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('FriendRequestAccepted', this.addNotification);
        //When a user likes your post.
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('PostLiked', this.addNotification);
        //When a user comment on your post
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('CommentOnPost', this.addNotification);
        //When a user replies to your comment.
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('ReplyOnComment', this.addNotification);
        //When a user likes your comment.
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('CommentLiked', this.addNotification);
        //When a user likes your reply.
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('ReplyLiked', this.addNotification);
        //When a user shares your post.
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('PostShared', this.addNotification);
        //When you have been promoted to the next grade
        this.$echo.private('App.Models.User.' + this.user.id)
            .listen('RankPromoted', this.addNotification);

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
            Inertia.get(url, {}, {
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
        },
        clearNotifications() {
            Inertia.post(this.$route('clearNotifications'), {}, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    this.notifications = [];
                    this.fetchNotificationData();
                    (useToast()).success('Notifications have been cleared');
                },
            })
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


.dropdown-menu {
    right: 0 !important;
    left: unset;
    width: 300px;
}

.dropdown-menu .dropdown-item {
    white-space: initial !important;
    background: #ddd;
}

.dropdown-menu .dropdown-item + .dropdown-item {
    margin-top: 0.5rem;
}

.dropdown-menu .dropdown-item * {
    margin: 0;
    font-size: 0.875rem;
    font-weight: 500;
}

.dropdown-menu .dropdown-item:hover *{
    color: #fff;
}

.dropdown-menu .dropdown-item:last-of-type{
    background: transparent;
}

.dropdown-menu .dropdown-item:last-of-type:hover{
    color: #000;
}
</style>
