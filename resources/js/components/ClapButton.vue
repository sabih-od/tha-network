<template>
    <a @click.prevent="postLike()" :class="{'active': sPost?.has_liked}" href="#"><img :src="$store.getters['Utils/public_asset']('images/clapping.png')" alt="">
        {{ clapText }}Clap</a>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";
import _ from "lodash";

export default {
    name: "ClapButton",
    props: {
        post_id: String
    },
    computed: {
        sPost() {
            return this.$store.getters['Post/getSinglePost'](this.post_id)
        },
        clapText() {
            const claps = this.sPost?.likers_count ?? 0
            return claps > 0 ? claps + ' ' : '';
        }
    },
    methods: {
        postLike() {
            Inertia.post(this.$route('postLikeToggle'), {
                post_id: this.post_id
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    const isClapped = !this.sPost?.has_liked
                    let recent_likes = this.sPost?.recent_likes
                    if (isClapped) {
                        recent_likes.push({
                            id: usePage().props.value?.auth?.id,
                            name: usePage().props.value?.auth?.name
                        })
                    } else {
                        _.remove(recent_likes, {id: usePage().props.value?.auth?.id})
                    }
                    this.$store.commit('Post/setSinglePost', {
                        id: this.post_id,
                        postData: {
                            has_liked: isClapped,
                            likers_count: (this.sPost?.likers_count ?? 0) + (isClapped ? +1 : -1),
                            recent_likes
                        }
                    });
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
.active {
    color: #1c92ff;
}

.active img {
    filter: grayscale(0);
}
</style>
