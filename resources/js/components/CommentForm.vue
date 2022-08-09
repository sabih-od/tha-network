<template>
    <div class="commentInput">
        <a href="#" @click.prevent><img :src="auth_image" class="rounded-circle" alt=""></a>
        <input type="text" ref="refInput" @keyup.enter.prevent="submit" v-model="form.comment" placeholder="Write a comment">
        <button @click.prevent="submit"><i class="fa fa-paper-plane"></i></button>
    </div>
<!--    <div class="commentInput">
        <a href="#" @click.prevent class="iconWrap"><img :src="profile_img" class="rounded-circle" alt=""></a>
        <input type="text" ref="refInput" @keyup.enter.prevent="submit" v-model="form.comment"
               placeholder="Write a comment">
        <button @click.prevent="submit"><i class="fa fa-paper-plane"></i></button>
    </div>-->
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import utils from "../mixins/utils";

export default {
    name: "CommentForm",
    mixins: [utils],
    props: {
        post_id: String
    },
    data() {
        return {
            form: useForm({
                post_id: this.post_id,
                comment: ""
            })
        }
    },
    methods: {
        submit() {
            this.$refs.refInput.blur()
            this.form
                .post(this.$route('postCommentStore'), {
                    replace: true,
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.showSuccessMessage()
                        this.$emitter.emit('comment-created', this.post_id)
                        this.$emitter.emit('load-comments', this.post_id)
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
