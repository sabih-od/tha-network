<template>
    <li><a @click.prevent="commentLike()" href="#" :style="comment?.has_liked ? 'color: #00a2f5;' : ''"
           :class="{'active': comment?.has_liked}">{{ comment?.has_liked ? 'Unlike' : 'Like' }}</a></li>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";
import _ from "lodash";
import utils from "../mixins/utils";

export default {
    name: "CommentLikeButton",
    mixins: [utils],
    props: {
        comment: Object
    },
    methods: {
        commentLike() {
            Inertia.post(this.$route('commentLikeToggle'), {
                comment_id: this.comment.id
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    const isLike = !this.comment?.has_liked;
                    this.comment.has_liked = !this.comment?.has_liked;
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
