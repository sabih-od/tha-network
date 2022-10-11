<template>
    <li><a @click.prevent="replyLike()" href="#" :style="reply?.has_liked ? 'color: #00a2f5;' : ''" :class="{'active': reply?.has_liked}">{{ reply?.has_liked ? 'Unlike' : 'Like' }}</a></li>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";
import _ from "lodash";
import utils from "../mixins/utils";

export default {
    name: "ReplyLikeButton",
    mixins: [utils],
    props: {
        reply: Object
    },
    methods: {
        replyLike() {
            Inertia.post(this.$route('replyLikeToggle'), {
                reply_id: this.reply.id
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    const isLike = !this.reply?.has_liked;
                    this.reply.has_liked = !this.reply?.has_liked;
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
