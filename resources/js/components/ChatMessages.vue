<template>
    <div class="centerBox" >
        <template v-if="active_channel_id">
<!--            <ChatMessageItem-->
<!--                :channel_id="this.active_channel_id"-->
<!--                v-for="message in messages"-->
<!--                :message="message"-->
<!--                :key="message.id"-->
<!--            />-->

<!--            <div class="chatMsgOpt">-->
<!--                <MessageForm :channel_id="this.active_channel_id"/>-->
<!--            </div>-->

            <!--header-->

            <div class="chat-panel">
                <div class="settings-tray">
                    <div class="row jhnmsngr">
                        <div class="col-sm-10">
                            <div class="chatHead">
                                <h6>{{active_user_name}}</h6>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <span class="settings-tray--right"> </span>
                        </div>
                    </div>
                </div>
                <!--inside search-->
                <div class="insideSearch">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button class="searchBtn" type="button"><i class="fa fa-search"></i></button>
                        </div>
                        <input type="text" placeholder="Search here...">
                        <div class="input-group-prepend">
                            <button class="closeSearchBtn" type="button"><i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!--messages-->
                <div class="chatSec" ref="chatContainer">
                    <ChatMessageItem
                        :channel_id="this.active_channel_id"
                        v-for="message in messages"
                        :message="message"
                        :key="message.id"
                    />
                </div>

                <!--message form-->
                <div class="chat-box-tray">
                    <MessageForm :is_auth_friend="is_auth_friend"></MessageForm>
                </div>
            </div>

        </template>
        <h3 v-else class="text-secondary text-center mt-5">No chat selected!</h3>
    </div>
</template>

<script>
import MessageForm from "./MessageForm";
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";
import ChatMessageItem from "./ChatMessageItem";
import _ from "lodash";
import queues from "../mixins/queues";

export default {
    name: "ChatMessages",
    mixins: [queues],
    components: {
        MessageForm,
        ChatMessageItem
    },
    data() {
        return {
            active_channel_id: null,
            active_user_name: null,
            last_scroll_height: null,
            loading: false,
            messages: [],
            next_page_url: null,
            is_auth_friend: true,
        }
    },
    watch: {
        active_channel_id(val, old) {
            if (old) {
                this.$echo.leave('App.Models.Channel.' + old)
            }
            if (val) {
                this.$echo.private('App.Models.Channel.' + val)
                    .listen('NewMessage', this.channelListenAddNewMessage)
                this.chatActiveListener(val)
            }
            // this.$store.commit('Channel/setChatActiveChannel', val)
        }
    },
    mounted() {
        this.$emitter.on('chat_active', this.chatActiveListener)
        this.$emitter.on('chat_message_added', this.newMessageAddedListener)
        this.$emitter.on('chat_message_deleted', this.messageDeletedListener)
        this.$emitter.on('chat_message_stored', this.chatMessageStoredListener)
        this.$emitter.on('chat_active_user_data', this.onChatActiveUserData)
        this.$emitter.on('unfriended_user_active', this.onUnfriendedUserActive);
        this.$emitter.on('unfriended_user_inactive', this.onUnfriendedUserInactive);
        const el = this.$refs.chatContainer
        if (el)
            el.addEventListener('scroll', this.scrollListener)
    },
    unmounted() {
        this.$emitter.off('chat_active')
        this.$emitter.off('chat_message_added')
        this.$emitter.off('chat_message_deleted')
        this.$emitter.off('chat_message_stored')
        const el = this.$refs.chatContainer
        if (el)
            el.removeEventListener('scroll', this.scrollListener)
    },
    methods: {
        chatActiveListener(channel_id) {
            this.active_channel_id = channel_id
            this.next_page_url = null
            this.messages = []
            this.loadMessages()
        },
        newMessageAddedListener(data) {
            this.messages = [
                ...this.messages,
                data
            ]
            this.scrollToBottom()
        },
        messageDeletedListener(id) {
            _.remove(this.messages, (val) => {
                return val.id === id || (val?.created_id && val.created_id === id)
            })
            this.scrollToBottom()
        },
        chatMessageStoredListener(data) {
            const message = _.find(this.messages, {id: data.old_id})
            if (message) {
                _.set(message, 'created_id', data.id)
            }
        },
        channelListenAddNewMessage(e) {
            if (e.data) {
                if (e?.data?.sender?.id !== usePage().props.value?.auth?.id) {
                    this.newMessageAddedListener(e.data)
                }
            }
        },
        scrollListener(e) {
            if (e.target.scrollTop <= 100 && this.next_page_url) {
                this.loadMessages(this.next_page_url)
            }
        },
        loadMessages(url = null) {
            if (this.loading) return;

            let isLoadMore = !!(url)
            url = url ?? this.$store.getters['Utils/baseUrl']
            if (isLoadMore)
                this.lastScrollHeightSet()

            Inertia.get(url, {
                channel_id: this.active_channel_id
            }, {
                replace: true,
                preserveScroll: true,
                preserveState: true,
                only: ['messages'],
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    this.next_page_url = visit.props?.messages?.next_page_url ?? null
                    const newMessages = _.reverse((visit.props?.messages?.data ?? []))
                    if (isLoadMore)
                        this.messages = [
                            ...newMessages,
                            ...this.messages
                        ]
                    else
                        this.messages = newMessages

                    this.scrollToBottom(isLoadMore)
                },
                onFinish: () => {
                    this.loading = false
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        scrollToBottom(is_load_more = false) {
            const el = this.$refs.chatContainer
            if (el) {
                setTimeout(() => {
                    let position = el.scrollHeight
                    if (is_load_more) {
                        position -= this.last_scroll_height
                    }
                    el.scrollTop = position
                }, 200)
            }
        },
        lastScrollHeightSet() {
            const el = this.$refs.chatContainer
            if (el) {
                this.last_scroll_height = el.scrollHeight
            }
        },
        onChatActiveUserData(data) {
            this.active_user_name = data.profile?.first_name + ' ' + data.profile?.last_name;
        },
        onUnfriendedUserActive() {
            this.is_auth_friend = false;
        },
        onUnfriendedUserInactive() {
            this.is_auth_friend = true;
        }
    }
}
</script>

<style scoped>

</style>
