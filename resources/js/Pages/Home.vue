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

                            <li><i class="fas fa-user-friends"></i> Friends 250</li>

                            <li><i class="fas fa-clock"></i> Connections 1000</li>

                            <li><i class="fas fa-home"></i> Lives in New York, USA.</li>

                            <li><i class="fas fa-heart"></i> Single</li>
                            <li><i class="fas fa-clock"></i> Joined April 2016</li>
                            <li><i class="fas fa-bullseye-arrow"></i> Weekly Goal 500 Person</li>
                            <li><img :src="asset('images/followers.png')" alt=""> Followed by 2,838 people</li>
                            <li>
                                <p class="ml-4">See More Details...</p>
                            </li>
                        </ul>
                        <a href="#" class="btnDesign">Edit Info Details</a>
                    </div>
                </div>
                <!-- Left Section -->

                <!-- Center Section -->
                <div class="col-md-6">
                    <FriendStorySlider/>

                    <PostForm/>

                    <PostListItem v-for="n in 3" :key="n"/>
                </div>
                <!-- Center Section -->

                <!-- Right Section -->
                <div class="col-md-3">
                    <a href="#" class="btnDesign postBtn mb-4 w-100 text-center" data-toggle="modal"
                       data-target="#weeklyModal">My Posts</a>

                    <WeeklyGoals/>

                    <NewMembersList/>

                    <Messages/>
                </div>

                <!-- Right Section -->
            </div>
        </div>
    </section>
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
        UserInfo
    },
    layout: ProfileLayout,
    data() {
        return {
            // flash_error: this.$page.props?.flash?.error ?? null,
        }
    },
    mounted() {
        /*if (this.flash_error) {
            (useToast()).error(this.flash_error);
        }*/
    },
}
</script>

<style scoped>

</style>
