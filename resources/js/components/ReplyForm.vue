<template>
    <div class="replyInput">
        <a href="#" @click.prevent class="iconWrap"><img :src="profile_img" class="rounded-circle" alt=""></a>
        <input type="text" ref="refInput" @keyup.enter.prevent="submit" v-model="form.reply"
               placeholder="Write a Reply">
        <button @click.prevent="submit"><i class="fa fa-paper-plane"></i></button>
    </div>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";

export default {
    name: "ReplyForm",
    computed: {
        profile_img() {
            return usePage().props.value?.auth_profile_image ?? this.$store.getters['Utils/public_asset']('images/ph-profile.jpg')
        },
    },
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
                        (useToast()).clear();
                        (useToast()).success(usePage().props.value?.flash?.success ?? 'Request submitted successfully!');
                        this.$emit('created')
                    },
                    onError: () => {
                        (useToast()).clear();
                        const errors = usePage().props.value?.errors ?? {};
                        for (const x in errors) {
                            (useToast()).error(errors[x]);
                            break
                        }
                    }
                })
        },
    }
}
</script>

<style scoped>

</style>
