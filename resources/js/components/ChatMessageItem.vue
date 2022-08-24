<template>
    <div :class="{row: true, 'justify-content-end': isMe(message?.sender?.id)}">
        <div :class="{'tme-cht': true, bluebg: isMe(message?.sender?.id)}">
            <div class="chat-bubble chat-bubble--left justify-content-end">
                <div v-if="isMe(message?.sender?.id) && (!message.isForm || (message.isForm && message.created_id))" class="dropdown ml-auto">
                    <button type="button" id="dropdownNotifications" data-toggle="dropdown"
                            aria-expanded="false">
                        <i class="far fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownNotifications">
                        <a class="dropdown-item" @click.prevent="deleteMessage" href="#">Delete Message</a>
                    </div>
                </div>
                <ChatProfileImageIconRounded v-if="!(isMe(message?.sender?.id))" :user_id="message?.sender?.id" :profile_img="message?.sender?.profile_img"/>
                <div class="mesgHfs">
                    <h5>{{ message?.sender?.name }}</h5>
                    <div class="mesg-bx">
                        <!--text content-->
                        <p v-html="renderMessage(message?.content)"></p>
                        <!--media content-->
                        <div v-if="isMedia" :href="media_file?.url" :data-fancybox="channel_id" class="videoImg">
                            <span v-if="!isImage(media_file?.mime_type)"><i class="fas fa-play"></i></span>
                            <img :src="!isImage(media_file?.mime_type) ? media_file?.video_thumb:media_file?.url"
                                 alt="video"
                                 class="img-fluid">
                        </div>
                        <small class="d-block text-secondary" v-if="message.isForm && storeForm.processing">Sending...</small>
                    </div>
                    <span>{{ $store.getters['Utils/fromNow'](message?.created_at) }}</span>
                </div>
                <ChatProfileImageIconRounded v-if="isMe(message?.sender?.id)" :user_id="message?.sender?.id" :profile_img="message?.sender?.profile_img"/>
            </div>
<!--            <div v-if="isMe(message?.sender?.id) && (!message.isForm || (message.isForm && message.created_id))"-->
<!--                 class="dropdown ml-auto">-->
<!--                <button type="button" id="dropdownNotifications" data-toggle="dropdown"-->
<!--                        aria-expanded="false">-->
<!--                    <i class="far fa-ellipsis-v"></i>-->
<!--                </button>-->
<!--                <div class="dropdown-menu" aria-labelledby="dropdownNotifications">-->
<!--                    <a class="dropdown-item" @click.prevent="deleteMessage" href="#">Delete Message</a>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </div>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import _ from "lodash";
import ChatProfileImageIconRounded from "./ChatProfileImageIconRounded";

export default {
    name: "ChatMessageItem",
    components: {
        ChatProfileImageIconRounded
    },
    props: {
        message: Object,
        channel_id: String
    },
    computed: {
        isMedia() {
            return typeof this.media_file?.url !== 'undefined'
        },
        isImage() {
            return (type) => /^(image\/)[\w]+$/.test(type)
        },
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
            media_file: this.message?.file ?? null,
            storeForm: useForm({
                channel_id: this.channel_id,
                message: '',
                file: null
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
            if (this.message?.file) {
                this.storeForm.file = this.message.file
                this.createThumbnail(this.message.file)
            }
            this.queue_data = {
                id: this.message?.id
            }
            this.$emitter.on('queue_running', (q_data) => {
                if (_.isEqual(this.queue_data, q_data))
                    this.storeMessage()
            })
            this.$emitter.emit('new_queue_added', this.queue_data)
        } else {
            if (this.media_file?.mime_type && !this.isImage(this.media_file.mime_type)) {
                const _this = this
                // render files
                let reader = new FileReader();
                reader.onload = function () {
                    _this.media_file['video_thumb'] = reader.result
                }
                const vidFile = _this.media_file.url
                _this.$store.dispatch('Utils/getVideoCover', {
                    file_url: vidFile,
                    seekTo: 2
                }).then(res => {
                    reader.readAsDataURL(res);
                })
            }
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
                    if (usePage().props.value?.v_data?.media && this.media_file?.mime_type && !this.isImage(this.media_file?.mime_type)) {
                        this.media_file.url = usePage().props.value?.v_data?.media?.url
                    }
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
        },
        createThumbnail(file) {
            const fileType = file?.type
            if (fileType && (/^(image\/)[\w]+$/.test(fileType) || /^(video\/)[\w]+$/.test(fileType))) {
                const _this = this
                _this.media_file = {
                    url: null,
                    mime_type: fileType
                }
                // render files
                let reader = new FileReader();
                reader.onload = function () {
                    // _this.thumbnail = reader.result
                    _this.media_file['url'] = reader.result

                    if (!_this.isImage(fileType)) {
                        _this.media_file['video_thumb'] = reader.result
                    }
                }
                if (/^(image\/)[\w]+$/.test(fileType)) {
                    reader.readAsDataURL(file);
                } else {
                    _this.$store.dispatch('Utils/getVideoCover', {
                        file_url: URL.createObjectURL(file),
                        seekTo: 2
                    }).then(res => {
                        // _this.media_file['url'] = vidFile
                        reader.readAsDataURL(res);
                    })
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
