<template>
    <section class="bg-grey p-0">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-3">
                    <div class="cardWrap">
                        <h2>About</h2>
                        <p class="text-pre-wrap">{{ profile?.bio }}</p>
                        <a href="#" @click.prevent class="btnDesign">See more</a>
                    </div>

                    <PeopleList/>

                    <div class="cardWrap">
                        <h2>Basic info Details</h2>
                        <ul class="infoList">
                            <li><i class="fas fa-home"></i> Lives in New York, USA.</li>
                            <li><i class="fas fa-heart"></i> Single</li>
                            <li><i class="fas fa-clock"></i> Joined April 2016</li>
                            <li><img :src="asset('images/followers.png')" alt=""> Followed by 2,838 people</li>
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
                                <img :src="asset('images/ranking.png')" alt="">
                                <h3>10 <sup>th</sup></h3>
                                <p>Rank</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img :src="asset('images/friends.png')" alt="">
                                <h3>250</h3>
                                <p>Friends</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img :src="asset('images/connections.png')" alt="">
                                <h3>1000</h3>
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
import PostsList from "../components/PostsList";
import ProfileLayout from "../Layouts/ProfileLayout";
import PeopleList from "../components/Widgets/PeopleList";
import NewMembersList from "../components/Widgets/NewMembersList";
import utils from "../mixins/utils";
import {useForm, usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "UserProfile",
    mixins: [utils],
    components: {
        PostsList,
        PeopleList,
        NewMembersList
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object
    },
    mounted() {
        this.$store.commit('Profile/setIsAnother', true)
        this.$store.commit('Profile/setProfile', this.profile)

        $('.btn_message').prop('hidden', !(usePage().props.value?.is_auth_friend));
        $('.btn_message').data('profile', usePage().props.value?.user.id);
    },
    unmounted() {
        $('.btn_message').prop('hidden', usePage().props.value?.is_auth_friend);
    }
}
</script>

<style scoped>

</style>
