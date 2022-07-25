<template>
    <div class="groupChatBox">
        <ProfileImageIconRounded
            :user_id="message?.sender?.id"
            :profile_img="message?.sender?.profile_img"
        />
        <div class="content">
            <a href="#" @click.prevent>
                <h3>{{ message?.sender?.name }}</h3>
            </a>
            <div class="deleteMsg">
                <div>
                    <p v-html="renderMessage(message?.content)"></p>
                    <small class="d-block text-secondary">{{
                            $store.getters['Utils/fromNow'](message?.created_at)
                        }}</small>
                    <small class="d-block text-secondary"
                           v-if="message.isForm && storeForm.processing">Sending...</small>
                </div>
                <div v-if="isMe(message?.sender?.id) && (!message.isForm || (message.isForm && message.created_id))" class="dropdown ml-auto">
                    <button type="button" id="dropdownNotifications" data-toggle="dropdown"
                            aria-expanded="false">
                        <i class="far fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownNotifications">
                        <a class="dropdown-item" @click.prevent="deleteMessage" href="#">Delete Message</a>
                    </div>
                </div>
            </div>
            <!--            <a href="#" class="likeBtn"><i class="fas fa-thumbs-up"></i> 2</a>-->
        </div>
    </div>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import _ from "lodash";
import ProfileImageIconRounded from "./ProfileImageIconRounded";

export default {
    name: "ChatMessageItem",
    components: {
        ProfileImageIconRounded
    },
    props: {
        message: Object,
        channel_id: String
    },
    computed: {
        isMe() {
            return (user_id) => {
                if (user_id && usePage().props.value?.auth?.id)
                    return user_id === usePage().props.value?.auth?.id
                return false
            }
        },
        renderMessage() {
            return (string) => {
                return _.unescape(string)
            }
        },
        delete_id() {
            if(this.message.isForm){
                return this.message?.created_id ?? null
            }
            return this.message.id
        }
    },
    data() {
        return {
            storeForm: useForm({
                channel_id: this.channel_id,
                message: ''
            }),
            queue_data: {},
            form: useForm({
                id: this.message?.id ?? null
            })
        }
    },
    updated() {
        this.form.id = this.delete_id
    },
    mounted() {
        if (this.message?.isForm) {
            this.storeForm.message = this.message?.content ?? ''
            this.queue_data = {
                id: this.message?.id
            }
            this.$emitter.on('queue_running', (q_data) => {
                if (_.isEqual(this.queue_data, q_data))
                    this.storeMessage()
            })
            this.$emitter.emit('new_queue_added', this.queue_data)
        }
    },
    unmounted() {
        this.$emitter.off('queue_running')
    },
    methods: {
        storeMessage() {
            if (this.storeForm.processing) return

            this.storeForm.post(this.$route('chatMessageStore'), {
                replace: true,
                preserveScroll: true,
                preserveState: true,
                forceFormData: true,
                onSuccess: () => {
                    this.$emitter.emit('chat_message_stored', {
                        old_id: this.message.id,
                        id: usePage().props.value?.v_data ?? null
                    })
                    this.storeForm.reset();
                },
                onFinish: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                    this.$emitter.emit('next_queue_run')
                }
            })
        },
        deleteMessage() {
            if (this.form.processing) return;

            this.form.delete(this.$route('chatMessageDestroy'), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                    // this.$emit('deleted')
                    this.$emitter.emit('chat_message_deleted', this.form.id)
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
