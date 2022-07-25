<template>
    <a href="#" @click.prevent="sharePost"><i class="fal fa-share"></i> Share</a>
</template>

<script>
export default {
    name: "ShareButton",
    props: {
        post: Object
    },
    methods: {
        sharePost() {
            this.$emit('share-post', this.post)
        }
    }
}
</script>

<style scoped>

</style>
