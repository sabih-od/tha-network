<template>
    <div class="commentWrapper">
        <a v-if="next_page_url" href="#" @click.prevent="loadComments(post_id, next_page_url)"
           class="link-primary load-more-link mb-2 d-inline-block">Load previous comments</a>
        <CommentItem
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
            @deleted="deleteCommentHandler"/>
        <a v-if="prev_page_url" href="#" @click.prevent="loadComments(post_id, prev_page_url)"
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
        post_id: String
    },
    computed: {
        runningQueue() {
            return this.$store.state.LoadingQueue.runningQueue
        },
        sPost() {
            return this.$store.getters['Post/getSinglePost'](this.post_id)
        }
    },
    watch: {
        runningQueue(val) {
            if (_.isEqual(val, this.queue_data)) {
                this.loadComments(this.post_id)
            }
        }
    },
    data() {
        return {
            initial_load: false,
            loading: false,
            next_page_url: null,
            prev_page_url: null,
            comments: [],
            queue_data: {
                type: 'comment_load',
                id: this.post_id
            }
        }
    },
    methods: {
        loadComments(post_id, url = null) {
            if (this.loading) return;

            if (this.$store.state.LoadingQueue.loading)
                return;

          if (!this.initial_load)
            this.initial_load = true

            // queue loading emit
            this.$store.dispatch('LoadingQueue/initQueue', this.queue_data)

            url = url ?? this.$store.getters['Utils/baseUrl']
            Inertia.get(url, {
                post_id
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                only: ['comments'],
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    this.next_page_url = visit.props?.comments?.next_page_url ?? null
                    this.prev_page_url = visit.props?.comments?.prev_page_url ?? null
                    this.comments = _.reverse(visit.props?.comments?.data ?? [])
                    this.$store.dispatch('LoadingQueue/reInit')
                },
                onFinish: () => {
                    this.loading = false
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        deleteCommentHandler() {
            this.$store.commit('Post/setSinglePost', {
                id: this.post_id,
                postData: {
                    comments_count: this.sPost.comments_count - 1
                }
            });

            this.loadComments(this.post_id)
        },
        resetComments() {
            this.comments = []
            this.next_page_url = null
            this.prev_page_url = null
        }
    }
}
</script>

<style scoped>

</style>
