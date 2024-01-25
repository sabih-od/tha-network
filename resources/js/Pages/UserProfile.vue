<template>
    <section class="bg-grey p-0">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-3">
                    <div class="cardWrap" v-if="profile?.bio">
                        <h2>About</h2>
<!--                        <p class="text-pre-wrap">{{ profile?.bio }}</p>-->
                        <p class="text-pre-wrap">
                            <strong>Bio: </strong>
                            <span id="span_bio_teaser" v-if="para_bio_lines > 4 && bio_see_more">{{ bio_teaser }}</span>
                            <span id="span_bio" v-if="para_bio_lines < 4 || !bio_see_more">{{ profile.bio }}</span>
                            <span class="span_more" style="color: blue; cursor: pointer;" v-if="para_bio_lines > 4" @click.prevent="toggleBioSeeMore()">{{bio_see_more ? '...see more' : '[X]'}}</span>
                        </p>


                        <p v-if="see_more" class="text-pre-wrap"><strong>Rank: </strong>{{ level_details.level }}</p>
                        <p v-if="see_more" class="text-pre-wrap"><strong>Friends: </strong>{{ friends_count }}</p>
                        <p v-if="see_more && (profile?.city && profile?.country)" class="text-pre-wrap"><strong>Lives in: </strong>{{ profile?.city + ', ' + profile?.country + '.'}}</p>
                        <p v-if="see_more" class="text-pre-wrap"><strong>Joined: </strong>{{ new Date(user.created_at).toLocaleString('en-us',{month:'short', year:'numeric'}) }}</p>

                        <a href="javascript:void(0)" v-if="level_details && level_details.level && friends_count && profile && profile.city && profile.country" class="btnDesign" @click.prevent="toggleSeeMore()">{{ see_more ? 'Collapse' : 'See more' }}</a>
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
                                <img :src="level_details.trophy ?? 'images/ranking.png'" style="max-width: 76px;" alt="">
                                <h3><sup>{{ level_details.level }}</sup></h3>
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
            para_bio_lines: 0,
            bio_see_more: false,
            bio_teaser: null,
            see_more: false,
        }
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object,
        profile_image: Object,
        level_details: Object,
        is_in_my_network: Boolean,
        year_to_date_earnings: String,
        gross_earnings: String,
    },
    methods: {
        toggleBioSeeMore () {
            this.bio_see_more = !this.bio_see_more;
        },
        toggleSeeMore () {
            this.see_more = !this.see_more;
        },
    },
    mounted() {
        //computing bio
        let _t = this;
        setTimeout(function () {
            _t.para_bio_lines = ($('#span_bio').text().split(' ').length) / 6;
            _t.bio_teaser = _t.profile.bio.substring(0, 20);
            _t.bio_see_more = (_t.para_bio_lines > 4);
        }, 50);

        //earnings
        this.$emitter.emit('year_to_date_earnings', this.year_to_date_earnings)
        this.$emitter.emit('gross_earnings', this.gross_earnings)

        //hide blocked users and people in my network areas
        this.$emitter.emit('setPeopleInMyNetworkFlagOff', false);
        this.$emitter.emit('setBlockedUsersFlagOff', false);

        this.$store.commit('Profile/setIsAnother', true)
        this.$store.commit('Profile/setProfile', this.profile)

        //change profile image
        this.$emitter.emit('user-profile-image-on', this.profile_image);

        //change level details
        this.$emitter.emit('change_level_details', this.level_details);

        this.friends_count = usePage().props.value?.friends_count;
        this.network_count = usePage().props.value?.network_count;
        this.request_sent = usePage().props.value?.request_sent;
        this.request_received = usePage().props.value?.request_received;
        this.user_is_blocked = usePage().props.value?.user_is_blocked;

        //message button behaviour
        $('.btn_message').prop('hidden', !(usePage().props.value?.is_auth_friend || this.is_in_my_network) || this.user_is_blocked);
        $('.btn_message').data('profile', usePage().props.value?.user.id);

        //invite button behaviour
        $('.btn_invite').prop('hidden', true);

        //add friend button behaviour
        $('.btn_add_friend').prop('hidden', usePage().props.value?.is_auth_friend || this.request_received || this.user_is_blocked);
        $('.btn_add_friend').html(this.request_sent ? 'Cancel Request' : '<i class="fa fa-plus" aria-hidden="true"></i> Add Friend');
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

        //change profile image back to auth user
        this.$emitter.emit('user-profile-image-off');
    }
}
</script>

<style scoped>

</style>
