<template>
    <div class="feedBox" ref="postItem">
        <PostMainData v-if="post?.shared_post" :post="post?.shared_post" is-follow-enable is-sharable/>
        <PostMainData :post="post"/>
        <ul class="feedOptions">
            <li>
                <ClapButton :post_id="post?.id"/>
            </li>
            <li>
                <CommentButton :post_id="post?.id"/>
            </li>
            <li>
                <ShareButton @share-post="sharePost" :post="post"/>
            </li>
            <li class="ml-auto"><a href="#" data-toggle="modal" data-target="#giftModal"
                                   class="themeBtn"><i class="fal fa-hand-holding-box"></i>
                GiftS</a></li>
        </ul>
        <p>{{ recentLikes }}</p>

        <CommentWrapper ref="commentWrapperRef" :post_id="post?.id"/>
        <CommentForm @created="commentCreated" :post_id="post?.id"/>
    </div>
</template>

<script>
import ClapButton from "./ClapButton";
import CommentButton from "./CommentButton";
import CommentForm from "./CommentForm";
import CommentWrapper from "./CommentWrapper";
import ShareButton from "./ShareButton";
import PostMainData from "./PostMainData";

export default {
    name: "PostListItem",
    components: {
        PostMainData,
        ClapButton,
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
                        lists.push(this.post?.recent_likes[i]?.name)
                }
            }
            const remainingCount = this.post?.likers_count - lists.length
            const appendText = this.post?.likers_count > lists.length ? ' and ' + remainingCount : ''
            return lists.join(this.post?.likers_count > 2 ? ', ' : ' and ') + appendText
        }
    },
    mounted() {
        const _this = this
        _this.visibleToLoadComment()
        window.addEventListener('scroll', function () {
            _this.visibleToLoadComment()
        });
    },
    methods: {
        commentCreated() {
            this.$store.commit('Post/setSinglePost', {
                id: this.post.id,
                postData: {
                    comments_count: this.post.comments_count + 1
                }
            });
            this.$refs.commentWrapperRef.loadComments(this.post.id)
        },
        visibleToLoadComment() {
            const element = this.$refs.postItem
            if (element) {
                const position = element.getBoundingClientRect();
                /* if (position.top >= 0 && position.bottom <= window.innerHeight) {
                     if (!_this.$refs.commentWrapperRef.initial_load) {
                         _this.$refs.commentWrapperRef.loadComments(_this.post.id)
                     }
                 }*/

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
            this.$store.dispatch('SharePostModal/showModal', post)
        }
    }
}
</script>

<style scoped>
.text-prewrap {
    white-space: pre-wrap;
}
</style>
