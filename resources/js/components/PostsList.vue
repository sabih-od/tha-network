<template>
    <div>
        <PostListItem
            v-for="(post, index) in posts"
            :post="post"
            :key="post.id"
            :id="'ref_post_list_item' + index"
        />
        <h3 v-if="loading" class="text-secondary mb-5 text-center">Loading...</h3>
        <h3 v-if="!loading && posts.length < 1" class="text-secondary my-5 text-center">No posts at the moment!</h3>
        <teleport to="body">
            <SharePostModal/>
        </teleport>
    </div>
</template>

<script>
import PostListItem from "./PostListItem";
import SharePostModal from "./SharePostModal";
import {Inertia} from "@inertiajs/inertia";
import _ from "lodash";

export default {
    name: "PostsList",
    components: {
        PostListItem,
        SharePostModal
    },
    data() {
        return {
            loading: false,
            next_page_url: null,
            posts: []
        }
    },
    mounted() {
        this.loadPosts()
        window.addEventListener('scroll', this.listener);
        this.$emitter.on('post-created', this.onPostCreated)
        this.$emitter.on('post-shared', this.onPostShared)
        this.$emitter.on('comment-created', this.onCommentCreated)
        this.$emitter.on('post-like-toggle', this.onLikeToggle)
        this.$emitter.on('post-deleted', this.loadPosts)
        this.$emitter.on('my-post-loading', (val) => {
            this.loadPosts(null, val)
        })

        let _t = this;
        this.$emitter.on('fetch_post_on_top', function(post_id) {
            _t.loadPosts(null, false, post_id);
        })
    },
    unmounted() {
        window.removeEventListener('scroll', this.listener)
        this.$emitter.off('post-created')
        this.$emitter.off('post-shared')
        this.$emitter.off('comment-created')
        this.$emitter.off('post-like-toggle')
        this.$emitter.off('post-deleted')
        this.$emitter.off('my-post-loading')
    },
    methods: {
        loadPosts(url = null, is_my_posts = false, post_id = null) {
            if (this.loading) return;

            let isLoadMore = !!(url)
            url = url ?? this.$store.getters['Utils/baseUrl']

            this.loading = true
            if (!isLoadMore)
                this.posts = []
            this.$store.dispatch('HttpUtils/getReq', {
                url: url,
                only: ['posts'],
                params: {
                    is_my_posts: is_my_posts ? 1 : 0,
                    post_id: post_id
                }
            }).then(res => {
                this.next_page_url = res?.posts?.next_page_url ?? null
                if (isLoadMore)
                    this.posts = [
                        ...this.posts,
                        ...(res?.posts?.data ?? [])
                    ]
                else
                    this.posts = res?.posts?.data ?? []
            }).finally(() => {
                this.loading = false
            })
        },
        listener() {
            const appElHeight = document.getElementById('app')?.offsetHeight ?? document.documentElement.offsetHeight
            let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight + 300 >= appElHeight;
            if (bottomOfWindow && this.next_page_url) {
                this.loadPosts(this.next_page_url)
            }
        },
        onPostCreated() {
            this.loadPosts()
        },
        onPostShared() {
            window.scrollTo(0, 0);
            this.loadPosts()
        },
        setPostData(post_id, post_data = {}) {
            const post = _.find(this.posts, {id: post_id})
            if (post)
                _.set(this.posts, _.findIndex(this.posts, {id: post_id}), {
                    ...post,
                    ...post_data
                })
        },
        onCommentCreated(post_id) {
            const post = _.find(this.posts, {id: post_id})
            this.setPostData(post_id, {
                comments_count: post.comments_count + 1
            })
        },
        onLikeToggle({post_id, post_data}) {
            this.setPostData(post_id, post_data)
        },
    }
}
</script>

<style scoped>

</style>
