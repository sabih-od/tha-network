<template>
    <div class="centerBox" ref="chatContainer">
        <template v-if="active_channel_id">
            <ChatMessageItem
                :channel_id="this.active_channel_id"
                v-for="message in messages"
                :message="message"
                :key="message.id"
            />

            <div class="chatMsgOpt">
                <MessageForm :channel_id="this.active_channel_id"/>
            </div>
        </template>
        <h3 v-else class="text-secondary text-center mt-5">No chat selected!</h3>
    </div>
</template>

<script>
import MessageForm from "./MessageForm";
import {Inertia} from "@inertiajs/inertia";
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
            last_scroll_height: null,
            loading: false,
            messages: [],
            next_page_url: null,
        }
    },
    mounted() {
        this.$emitter.on('chat_active', this.chatActiveListener)
        this.$emitter.on('chat_message_added', this.newMessageAddedListener)
        this.$emitter.on('chat_message_deleted', this.messageDeletedListener)
        this.$emitter.on('chat_message_stored', this.chatMessageStoredListener)
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
        }
    }
}
</script>

<style scoped>

</style>
