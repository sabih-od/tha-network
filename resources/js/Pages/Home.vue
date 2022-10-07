<template>
    <section class="bg-grey p-0">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-3">
                    <PeopleList/>

                    <div class="cardWrap p-0">
                        <a href="#" class="imgWrap"><img :src="asset('images/img2.jpg')" alt=""></a>
                    </div>

                    <div class="cardWrap">
                        <h2>Basic info Details</h2>
                        <ul class="infoList">
                            <li><i class="fas fa-trophy"></i> Rank 10th</li>

                            <li><i class="fas fa-user-friends"></i> Friends: {{ friends_count }}</li>

<!--                            <li><i class="fas fa-clock"></i> Connections: {{ network_count }}</li>-->

                            <li v-if="profile?.city && profile?.country"><i class="fas fa-home"></i> Lives in {{ profile?.city + ', ' + profile?.country + '.'}}</li>

                            <li><i class="fas fa-heart" v-if="profile?.marital_status"></i> {{ profile?.marital_status }}</li>
                            <li><i class="fas fa-clock"></i> Joined {{ new Date(user.created_at).toLocaleString('en-us',{month:'short', year:'numeric'}) }}</li>
                            <li><i class="fas fa-bullseye-arrow"></i> Weekly Goal 500 Person</li>
<!--                            <li><img :src="asset('images/followers.png')" alt=""> Followed by 2,838 people</li>-->
<!--                            <li>-->
<!--                                <p class="ml-4">See More Details...</p>-->
<!--                            </li>-->
                        </ul>
                        <Link class="btnDesign" replace :href="$route('editProfileForm')">Edit Info Details</Link>
                    </div>
                </div>
                <!-- Left Section -->

                <!-- Center Section -->
                <div class="col-md-6">
                    <FriendStorySlider/>

                    <PostForm/>

                    <PostsList/>
                </div>
                <!-- Center Section -->

                <!-- Right Section -->
                <div class="col-md-3">
                    <a href="#" @click.prevent="myPost" class="btnDesign postBtn mb-4 w-100 text-center">{{ myPostText }}</a>

                    <WeeklyGoals :goals="goals" />

                    <NewMembersList/>

                    <Messages/>
                </div>

                <!-- Right Section -->
            </div>
        </div>
    </section>
    <div class="notifyPopup">
        <div class="notiBody">
            <p>Nice to see you still here. Or have you just clicked back here after forgetting what this tab was? Oops</p>
        </div>
        <div class="notiFooter">
            <button><i class="fas fa-check"></i><span>Ok</span></button>
            <button><i class="fas fa-times"></i><span>Close</span></button>
        </div>
    </div>
</template>

<script>
import Header from "../components/Header";
import UserInfo from "../components/UserInfo";
import utils from "../mixins/utils";
import PeopleList from "../components/Widgets/PeopleList";
import FriendStorySlider from "../components/Widgets/FriendStorySlider";
import PostForm from "../components/PostForm";
import PostListItem from "../components/PostListItem";
import WeeklyGoals from "../components/Widgets/WeeklyGoals";
import NewMembersList from "../components/Widgets/NewMembersList";
import Messages from "../components/Widgets/Messages";
import ProfileLayout from "../Layouts/ProfileLayout";
import PostsList from "../components/PostsList";
import {Link, usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "Home",
    mixins: [utils],
    components: {
        Messages,
        NewMembersList,
        WeeklyGoals,
        PostListItem,
        PostForm,
        FriendStorySlider,
        PeopleList,
        Header,
        UserInfo,
        PostsList
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object,
        goals: Object,
    },
    computed: {
        myPostText() {
            if(this.is_my_posts)
                return 'Go Back'
            return 'My Posts'
        }
    },
    data() {
        return {
            is_my_posts: false,
            // flash_error: this.$page.props?.flash?.error ?? null,
            friends_count: null,
            network_count: null,
        }
    },
    mounted() {
        //hide message button
        $('.btn_message').prop('hidden', true);
        $('.btn_add_friend').prop('hidden', true);
        $('.btn_accept_request').prop('hidden', true);
        $('.btn_reject_request').prop('hidden', true);
        $('.btn_unfriend').prop('hidden', true);
        $('.btn_block').prop('hidden', true);
        $('.btn_invite').prop('hidden', false);

        this.friends_count = usePage().props.value?.friends_count;
        this.network_count = usePage().props.value?.network_count;
    },
    unmounted() {
        //un-hide message button
        $('.btn_message').prop('hidden', false);
        $('.btn_add_friend').prop('hidden', false);
        $('.btn_accept_request').prop('hidden', false);
        $('.btn_reject_request').prop('hidden', false);
        $('.btn_unfriend').prop('hidden', false);
        $('.btn_block').prop('hidden', false);
        $('.btn_invite').prop('hidden', true);
    },
    methods: {
        myPost() {
            this.is_my_posts = !this.is_my_posts
            this.$emitter.emit('my-post-loading', this.is_my_posts)
        }
    }
}
</script>

<style scoped>

</style>
