<template>
    <div class="cardWrap">
        <div class="df aic jcsb mb-3">
            <h2 class="m-0">Messages</h2>
            <a href="#" class="editBtn"><i class="fal fa-edit"></i></a>
        </div>
        <form action="">
            <div class="searchlist">
                <i class="fal fa-search"></i>
                <input type="search" placeholder="Search messages" name="search" v-model="search" @keyup.prevent="fetchFriendRequests()" autocomplete="off">
                <button><i class="fal fa-sliders-h"></i></button>
            </div>
        </form>
        <div class="msgList">
            <ul class="nav msg-tabs" id="myTab" role="tablist">
                <!--unread notifications-->
                <li>
                    <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one-pane" role="tab"
                       aria-controls="one-pane" aria-selected="true">New <span v-if="unread_notifications.length > 0">({{ unread_notifications.length }})</span></a>
                </li>
                <!--read notifications-->
                <li>
                    <a class="nav-link" id="two-tab" data-toggle="tab" href="#two-pane" role="tab"
                       aria-controls="two-pane" aria-selected="false">Read <span v-if="read_notifications.length > 0">({{ read_notifications.length }})</span></a>
                </li>
                <!--friend requests-->
                <li>
                    <a class="nav-link" id="three-tab" data-toggle="tab" href="#three-pane" role="tab"
                       aria-controls="three-pane" aria-selected="false">Friend Requests <span v-if="peoples.length > 0">({{ peoples.length }})</span></a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <!--unread notifications-->
                <div class="tab-pane fade show active" id="one-pane" role="tabpanel" aria-labelledby="one-tab">
                    <div class="userList" v-for="notification in unread_notifications">
                        <div class="userInfo">
                            <Link href="#" @click.prevent="chatWithProfile(notification.sender.id)"><img :src="notification.sender.profile_image ? notification.sender.profile_image : asset('images/char-usr.png')" class="rounded-circle" alt=""></Link>
                            <h3>
                                {{ notification.sender.profile.first_name + ' ' + notification.sender.profile.last_name }}
                                <a href="#">{{ notification.last_activity_readable }}</a>
                            </h3>
                        </div>
                    </div>
                    <div style="text-align: center!important;" v-if="unread_notifications.length === 0">
                        <h6>No new notifications yet.</h6>
                    </div>
                </div>
                <!--read notifications-->
                <div class="tab-pane fade" id="two-pane" role="tabpanel" aria-labelledby="two-tab">
                    <div class="userList" v-for="notification in read_notifications">
                        <div class="userInfo">
                            <Link href="#" @click.prevent="chatWithProfile(notification.sender.id)"><img :src="notification.sender.profile_image ? notification.sender.profile_image : asset('images/char-usr.png')" class="rounded-circle" alt=""></Link>
                            <h3>
                                {{ notification.sender.profile.first_name + ' ' + notification.sender.profile.last_name }}
                                <a href="#">{{ notification.last_activity_readable }}</a>
                            </h3>
                        </div>
                    </div>
                    <div style="text-align: center!important;" v-if="read_notifications.length === 0">
                        <h6>No read notifications yet.</h6>
                    </div>
                </div>
                <!--friend requests-->
                <div class="tab-pane fade" id="three-pane" role="tabpanel" aria-labelledby="three-tab">
                    <div class="userList" v-for="user in peoples" :ref="'fr_' + user.id">
                        <div class="userInfo">
                            <Link :href="$route('userProfile', user.id)"><img :src="user.profile_image ? user.profile_image : asset('images/char-usr.png')" class="rounded-circle" alt=""></Link>
                            <h3>
                                <Link :href="$route('userProfile', user.id)">
                                    <strong>{{user.profile ? user.profile.first_name +' '+ user.profile.last_name : ''}}</strong>
                                </Link>
                                <a href="#">Connect</a>
                            </h3>
                        </div>
                        <RequestButtonSection :user_id="user.id"></RequestButtonSection>
                    </div>
                    <div style="text-align: center!important;" v-if="peoples.length === 0 && search === ''">
                        <h6>There are no new friend requests yet.</h6>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import utils from "../../mixins/utils";
import {Link, usePage} from "@inertiajs/inertia-vue3";
import FollowUserButton from "../FollowUserButton";
import RequestButtonSection from "../RequestButtonSection";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "Messages",
    mixins: [utils],
    components: {
        Link,
        FollowUserButton,
        RequestButtonSection
    },
    computed: {

    },
    data() {
        return {
            search: '',
            peoples: [],
            debounce: null,
            unread_notifications: [],
            read_notifications: [],
        }
    },
    mounted() {
        this.fetchFriendRequests();
        this.$emitter.emit('request_for_notifications');
        this.$emitter.on('unread_notifications_updated', this.populateUnreadNotifications);
        this.$emitter.on('read_notifications_updated', this.populateReadNotifications);
        this.$emitter.on('remove_fr_section', this.fetchFriendRequests);
    },
    methods: {
        fetchFriendRequests() {
            clearTimeout(this.debounce);
            this.peoples = []
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: ['friend_requests'],
                    params: {
                      search: this.search
                    }
                }).then(res => {
                    this.peoples = res?.friend_requests?.data ?? [];
                }).finally(() => {
                    // this.loading = false
                })
            }, 600);
        },
        populateUnreadNotifications(notifications) {
            this.unread_notifications = [
                ...this.unread_notifications,
                ...notifications
            ];
        },
        populateReadNotifications(notifications) {
            this.read_notifications = [
                ...this.read_notifications,
                ...notifications
            ];
        },
        chatWithProfile(sender_id) {
            this.$emitter.emit('request_chat_with_profile', sender_id);
        },
    }
}
</script>

<style scoped>

</style>
