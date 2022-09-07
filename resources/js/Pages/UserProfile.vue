<template>
    <section class="bg-grey p-0">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-3">
                    <div class="cardWrap" v-if="profile?.bio">
                        <h2>About</h2>
                        <p class="text-pre-wrap">{{ profile?.bio }}</p>
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
                                <img :src="asset('images/ranking.png')" alt="">
                                <h3>10 <sup>th</sup></h3>
                                <p>Rank</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img :src="asset('images/friends.png')" alt="">
                                <h3>{{ friends_count }}</h3>
                                <p>Friends</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img :src="asset('images/connections.png')" alt="">
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
    computed: {
        formattedDate(string) {
            let dt = new Date(string);
            return dt;
        }
    },
    data() {
        return {
            friends_count: null,
            network_count: null,
            request_sent: null,
            request_received: null,
            user_is_blocked: null,
        }
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object,
    },
    mounted() {
        this.$store.commit('Profile/setIsAnother', true)
        this.$store.commit('Profile/setProfile', this.profile)

        this.friends_count = usePage().props.value?.friends_count;
        this.network_count = usePage().props.value?.network_count;
        this.request_sent = usePage().props.value?.request_sent;
        this.request_received = usePage().props.value?.request_received;
        this.user_is_blocked = usePage().props.value?.user_is_blocked;

        //message button behaviour
        $('.btn_message').prop('hidden', !(usePage().props.value?.is_auth_friend));
        $('.btn_message').data('profile', usePage().props.value?.user.id);

        //invite button behaviour
        $('.btn_invite').prop('hidden', true);

        //add friend button behaviour
        $('.btn_add_friend').prop('hidden', usePage().props.value?.is_auth_friend || this.request_received);
        $('.btn_add_friend').html(this.request_sent ? '<i class="fa fa-check mr-2"></i>Request Sent' : '<i class="fa fa-plus" aria-hidden="true"></i> Add Friend');
        $('.btn_add_friend').prop('disabled', this.request_sent);
        $('.btn_add_friend').data('profile', this.$route('sendRequest', usePage().props.value?.user.id));

        //unfriend button behaviour
        $('.btn_unfriend').prop('hidden', !(usePage().props.value?.is_auth_friend));
        $('.btn_unfriend').data('profile', this.$route('unfriend', usePage().props.value?.user.id));

        //unblock/block button behaviour
        if(this.user_is_blocked) {
            const block_url = this.$route('unblock', usePage().props.value?.user.id);
            const block_btn_text = 'Unblock';
            $('.btn_block').prop('hidden', false);
            $('.btn_block').data('profile', block_url);
            $('.btn_block').html(block_btn_text);
        } else {
            const block_url = this.$route('block', usePage().props.value?.user.id);
            const block_btn_text = 'Block';
            $('.btn_block').prop('hidden', false);
            $('.btn_block').data('profile', block_url);
            $('.btn_block').html(block_btn_text);
        }

        //accept request button behaviour
        $('.btn_accept_request').prop('hidden', !this.request_received);
        $('.btn_accept_request').data('profile', this.$route('acceptRequest', usePage().props.value?.user.id));

        //reject request button behaviour
        $('.btn_reject_request').prop('hidden', !this.request_received);
        $('.btn_reject_request').data('profile', this.$route('rejectRequest', usePage().props.value?.user.id));
    },
    unmounted() {
        $('.btn_message').prop('hidden', usePage().props.value?.is_auth_friend);

        //invite button behaviour
        $('.btn_invite').prop('hidden', false);
    }
}
</script>

<style scoped>

</style>
