<template>
  <li>
    <a href="#" @click.prevent="select">
      <div class="friend-drawer friend-drawer--onhover">

        <ProfileImageIconRounded :profile_img="cover?.profile_img" :count="notifications_count"/>
        <div class="text">
          <h6>{{ cover?.profile?.first_name + ' ' + cover?.profile?.last_name}}</h6>
          <p>{{ '@' + cover?.username }}</p>
        </div>

        <div class="dropdown ml-auto">
          <button type="button" id="dropdownNotifications" data-toggle="dropdown"
                  aria-expanded="false">
            <i class="far fa-ellipsis-v"></i>
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownNotifications">
            <a class="dropdown-item" @click.stop.prevent="deleteConversation" href="#">Delete Conversation</a>
          </div>
        </div>
      </div>
    </a>
  </li>
</template>

<script>
import ProfileImageIconRounded from "./ProfileImageIconRounded";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "ChatListingItem",
    components: {
        ProfileImageIconRounded
    },
    props: {
        channel_id: String,
        cover: Object,
        is_auth_friend: Boolean,
        is_in_my_network: Boolean
    },
    data() {
        return {
            form: useForm({
                id: this.channel_id
            })
        }
    },
    computed: {
        notifications_count() {
            return this.$store.getters['Channel/getChannelNotificationsCount'](this.channel_id)
        }
    },
    watch: {
        notifications_count(val) {
            if (val > 0 && this.channel_id === this.$store.state.Channel.chat_active_channel) {
                clearTimeout(this.debounce);
                this.debounce = setTimeout(() => {
                    this.viewedNotifications(this.channel.id)
                }, 1500)
            }
        }
    },
    mounted() {
        let active_user_id_check = this.$store.getters['Chat/activeUserId'];
        if (active_user_id_check) {
            // alert('user id: ' + active_user_id_check);
            // this.$emitter.emit('chat_user_select', active_user_id_check);
            // this.$store.commit('Chat/setActiveUserId', null);

            if (active_user_id_check == this.cover.id) {
                this.select();
                this.$store.commit('Chat/setActiveUserId', null);
            }
        }
    },
    methods: {
        select() {
            this.$emitter.emit('update_is_in_my_network', this.is_in_my_network);
            this.$emitter.emit('chat_active', this.channel_id)
            this.$store.commit('Channel/setChatActiveChannel', this.channel_id)
            this.$emitter.emit('chat_active_user_data', this.cover)
            if (!this.is_auth_friend) {
                this.$emitter.emit('unfriended_user_active');
            } else {
                this.$emitter.emit('unfriended_user_inactive');
            }

            if (this.notifications_count > 0) {
                this.viewedNotifications(this.channel.id)
            }
        },
        viewedNotifications(channel_id) {
            Inertia.post(this.$route('channelNotificationViewed', channel_id),
                {},
                {
                    replace: true,
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        this.$store.commit('Channel/setChannelsNotificationsCounts', {
                            id: channel_id,
                            count: 0
                        })
                    },
                    onFinish: () => {
                        this.$store.dispatch('Utils/showErrorMessages')
                        this.$store.dispatch('Notification/updateTotalCount')
                    }
                })
        },
        deleteConversation() {
            if (this.form.processing) return;

            this.form.delete(this.$route('channelDestroy'), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Channel/loadChatListing')
                    this.$store.dispatch('Utils/showSuccessMessage');
                    // this.$emit('deleted')
                    this.$emitter.emit('conversation_deleted', this.form.id);
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
            // this.$store.dispatch('Utils/showSuccessMessage');
            // this.$emitter.emit('conversation_deleted', this.form.id);
        },
    }
}
</script>

<style scoped>
.imgWrap img {
    width: 50px;
    height: 50px;
    object-fit: cover;
}

.dropdown button {
    background: transparent;
    border: none;
    font-size: 1.5rem;
    padding: 0;
}
</style>
