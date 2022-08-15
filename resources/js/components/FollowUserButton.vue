<template>
<!--    <a v-if="isFollowable" href="#" @click.prevent="follow" class="themeBtn">{{ buttonText }}</a>-->
                <a href="#" class="nav-icons" @click.prevent="follow"><i :class="is_followed ? 'fal fa-user-check' : 'fal fa-user-plus'"></i></a>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "FollowUserButton",
    props: {
        user_id: String,
        post_id: String,
        isPostShared: {
            type: Boolean,
            default: false
        },
        is_followed: false
    },
    data() {
        return {
            initialUrl: this.$page.url
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
            Inertia.post(this.$route('userFollowToggle'), {
                user_id: this.user_id
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    // const setIsFollowing = !this.isFollowing
                    // this.$store.commit('Post/setFollowStatus', {
                    //     user_id: this.user_id,
                    //     path: 'user.is_followed',
                    //     value: setIsFollowing
                    // });
                    // this.$emitter.emit('post-follow-user-toggle', {
                    //     user_id: this.user_id,
                    //     is_following: setIsFollowing
                    // })
                    // this.is_followed = !this.is_followed;
                    this.$emit('update_is_followed', !this.is_followed);
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.initialUrl)
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
