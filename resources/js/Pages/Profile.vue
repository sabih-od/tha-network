<template>
    <div class="col-md-9">
        <PostForm/>

        <PostsList/>
    </div>
</template>

<script>
import PostForm from "../components/PostForm";
import PostsList from "../components/PostsList";
import ProfileLayout from "../Layouts/ProfileLayout";

export default {
    name: "Profile",
    components: {
        PostForm,
        PostsList
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object
    },
    mounted() {
        this.$store.commit('Profile/setIsAnother', false)
        this.$store.commit('Profile/setProfile', this.profile)

        // posts listing initialize
        // this.$store.commit('Post/setInitialUrl', this.$page.url)
        // this.$store.commit('Post/setIsLoadMore', false)
        // this.$store.commit('Post/setPosts', [])
        // this.$store.dispatch('Post/loadPosts', this.$route('profile'))
    },
    methods: {
        onPostCreated() {
            this.$store.commit('Post/setIsLoadMore', false)
            this.$store.dispatch('Post/loadPosts', this.$route('profile'))
        }
    }
}
</script>

<style scoped>

</style>
