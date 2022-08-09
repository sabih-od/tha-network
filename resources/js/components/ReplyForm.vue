<template>
    <div class="replyInput">
        <a href="#" @click.prevent class="iconWrap"><img :src="auth_image" class="rounded-circle" alt=""></a>
        <input type="text" ref="refInput" @keyup.enter.prevent="submit" v-model="form.reply"
               placeholder="Write a Reply">
        <button @click.prevent="submit"><i class="fa fa-paper-plane"></i></button>
    </div>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import utils from "../mixins/utils";

export default {
    name: "ReplyForm",
    mixins: [utils],
    props: {
        comment_id: String
    },
    data() {
        return {
            form: useForm({
                comment_id: this.comment_id,
                reply: ""
            })
        }
    },
    methods: {
        submit() {
            this.$refs.refInput.blur()
            this.form
                .post(this.$route('commentReplyStore'), {
                    replace: true,
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.showSuccessMessage()
                        this.$emit('created')
                    },
                    onFinish: () => {
                        this.showErrorMessage()
                    }
                })
        },
    }
}
</script>

<style scoped>

</style>
