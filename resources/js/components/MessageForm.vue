<template>
    <div v-if="renderedFiles.length > 0" class="render-img-wrapper">
        <div class="row mx-2">
            <div class="col-md-2 position-relative render-img-con" v-for="(file, ind) in renderedFiles" :key="ind">
                <img v-if="file.type === 'image'" :src="file.source" class="img-fluid w-100" alt="">
                <i class="fas fa-times delete-icon"
                   @click.prevent="removeFile($event, file.fileInd, files, renderedFiles)"></i>
            </div>
        </div>
    </div>

    <div v-if="is_auth_friend"
        class="form-control demo6"
        id="message-text"
        ref="textInput"
        @keydown.enter.prevent.exact="submit"
        @keyup.ctrl.enter.prevent="newLine"
        :disabled="this.form.processing"
        :contenteditable="!this.form.processing"
    ></div>

<!--        <input type="text" placeholder="Type your message here..." class="demo6" id="message-text" data-to="11"-->
<!--               ref="textInput"-->
<!--               v-model="this.form.message"-->
<!--               @keydown.enter.prevent.exact="submit"-->
<!--               @keyup.ctrl.enter.prevent="newLine"-->
<!--               :disabled="this.form.processing">-->

        <!--                            <Emojionearea-->
    <!--                                :search="false"-->
    <!--                            ></Emojionearea>-->
    <EmojiButton v-if="is_auth_friend" ref="emojiComponent" @select-emoji="addEmoji"/>

    <div v-if="is_auth_friend" class="papr-clp" id="post-image">
        <i class="far fa-paperclip"></i>
        <input type="file" name="file" id="msgfile" accept="image/*, video/*, audio/*" @change.prevent="filesSelect($event, files, renderedFiles)"
               multiple>
    </div>


    <button v-if="is_auth_friend" class="btn btn-default text-white" id="send_message" @click="submit">
        <i class="fa fa-paper-plane"></i>
    </button>

    <div v-else class="centerBox text-center" ref="chatContainer2" style="width: 100%;">
        <h3 class="text-secondary text-center mt-3">This user isn’t in your friend list. Send request to send a message</h3>
    </div>
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
        channel_id: String,
        is_auth_friend: Boolean
    },
    watch: {
        channel_id(val) {
            this.form.channel_id = val
            this.$refs.emojiComponent.hide()
        },
        renderedFiles: {
            deep: true,
            handler(val) {
                this.$emit('change-files', val)
                this.cursorToEnd()
            }
        }
    },
    data() {
        return {
            form: useForm({
                channel_id: this.channel_id,
                message: '',
            }),
            files: [],
            renderedFiles: []
        }
    },
    methods: {
        addEmoji(url) {
            const el = this.$refs.textInput
            this.removeExtraLine()
            el.innerHTML += ` <img style="max-height: 24px; margin: 0px;" src="${url}" class="emoji-icon" />`
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
            const date = new Date()
            const id = 'msg_' + date.getTime()

            //uncomment innerHTML when emoji work done
            if (el.innerHTML.trim() === '' && this.files.length < 0) return
            this.form.message = _.escape(el.innerHTML)
            // this.form.message = _.escape(el.value)

            let send_data = {
                id,
                content: null,
                file: null,
                created_at: date.toISOString(),
                isForm: true,
                sender: {
                    id: usePage().props.value?.auth?.id,
                    name: usePage().props.value?.auth?.name,
                    profile_img: usePage().props.value?.auth_profile_image,
                }
            }

            if (this.files.length > 0) {
                for (const fileKey in this.files) {
                    const fileObj = this.files[fileKey]
                    send_data = {
                        ...send_data,
                        id: id + '_' + fileKey,
                        file: fileObj.file
                    }
                    this.$emitter.emit('chat_message_added', send_data)
                }
                this.renderedFiles = []
                this.files = []
            }

            if (el.innerHTML.trim() !== '') {
                send_data = {
                    ...send_data,
                    id,
                    file: null,
                    content: this.form.message
                }
                this.$emitter.emit('chat_message_added', send_data);
                el.innerHTML = ""
                this.form.reset();
            }
            setTimeout(() => {
                el.focus()
            }, 100)
            return;


        },
        filesSelect(e) {
            const _this = this

            for (const filesKey in e.target.files) {
                const fileType = e.target.files[filesKey]?.type
                if (fileType) {
                    if (
                        (/^(image\/)[\w]+$/.test(fileType) || /^(video\/)[\w]+$/.test(fileType))
                    ) {
                        const key = this.$store.getters['Utils/uuid']
                        _this.files.push({
                            fileInd: key,
                            file: e.target.files[filesKey]
                        })
                        // render files
                        let reader = new FileReader();
                        reader.onload = function () {
                            _this.renderedFiles.push({
                                fileInd: key,
                                type: 'image',
                                source: reader.result
                            })
                        }
                        if (/^(image\/)[\w]+$/.test(fileType)) {
                            reader.readAsDataURL(e.target.files[filesKey]);
                        } else {
                            _this.$store.dispatch('Utils/getVideoCover', {
                                file_url: URL.createObjectURL(e.target.files[filesKey]),
                                seekTo: 2
                            }).then(res => {
                                reader.readAsDataURL(res);
                            }).catch(err => {
                                (useToast()).clear();
                                (useToast()).error(err);
                            })

                        }
                    } else {
                        (useToast()).clear();
                        (useToast()).error("Invalid file selected!");
                    }
                }
            }
            e.target.value = ""
        },
        removeFile(e, fileInd) {
            e.preventDefault()
            _.remove(this.files, {fileInd})
            _.remove(this.renderedFiles, {fileInd})
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
