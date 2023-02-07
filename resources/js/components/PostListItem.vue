<template>
    <div class="feedBox" ref="postItem">
        <PostMainData v-if="post?.shared_post && post?.post_id" :post="post?.shared_post" is-sharable/>
        <p v-else-if="!post?.shared_post && post?.post_id" class="border p-3 rounded shadow-sm text-secondary">This
            shared post has been deleted!</p>
        <PostMainData :post="post"/>

        <ul class="feedOptions mb-3">
            <li>
                <LikeButton :post="post"/>
            </li>
            <li><a href="#" @click.prevent="inputFocus(post)"><i class="fal fa-comment-dots"></i></a></li>
            <li><a href="#" @click.prevent="sharePost(post)"><i class="fal fa-share-alt"></i></a></li>
<!--            <li class="ml-auto"><a href="#"><i class="fal fa-bookmark"></i></a></li>-->
        </ul>
        <ul v-if="post?.likers_count > 0" class="likedUser">
            <li v-for="liker in post?.recent_likes" :key="liker.id">
                <a href="#" @click.prevent>
                    <img :src="profile_image(liker.profile_image)" class="rounded-circle" alt="">
                </a>
            </li>
            <li>
                <span v-html="recentLikes"></span>
            </li>
        </ul>

        <CommentWrapper ref="commentWrapperRef" :post="post"/>
        <CommentForm :post_id="post?.id" ref="commentFormRef"/>
    </div>
</template>

<script>
import LikeButton from "./LikeButton";
import CommentButton from "./CommentButton";
import CommentForm from "./CommentForm";
import CommentWrapper from "./CommentWrapper";
import ShareButton from "./ShareButton";
import PostMainData from "./PostMainData";
import utils from "../mixins/utils";

export default {
    name: "PostListItem",
    mixins: [utils],
    components: {
        PostMainData,
        LikeButton,
        CommentButton,
        CommentForm,
        CommentWrapper,
        ShareButton,
    },
    props: {
        post: Object
    },
    computed: {
        recentLikes() {
            let lists = []
            if (this.post?.recent_likes) {
                for (let i = 0; i < 2; i++) {
                    if (this.post?.recent_likes.hasOwnProperty(i))
                        lists.push(`<b>${this.post?.recent_likes[i]?.username}</b>`)
                }
            }
            const remainingCount = `<b>${this.post?.likers_count - lists.length}</b>`
            const appendText = this.post?.likers_count > lists.length ? ' and ' + remainingCount : ''
            return `<span>Liked by ${lists.join(this.post?.likers_count > 2 ? ', ' : ' and ') + appendText}</span>`
        }
    },
    mounted() {
        this.visibleToLoadComment()
        window.addEventListener('scroll', this.visibleToLoadComment);
        this.$emitter.on('load-comments', this.$refs.commentWrapperRef.loadComments)
    },
    unmounted() {
        window.removeEventListener('scroll', this.visibleToLoadComment)
        this.$emitter.off('load-comments')
    },
    methods: {
        visibleToLoadComment() {
            const element = this.$refs.postItem
            if (element) {
                const position = element.getBoundingClientRect();
                if (position.top < window.innerHeight && position.bottom >= 0) {
                    if (!this.$refs.commentWrapperRef.initial_load) {
                        this.$refs.commentWrapperRef.loadComments(this.post.id)
                    }
                }
            }
        },
        /*loadCommentToggle(e) {
            if (e)
                this.$refs.commentWrapperRef.loadComments(this.post.id)
            else
                this.$refs.commentWrapperRef.resetComments()
        },*/
        sharePost(post) {
            // console.log("share trigger post", post)
            // this.$refs.sharePostModalRef.show(post)
            // this.$store.dispatch('SharePostModal/showModal', post)
            this.$emitter.emit('share-post-modal', post)
        },
        inputFocus(post){
            this.$refs.commentFormRef.focusInput()
        }
    }
}
</script>

<style scoped>

</style>
