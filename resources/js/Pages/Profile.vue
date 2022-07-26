<template>
    <section class="bg-grey p-0">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-3">
                    <div class="cardWrap">
                        <h2>About</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                            labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                        <a href="#" class="btnDesign">See more</a>
                    </div>

                    <PeopleList/>

                    <div class="cardWrap">
                        <h2>Basic info Details</h2>
                        <ul class="infoList">
                            <li><i class="fas fa-home"></i> Lives in New York, USA.</li>
                            <li><i class="fas fa-heart"></i> Single</li>
                            <li><i class="fas fa-clock"></i> Joined April 2016</li>
                            <li><img src="images/followers.png" alt=""> Followed by 2,838 people</li>
                            <li>
                                <p class="ml-4">See More Details...</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Left Section -->

                <!-- Center Section -->
                <div class="col-md-6">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img src="images/ranking.png" alt="">
                                <h3>10 <sup>th</sup></h3>
                                <p>Rank</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img src="images/friends.png" alt="">
                                <h3>250</h3>
                                <p>Friends</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img src="images/connections.png" alt="">
                                <h3>1000</h3>
                                <p>Connections</p>
                            </div>
                        </div>
                    </div>

                    <PostListItem v-for="n in 3" :key="n"/>

                </div>
                <!-- Center Section -->

                <!-- Right Section -->
                <div class="col-md-3">
                    <NewMembersList/>
                </div>
                <!-- Right Section -->
            </div>
        </div>
    </section>
    <!--    <div class="col-md-9">
            <PostForm/>

            <PostsList/>
        </div>-->
</template>

<script>
import PostForm from "../components/PostForm";
import PostsList from "../components/PostsList";
import ProfileLayout from "../Layouts/ProfileLayout";
import PeopleList from "../components/Widgets/PeopleList";
import PostListItem from "../components/PostListItem";
import NewMembersList from "../components/Widgets/NewMembersList";

export default {
    name: "Profile",
    components: {
        NewMembersList,
        PostListItem,
        PeopleList,
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
