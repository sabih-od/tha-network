<template>
    <section class="bg-grey p-0">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-3">
                    <div class="cardWrap">
                        <h2>About</h2>

                        <p class="text-pre-wrap"><strong>Bio: </strong>{{ bio }}</p>
                        <p class="text-pre-wrap"><strong>Gender: </strong>{{ profile.gender }}</p>
                        <p class="text-pre-wrap"><strong>Marital Status: </strong>{{ profile.marital_status }}</p>
                        <a href="#" @click.prevent class="btnDesign">See more</a>
                    </div>

                    <PeopleList/>

                    <div class="cardWrap">
                        <h2>Basic info Details</h2>
                        <ul class="infoList">
                            <li><i class="fas fa-user-friends"></i> Friends: {{ friends_count }}</li>
                            <li v-if="profile?.city && profile?.country"><i class="fas fa-home"></i> Lives in {{ profile?.city + ', ' + profile?.country + '.'}}</li>
                            <li><i class="fas fa-heart" v-if="profile?.marital_status"></i> {{ profile?.marital_status }}</li>
                            <li><i class="fas fa-clock"></i> Joined {{ new Date(user.created_at).toLocaleString('en-us',{month:'short', year:'numeric'}) }}</li>
<!--                            <li><img src="images/followers.png" alt=""> Followed by 2,838 people</li>-->
<!--                            <li>-->
<!--                                <p class="ml-4">See More Details...</p>-->
<!--                            </li>-->
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
                                <h3>{{ friends_count }}</h3>
                                <p>Friends</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img src="images/connections.png" alt="">
                                <h3>{{ network_count }}</h3>
                                <p>Connections</p>
                            </div>
                        </div>
                    </div>

                    <PostsList/>

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
</template>

<script>
import PostForm from "../components/PostForm";
import PostsList from "../components/PostsList";
import ProfileLayout from "../Layouts/ProfileLayout";
import PeopleList from "../components/Widgets/PeopleList";
import PostListItem from "../components/PostListItem";
import NewMembersList from "../components/Widgets/NewMembersList";
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "Profile",
    components: {
        NewMembersList,
        PostListItem,
        PeopleList,
        PostForm,
        PostsList
    },
    data() {
        return {
            friends_count: null,
            network_count: null,
            bio: null,
        }
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object
    },
    mounted() {
        this.$store.commit('Profile/setIsAnother', false)
        this.$store.commit('Profile/setProfile', this.profile)
        $('.btn_message').prop('hidden', true);
        $('.btn_add_friend').prop('hidden', true);
        $('.btn_accept_request').prop('hidden', true);
        $('.btn_reject_request').prop('hidden', true);
        $('.btn_unfriend').prop('hidden', true);
        $('.btn_block').prop('hidden', true);
        $('.btn_invite').prop('hidden', false);

        this.friends_count = usePage().props.value?.friends_count;
        this.network_count = usePage().props.value?.network_count;
        this.bio = this.profile.bio;

        // posts listing initialize
        // this.$store.commit('Post/setInitialUrl', this.$page.url)
        // this.$store.commit('Post/setIsLoadMore', false)
        // this.$store.commit('Post/setPosts', [])
        // this.$store.dispatch('Post/loadPosts', this.$route('profile'))
    },
    unmounted() {
        $('.btn_message').prop('hidden', false);
        $('.btn_invite').prop('hidden', true);
    },
    methods: {
        onPostCreated() {
            // this.$store.commit('Post/setIsLoadMore', false)
            // this.$store.dispatch('Post/loadPosts', this.$route('profile'))
        }
    }
}
</script>

<style scoped>
.text-pre-wrap {
    white-space: pre-wrap;
}
</style>
