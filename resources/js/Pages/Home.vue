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
                            <li><i class="fas fa-trophy"></i> Rank: {{ level_details.level }}</li>

                            <li><i class="fas fa-user-friends"></i> Friends: {{ friends_count }}</li>

<!--                            <li><i class="fas fa-clock"></i> Connections: {{ network_count }}</li>-->

                            <li v-if="profile?.city && profile?.country"><i class="fas fa-home"></i> Lives in {{ profile?.city + ', ' + profile?.country + '.'}}</li>

                            <li><i class="fas fa-heart" v-if="profile?.marital_status"></i> {{ profile?.marital_status }}</li>
                            <li><i class="fas fa-clock"></i> Joined {{ new Date(user.created_at).toLocaleString('en-us',{month:'short', year:'numeric'}) }}</li>
                            <li><i class="fas fa-bullseye-arrow"></i> Weekly Goal {{ goals.weekly_goals }} Referral(s)</li>
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

                    <PostForm id="ref_post_form"/>

                    <PostsList/>
                </div>
                <!-- Center Section -->

                <!-- Right Section -->
                <div class="col-md-3">
                    <a href="#" @click.prevent="myPost" class="btnDesign postBtn mb-4 w-100 text-center myPostText">{{ myPostText }}</a>

                    <WeeklyGoals :goals="goals" />

                    <NewMembersList/>

                    <Messages/>
                </div>

                <!-- Right Section -->
            </div>
        </div>
    </section>
    <div :class="notification_modal.class" style="z-in  dex: 999;">
        <div class="notiImgCont">
            <figure>
                <img :src="notification_modal.img" alt="">
            </figure>
        </div>
        <div class="notiBody">
            <p v-html="notification_modal.text"></p>
        </div>
        <div class="notiFooter">
            <Link @click.prevent="hideNotification" :href="notification_modal.redirect_url"><i class="fas fa-check"></i><span>Ok</span></Link>
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
        Link,
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
        level_details: Object,
        paypal_account_details: String,
        has_provided_stripe_payout_information: Object
    },
    computed: {
        myPostText() {
            if(this.is_my_posts)
                return 'View All Posts'
            return 'My Posts'
        }
    },
    data() {
        return {
            is_my_posts: false,
            // flash_error: this.$page.props?.flash?.error ?? null,
            friends_count: null,
            network_count: null,
            notification_modal: {
                text: '',
                img: '',
                class: 'notifyPopup',
                redirect_url: "#"
            },
        }
    },
    mounted() {
        //show popup notification
        let _t = this;
        this.$emitter.on('show_image_notification', function ({img, text, redirect_url = "#"}) {
            _t.showNotification(img, text, redirect_url);
        });

        this.$emitter.on('change_my_posts_button_text', function (is_my_posts) {
            // console.log("is_my_posts === true && is_my_posts !== 'View All Posts'", is_my_posts === true && is_my_posts !== 'View All Posts')
            if(is_my_posts === true && is_my_posts !== 'View All Posts') {
                // alert('true');
                $('.myPostText').html('View All Posts');
                _t.is_my_posts = !_t.is_my_posts;
            } else {
                $('.myPostText').html('My Posts');
            }
        });

        _t.$emitter.emit('change_my_posts_button_text', 'View All Posts');

        _t.$emitter.emit('populate_share_and_referral_permission_data', {
            'paypal_account_details': _t.paypal_account_details,
            'has_provided_stripe_payout_information': _t.has_provided_stripe_payout_information,
        });

        //if newly registered (NewMemberSignup)
        if(this.$store.getters['Misc/isNewlyRegistered']) {
            let img = _t.$store.getters['Utils/public_asset']('images/notifications/NewMemberSignup.png');
            let text = 'Welcome To Tha Network Let’s get to work sending Referrals, but first Let’s Create a Profile Page!!';
            _t.showNotification(img, text, _t.$route('editProfileForm'));
        }

        //if blocked users area flag is on
        if(this.$store.getters['Misc/getBlockedUsersFlag']) {
            console.log('checkkkkkkkkkkkkkkkkkkkkkk');
            $([document.documentElement, document.body]).animate({
                scrollTop: 1079
            }, 1000);
        }


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

        //change level details
        this.$emitter.emit('change_level_details', this.level_details);
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
        },
        showNotification(img, text, redirect_url = "#") {
            this.notification_modal.img = img;
            this.notification_modal.text = text;
            this.notification_modal.redirect_url = redirect_url;
            this.notification_modal.class = 'notifyPopup show';
        },
        hideNotification() {
            this.notification_modal.class = 'notifyPopup'
        }
    }
}
</script>

<style scoped>

</style>
