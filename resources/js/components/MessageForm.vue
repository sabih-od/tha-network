<template>
    <form @submit.prevent="submit">
        <div class="wrapper">
            <button><i class="fas fa-plus-circle"></i></button>
            <div
                class="text-editor"
                ref="textInput"
                @keydown.enter.prevent.exact="submit"
                @keyup.ctrl.enter.prevent="newLine"
                :contenteditable="!this.form.processing"
            ></div>

            <!--            <textarea class="form-control" rows="1" ref="textInput" v-model="this.form.message"
                                  :disabled="this.form.processing" placeholder="Message Typing"></textarea>-->
            <!--            <input v-model="this.form.message" ref="textInput" :disabled="this.form.processing" type="text"
                               placeholder="Message Typing">-->
            <button><i class="fas fa-gift"></i></button>
            <!-- <button><i class="fas fa-plus-circle"></i></button> -->
            <EmojiButton ref="emojiComponent" @select-emoji="addEmoji"/>
        </div>
    </form>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import EmojiButton from "./EmojiButton";
import _ from 'lodash'

export default {
    name: "MessageForm",
    components: {
        EmojiButton
    },
    props: {
        channel_id: String
    },
    watch: {
        channel_id(val) {
            this.form.channel_id = val
            this.$refs.emojiComponent.hide()
        }
    },
    data() {
        return {
            form: useForm({
                channel_id: this.channel_id,
                message: ''
            })
        }
    },
    methods: {
        addEmoji(url) {
            const el = this.$refs.textInput
            this.removeExtraLine()
            el.innerHTML += ` <img src="${url}" class="emoji-icon" />`
            this.cursorToEnd()
        },
        newLine() {
            const el = this.$refs.textInput
            el.innerHTML += '<br><br>'
            this.cursorToEnd()
        },
        removeExtraLine() {
            const el = this.$refs.textInput
            if (el.childNodes.length > 0) {
                const tag = el.childNodes[el.childNodes.length - 1]
                if (tag.tagName && tag.tagName === 'BR') {
                    el.removeChild(tag)
                }
            }
        },
        cursorToEnd() {
            const el = this.$refs.textInput
            const selection = window.getSelection();
            const range = document.createRange();
            selection.removeAllRanges();
            range.selectNodeContents(el);
            range.collapse(false);
            selection.addRange(range);
            el.focus();
            el.scrollTop = el.scrollHeight
        },
        submit() {
            const el = this.$refs.textInput
            if (el.innerHTML.trim() === '') return
            this.form.message = _.escape(el.innerHTML)
            const date = new Date()
            this.$emitter.emit('chat_message_added', {
                id: 'msg_' + date.getTime(),
                content: this.form.message,
                created_at: date.toISOString(),
                isForm: true,
                sender: {
                    id: usePage().props.value?.auth?.id,
                    name: usePage().props.value?.auth?.name,
                    profile_img: usePage().props.value?.auth_profile_image,
                }
            })
            el.innerHTML = ""
            this.form.reset();
            setTimeout(() => {
                el.focus()
            }, 100)
            return;


        }
    }
}
</script>

<style scoped>
.text-editor,
.text-editor:focus {
    width: 100%;
    border: 0;
    background: transparent;
    color: var(--white);
    font-size: 1rem;
    font-weight: 400;
    max-height: 90px;
    outline: 0;
    box-shadow: none;
    overflow-y: auto;
}
</style>
