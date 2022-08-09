<template>
    <a @click.prevent="postLike()" href="#" :class="{'active': post?.has_liked}"><i class="fas fa-heart"></i></a>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";
import _ from "lodash";
import utils from "../mixins/utils";

export default {
    name: "LikeButton",
    mixins: [utils],
    props: {
        post: Object
    },
    methods: {
        postLike() {
            Inertia.post(this.$route('postLikeToggle'), {
                post_id: this.post.id
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    const isLike = !this.post?.has_liked
                    let recent_likes = this.post?.recent_likes
                    if (isLike) {
                        recent_likes.push({
                            id: usePage().props.value?.auth?.id,
                            username: usePage().props.value?.auth?.username,
                            profile_image: this.auth_image
                        })
                    } else {
                        _.remove(recent_likes, {id: usePage().props.value?.auth?.id})
                    }
                    this.$emitter.emit('post-like-toggle', {
                        post_id: this.post.id,
                        post_data: {
                            has_liked: isLike,
                            likers_count: (this.post?.likers_count ?? 0) + (isLike ? +1 : -1),
                            recent_likes
                        }
                    })
                },
            })
        }
    }
}
</script>

<style scoped>
.active {
    color: #1c92ff;
}

.active img {
    filter: grayscale(0);
}
</style>
