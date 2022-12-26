<template>
    <section class="bg-grey p-0">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-3">
                    <div class="cardWrap">
                        <h2>About Me</h2>

                        <p class="text-pre-wrap">
                            <strong>Bio: </strong>
                            <span id="span_bio_teaser" v-if="para_bio_lines > 4 && bio_see_more">{{ bio_teaser }}</span>
                            <span id="span_bio" style="word-break: break-all;" v-if="para_bio_lines < 4 || !bio_see_more">{{ bio }}</span>
                            <span class="span_more" style="color: blue; cursor: pointer;" v-if="para_bio_lines > 4" @click.prevent="toggleBioSeeMore()">{{bio_see_more ? '...see more' : '[X]'}}</span>
                        </p>
                        <p class="text-pre-wrap"><strong>Gender: </strong>{{ profile.gender }}</p>
                        <p class="text-pre-wrap"><strong>Marital Status: </strong>{{ profile.marital_status }}</p>

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
                                <img :src="level_details.trophy ?? 'images/ranking.png'" style="max-width: 76px;" alt="">
                                <h3><sup>{{ level_details.level }}</sup></h3>
                                <p>Rank</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <Link :href="$route('home')" @click.prevent="myFriendsOn">
                                <div class="profileAwards">
                                    <img src="images/friends.png" alt="">
                                    <h3>{{ friends_count }}</h3>
                                    <p>Friends</p>
                                </div>
                            </Link>
                        </div>
                        <div class="col-md-4">
                            <Link :href="$route('home')" @click.prevent="peopleInMtNetworkOn">
                                <div class="profileAwards">
                                    <img src="images/connections.png" alt="">
                                    <h3>{{ network_count }}</h3>
                                    <p>People in my network</p>
                                </div>
                            </Link>
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
import {usePage, Link} from "@inertiajs/inertia-vue3";

export default {
    name: "Profile",
    components: {
        NewMembersList,
        PostListItem,
        PeopleList,
        PostForm,
        PostsList,
        Link
    },
    data() {
        return {
            friends_count: null,
            network_count: null,
            bio: null,
            bio_teaser: null,
            see_more: false,
            bio_see_more: false,
            para_bio_lines: 0
        }
    },
    computed: {

    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object,
        level_details: Object,
        paypal_account_details: String,
        has_provided_stripe_payout_information: Object
    },
    mounted() {
        //computing bio
        let _t = this;
        setTimeout(function () {
            _t.para_bio_lines = ($('#span_bio').text().split(' ').length) / 6;
            _t.bio_teaser = _t.bio.substring(0, 20);
            _t.bio_see_more = (_t.para_bio_lines > 4);
        }, 50);

        //hide blocked users and people in my network areas
        this.$emitter.emit('setPeopleInMyNetworkFlagOff', false);
        this.$emitter.emit('setBlockedUsersFlagOff', false);

        _t.$emitter.emit('populate_share_and_referral_permission_data', {
            'paypal_account_details': _t.paypal_account_details,
            'has_provided_stripe_payout_information': _t.has_provided_stripe_payout_information,
        });

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

        //bio with extra long words
        setTimeout(function () {
            let bio_words = _t.bio.split(' ');
            let new_words = [];
            bio_words.forEach((word) => {
                if (word.length > 30) {
                    word = word.match(/.{1,30}/g).join(' ');
                }
                new_words.push(word);
            });
            _t.bio = new_words.join(' ');
        }, 1000);

        //change level details
        this.$emitter.emit('change_level_details', this.level_details);
    },
    unmounted() {
        $('.btn_message').prop('hidden', false);
        $('.btn_invite').prop('hidden', true);
    },
    methods: {
        onPostCreated() {
            // this.$store.commit('Post/setIsLoadMore', false)
            // this.$store.dispatch('Post/loadPosts', this.$route('profile'))
        },
        peopleInMtNetworkOn() {
            this.$store.commit('Misc/setPeopleInMyNetworkFlag', true);
            this.$store.commit('Misc/setBlockedUsersFlag', false);
            this.$store.commit('Misc/setMyFriendsFlag', false);
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#ref_post_list_item0").offset().top - 400
            }, 1000);
        },
        myFriendsOn() {
            this.$store.commit('Misc/setMyFriendsFlag', true);
            this.$store.commit('Misc/setPeopleInMyNetworkFlag', false);
            this.$store.commit('Misc/setBlockedUsersFlag', false);
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#ref_post_list_item0").offset().top - 400
            }, 1000);
        },
        toggleSeeMore () {
            this.see_more = !this.see_more;
        },
        toggleBioSeeMore () {
            this.bio_see_more = !this.bio_see_more;
        }
    }
}
</script>

<style scoped>
.text-pre-wrap {
    white-space: pre-wrap;
}
</style>
