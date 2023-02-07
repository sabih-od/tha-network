<template>
    <div class="commentWrapper">
        <a v-if="next_page_url" href="#" @click.prevent="loadComments(post.id, next_page_url)"
           class="link-primary load-more-link mb-2 d-inline-block">Load previous comments</a>
        <CommentItem
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
            @deleted="deleteCommentHandler"/>
        <a v-if="prev_page_url" href="#" @click.prevent="loadComments(post.id, prev_page_url)"
           class="link-primary load-more-link mb-3 d-inline-block">Load next comments</a>
    </div>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import CommentItem from './CommentItem'
import _ from "lodash";

export default {
    name: "CommentWrapper",
    components: {
        CommentItem
    },
    props: {
        post: Object
    },
    data() {
        return {
            initial_load: false,
            loading: false,
            next_page_url: null,
            prev_page_url: null,
            comments: [],
        }
    },
    methods: {
        loadComments(post_id, url = null) {
            if (this.loading) return;

            if (!this.initial_load)
                this.initial_load = true

            url = url ?? this.$store.getters['Utils/baseUrl']

            this.loading = true
            this.$store.dispatch('HttpUtils/getReq', {
                url: url,
                only: ['comments'],
                params: {
                    post_id
                }
            }).then(res => {
                this.next_page_url = res?.comments?.next_page_url ?? null
                this.prev_page_url = res?.comments?.prev_page_url ?? null
                this.comments = _.reverse(res?.comments?.data ?? [])
            }).finally(() => {
                this.loading = false
            })
        },
        deleteCommentHandler() {
            this.$emitter.emit('comment-deleted', this.post.id)
            this.loadComments(this.post.id)
        },
    }
}
</script>

<style scoped>

</style>
