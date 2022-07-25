<template>
    <div>
        <PostListItem
            v-for="post in posts"
            :post="post"
            :key="post.id"/>
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
            posts: [],
            queue_data: {
                type: 'post_load',
                url: null
            }
        }
    },
    mounted() {
        this.loadPosts()
        window.addEventListener('scroll', this.listener);
        this.$emitter.on('post-created', this.onPostCreated)
        this.$emitter.on('post-shared', this.onPostShared)
    },
    unmounted() {
        window.removeEventListener('scroll', this.listener)
        this.$emitter.off('post-created', this.onPostCreated)
        this.$emitter.off('post-shared', this.onPostShared)
    },
    methods: {
        loadPosts(url = null) {
            if (this.loading) return;

            if (this.$store.state.LoadingQueue.loading)
                return;

            let isLoadMore = !!(url)
            url = url ?? this.$store.getters['Utils/baseUrl']
            this.queue_data.url = url

            // queue loading emit
            this.$store.dispatch('LoadingQueue/initQueue', this.queue_data)

            Inertia.get(url, {}, {
                replace: true,
                preserveScroll: true,
                preserveState: true,
                only: ['posts'],
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    this.next_page_url = visit.props?.posts?.next_page_url ?? null
                    if (isLoadMore)
                        this.posts = [
                            ...this.posts,
                            ...(visit.props?.posts?.data ?? [])
                        ]
                    else
                        this.posts = visit.props?.posts?.data ?? []
                    this.$store.commit('Post/setPosts', this.posts)
                    this.$store.dispatch('LoadingQueue/reInit')
                },
                onFinish: () => {
                    this.loading = false
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
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
    }
}
</script>

<style scoped>

</style>
