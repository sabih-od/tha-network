<template>
    <div class="col-md-9">
        <PostsList/>
    </div>
</template>

<script>
import PostsList from "../components/PostsList";
import ProfileLayout from "../Layouts/ProfileLayout";

export default {
    name: "UserProfile",
    components: {
        PostsList
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object
    },
    mounted() {
        this.$store.commit('Profile/setIsAnother', true)
        this.$store.commit('Profile/setProfile', this.profile)

        // posts listing initialize
        // this.$store.commit('Post/setInitialUrl', this.$page.url)
        // this.$store.commit('Post/setIsLoadMore', false)
        // this.$store.commit('Post/setPosts', [])
        // this.$store.dispatch('Post/loadPosts', this.$route('profile'))
    }
}
</script>

<style scoped>

</style>
