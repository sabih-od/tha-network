<template>
    <a href="#" :class="{'active': showComments}" @click.prevent><i class="fal fa-comment-dots"></i>
        {{ commentText }}Comment</a>
</template>

<script>
export default {
    name: "CommentButton",
    props: {
        post_id: String
    },
    computed: {
        sPost() {
            return this.$store.getters['Post/getSinglePost'](this.post_id)
        },
        commentText() {
            const comments = this.sPost?.comments_count ?? 0
            return comments > 0 ? comments + ' ' : '';
        }
    },
    data() {
        return {
            showComments: false
        }
    },
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
