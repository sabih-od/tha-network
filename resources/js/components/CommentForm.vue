<template>
    <div class="commentInput">
        <a href="#" @click.prevent class="iconWrap"><img :src="profile_img" class="rounded-circle" alt=""></a>
        <input type="text" ref="refInput" @keyup.enter.prevent="submit" v-model="form.comment"
               placeholder="Write a comment">
        <button @click.prevent="submit"><i class="fa fa-paper-plane"></i></button>
    </div>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";

export default {
    name: "CommentForm",
    computed: {
        profile_img() {
            return usePage().props.value?.auth_profile_image ?? this.$store.getters['Utils/public_asset']('images/ph-profile.jpg')
        },
    },
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
