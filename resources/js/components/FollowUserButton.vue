<template>
<!--    <a v-if="isFollowable" href="#" @click.prevent="follow" class="themeBtn">{{ buttonText }}</a>-->
    <Link v-if="!request_sent && is_followed_by_auth" :href="$route('userProfile', user_id)" class="nav-icons"><i class="fal fa-user"></i></Link>
    <a v-if="!request_sent && !is_followed_by_auth" href="#" class="nav-icons" @click.prevent="follow"><i class="fal fa-user-plus"></i></a>
    <a v-if="request_sent" href="#" class="nav-icons" @click.prevent="follow"><i class="fal fa-check"></i></a>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import {useForm, Link} from "@inertiajs/inertia-vue3";
import SendInviteModal from "./SendInviteModal";
import ImageUploadingProgress from "./ImageUploadingProgress";
import UserInfo from "./UserInfo";

export default {
    name: "FollowUserButton",
    components: {
        Link,
    },
    props: {
        user_id: String,
        post_id: String,
        isPostShared: {
            type: Boolean,
            default: false
        },
        is_followed: false,
        is_followed_by_auth: false,
        request_sent: '',
        request_received: ''
    },
    data() {
        return {
            initialUrl: this.$page.url,
            friendRequestForm: useForm({
                "redirect": false
            }),
            request_sent: this.request_sent,
            request_received: this.request_received
        }
    },
    computed: {
        sPost() {
            return this.isPostShared ?
                this.$store.getters['Post/getSharedPost'](this.post_id) :
                this.$store.getters['Post/getSinglePost'](this.post_id)
        },
        buttonText() {
            return this.isFollowing ? 'Following' : '+ Follow'
        },
        isFollowing() {
            return this.sPost?.user?.is_followed ?? false
        },
        isFollowable() {
            return this.user_id !== this.$page.props?.auth?.id
        }
    },
    methods: {
        follow() {
            // Inertia.post(this.$route('userFollowToggle'), {
            //     user_id: this.user_id
            // }, {
            //     replace: true,
            //     preserveState: true,
            //     preserveScroll: true,
            //     onSuccess: () => {
            //         this.$emit('update_is_followed', !this.is_followed);
            //     },
            //     onFinish: () => {
            //         window.history.replaceState({}, '', this.initialUrl)
            //     }
            // })

            this.friendRequestForm.get(this.$route('sendRequest', this.user_id), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Utils/showSuccessMessage');
                    this.request_sent = true;
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
