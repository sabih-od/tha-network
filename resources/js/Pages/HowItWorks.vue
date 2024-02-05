<template>
    <div>
        <HowItWorksMenu :visitedByCode="visitedByCode"/>

        <div class="main-slider howItWorkSlider">
            <img class="img-fluid w-100" :src="data.banner_image" alt="First slide">
            <div class="carousel-caption">
                <div class="container">
                    <div class="row aic">
                        <div class="col-sm-4">
                            <figure class="arrowImg"><img :src="asset('images/img1.png')" alt=""></figure>
                        </div>
                        <div class="col-sm-4">
                            <div class="content">
                                <h3>{{ data.banner_circle_title }}</h3>
                                <Link v-if="!this.user" :href="$route('stripePaymentShow')" class="themeBtn" replace>
                                    Join Us
                                </Link>
                                <Link v-else :href="$route('login')" class="themeBtn" replace>Go Back to Profile</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- END: Main Slider -->

        <section class="howItWork">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center mb-5">
                        <h2 class="secHeading">{{ data.section_2_title }}</h2>
                    </div>

                    <div class="col-md-4">
                        <div class="workCard">
                            <span class="sno">01</span>
                            <img :src="asset('images/line1.png')" alt="">
                            <div class="imgWrap">
                                <img :src="asset('images/icon1.png')" alt="">
                            </div>
                            <div class="content">
                                <h3>{{data.section_2_element_1_text}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="workCard">
                            <span class="sno">02</span>
                            <img :src="asset('images/line2.png')" alt="">
                            <div class="imgWrap">
                                <img :src="asset('images/icon2.png')" alt="">
                            </div>
                            <div class="content">
                                <h3>{{data.section_2_element_2_text}}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="workCard">
                            <span class="sno">03</span>
                            <img :src="asset('images/line1.png')" alt="">
                            <div class="imgWrap">
                                <img :src="asset('images/icon3.png')" alt="">
                            </div>
                            <div class="content">
                                <h3>{{data.section_2_element_3_text}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="benefitSec">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2 class="secHeading">{{data.title}}</h2>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 d-flex">
                        <div class="videoBox">
                            <img v-if="video_classes.includes('minimized')" :src="asset('images/intro-placeholder.png')"
                                 alt="">
                            <figure :class="video_classes" :style="video_Styling">
                                <video controls id="video_element">
                                    <source :src="asset('video/introVideo.mp4')">
                                </video>
                                <div id="video_btn">
                                    <button class="themeBtn" @click.prevent="playVideo">Play Introduction</button>
                                </div>
                                <div class="videoControllers">
                                    <button id="minmize" class="themeBtn"
                                            v-if="video_classes === 'introVideo minimized'"
                                            @click.prevent="minimizeVideo"><i
                                        class="fas fa-compress-arrows-alt"></i><span>Minimize</span>
                                    </button>
                                </div>
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="row aic">
                    <div class="col-md-6">
                        <figure><img :src="data.element_1_image" alt=""></figure>
                    </div>
                    <div class="col-md-6">
                        <h3>{{data.element_1_title}}</h3>
                        <p>{{ data.element_1_description_line_1 }}</p>
                        <p>{{ data.element_1_description_line_2 }}</p>
                        <p>{{ data.element_1_description_line_3 }}</p>
                    </div>
                </div>
                <div class="row aic frr">
                    <div class="col-md-6">
                        <figure><img :src="data.element_2_image" alt=""></figure>
                    </div>
                    <div class="col-md-6">
                        <h3>{{ data.element_2_title }}</h3>
                        <p>{{ data.element_2_description }}</p>
                    </div>
                </div>
                <div class="row aic">
                    <div class="col-md-6">
                        <figure><img :src="data.element_3_image" alt=""></figure>
                    </div>
                    <div class="col-md-6">
                        <h3>{{ data.element_3_title }}</h3>
                        <p>{{ data.element_3_description }}</p>
                        <p><strong>{{ data.element_3_title_2 }}</strong></p>
                        <img :src="data.element_3_image_2" class="character" alt="">
                        <p><strong>{{ data.element_3_title_3 }}</strong></p>
                        <ul>
                            <li>{{data.element_3_description_2_line_1}}</li>
                            <li>{{data.element_3_description_2_line_2}}</li>
                            <li>{{data.element_3_description_2_line_3}}</li>
                        </ul>
                        <!--                        <a href="login.php" class="themeBtn">Join Us</a>-->
                    </div>
                </div>
            </div>
        </section>

        <section class="planSec">
            <div class="container">
                <div class="row aic">
                    <div class="col-md-4 offset-md-4">
                        <div class="content text-center">
                            <Link v-if="!this.user" :href="$route('stripePaymentShow')" class="themeBtn" replace>Join
                                Us
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="planSec">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2 class="secHeading">FAQ's</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ol>
                            <li>
                                <b>What is ThaNetwork.org?</b>
                                <br>
                                ThaNetwork.org is a social media platform
                                that allows its members to make post within the platform, chat with other
                                members,  send messages to other members, create business contacts,  create a personalize avatar, and the most important thing of all, It allows each member to build a person network to earn residual income!!
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>Why Should I Join ThaNetwork.org?</b>
                                <br>
                                If you are a person who is interested in
                                becoming a member of an exclusive Social Media Platform that allows each member
                                to earn lots of cash and benefit from a network of friends with common goals,
                                then ThaNetwork.org is for you!!!  If popularity and fame are things that inspire your social media journey, you can continue all activities on other social media platforms, but make sure to add your network Referral Link to all content created to add new members to your network and earn CASH!!!
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>How do I become a member?</b>
                                <br>
                                There are 3 ways to become a member of
                                ThaNetwork.org. First You can go directly to the home login page and request an
                                Invitation Code, Second you can be referred by a current member by hyperlink,
                                and Lastly you can become a member by receiving an Invitation Code and entering
                                the code on the Login Page of the Web Portal under the Invitation Code tab.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>What is the Benefit of membership?</b>
                                <br>
                                The benefit of membership is earning residual income and becoming the top network member!
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>What Can I do on the Site?</b>
                                <br>
                                You can send referrals to friends and followers, and you can perform all of the normal activities granted by social media platforms, but the interactions will be limited to network members.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>How do I get a Invitation Code or Referral Link?</b>
                                <br>
                                Anyone can receive a invitation link or
                                code from a current member of ThaNetwork.org, or you can receive an invitation
                                code from the Login
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>Who can become a member?</b>
                                <br>
                                Anyone above the age of 17 can become a
                                member of ThaNetwork.org.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>How do I send Referrals and get people to join My Network?</b>
                                <br>
                                As a member, go to your home profile page, use the share your profile button to copy your referral link to send to others, or you can select the make a referral button to send a referral email to potential members.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>How do I Edit my profile?</b>
                                <br>
                                Log in to your profile, from your home page select the avatar picture in the upper menu selection area, and select the edit profile link.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>How do I Cancel my Membership?</b>
                                <br>
                                Log in to your profile, from your home page select the avatar picture in the upper menu selection area, and select the Edit Profile link.  Once on the Edit Profile page, scroll to the bottom of the page, select the close my account command button, and follow the directions.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>How does the monthly Automatic payment work?</b>
                                <br>
                                All membership payments are automatically withdrawn on the 1st of each month from the account used by you for payments.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>How and when do I receive referral payments?</b>
                                <br>
                                All referral payments are distributed on the 15th of each month for current members.  Remember that each person who you refer must be a member for at least 30 days before earning monthly membership payments for that person referred.  The initial membership payment will be distributed by the 15th of the month if the referral was made prior to the 12th of the month.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>What happens if I miss a payment?</b>
                                <br>
                                If you miss a payment you will have until the 7th of the month to update your payment method, by going to the bottom of the Edit Profile page and selecting the “Manager Your Stripe Subscription” button.  Once you have followed the instructions to update your payment information you will receive a confirmation email and your account will be updated.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>What happens when my membership is closed?</b>
                                <br>
                                You will lose all membership privileges along with All Network Followers.  You will also lose all monthly membership payments.
                                <br>

                            </li>
                            <br />
                            <li>
                                <b>If my account is closed can I re-open my account and reclaim my personal network?</b>
                                <br>
                                If your account is closed, you will have until the 11th of the month to make a request to reinstate your profile.  To reinstate your profile send a request to info@thanetwork.org.  In the subject enter the date, and title the email, “Reinstate My Account”.Make sure to give your account username so that the account can be searched for.  If the request is not received by the 12th of the month, we will be unable to reinstate your account.
                                <br>

                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>


        <!-- Begin: Footer -->
        <footer>
            <div class="container-md">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 wow fadeInLeft" data-wow-delay="1.2s">
                        <a href="#" class="d-block text-center"><img :src="asset('images/logo.png')" alt="logo"></a>
                        <ul class="links">
                            <li>
                                <Link :href="$route('work')">How It Works</Link>
                            </li>
                            <li>
                                <Link :href="$route('benefits')">Membership Benefits</Link>
                            </li>
                            <li>
                                <Link :href="$route('terms')">Terms & Conditions</Link>
                            </li>
                            <li>
                                <Link :href="$route('privacy')">Privacy Policy</Link>
                            </li>
                            <!--                            <li><Link :href="$route('contact')">Contact Us</Link></li>-->
                        </ul>
                        <ul class="list-unstyled footerSocial">
                            <li><a href="https://www.facebook.com/Tha-Network-150057600527324/" target="_blank"><i
                                class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://twitter.com/ThaNetwork4" target="_blank"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li><a href="https://www.youtube.com/channel/UCBf0MeQqY_T1Oqtw2qOK7Fg" target="_blank"><i
                                class="fab fa-youtube"></i></a></li>
                            <li><a href="https://www.tiktok.com/@_thanetwork_?lang=en" target="_blank"><img
                                :src="asset('images/simple-tiktok.png')" alt=""></a></li>
                            <li><a href="https://www.instagram.com/_thanetwork_/" target="_blank"><i
                                class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row copyRight">
                    <div class="col-lg-12 col-md-12 wow fadeInLeft" data-wow-delay="0.5s">
                        <p>Copyright © 2022 ThaNetwork</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END: Footer -->
    </div>
</template>

<script>
import HowItWorksMenu from "../components/HowItWorksMenu";
import utils from "../mixins/utils";
import {Link, useForm, usePage} from "@inertiajs/inertia-vue3";


export default {
    name: "HowItWorks",
    mixins: [utils],
    components: {
        HowItWorksMenu,
        Link
    },
    props: {
        inviter: Object,
        visitedByCode: Boolean,
        data: Object,
    },
    mounted() {
        let _t = this;
        $('#video_element').on('ended', function () {
            // _t.skipVideo();
        });

        // video columned if user is logged in
        if (this.user) {
            this.video_classes = 'introVideo columned';
        }
    },
    computed: {
        user() {
            return usePage().props.value?.auth ?? null
        },
        asset() {
            return url => this.$store.getters['Utils/public_asset'](url)
        },
    },
    data() {
        return {
            video_classes: 'introVideo columned',
            text_classes: 'hide',
            video_Styling: '',
            gridWidth: screen.availWidth,
            gridHeight: screen.availHeight
        }
    },
    methods: {
        minimizeVideo() {
            this.video_classes = 'introVideo columned';
        },

        playVideo() {
            const video = document.getElementById("video_element");
            const btnCont = document.getElementById("video_btn");
            video.play()
            btnCont.style.display = 'none';
            console.log('working')
            // playBtn.addEventListener('click', function (){
            //     // playBtn.fadeOut('slow')
            // });
        },

        async togglePictureInPicture(event) {
            const video = document.getElementById("video_element");
            const togglePipButton = document.getElementById("pip");

            if (this.video_classes === 'introVideo minimized') {
                this.video_classes = 'introVideo columned';
            }
            try {
                if (video !== document.pictureInPictureElement) {
                    await video.requestPictureInPicture();
                    // togglePipButton.textContent = "Disable Picture in Picture Mode";
                } else {
                    await document.exitPictureInPicture();
                    // togglePipButton.textContent = "Enable Picture in Picture Mode";
                }
            } catch (error) {
                console.log(error);
            } finally {
                togglePipButton.disabled = false; //enable toggle at last
            }
        }
    }
}
</script>

<style scoped>

.introVideo.columned {
    position: unset;
    height: 100%;
}

.introVideo .videoControllers {
    right: 1rem;
}

.introVideo .videoControllers button {
    background-color: rgba(0, 0, 0, 0.5);
}

.introVideo.minimized button span {
    white-space: nowrap;
    right: 0;
}

.videoBox {
    position: relative;
    height: 100%;
    width: 100%;
    background-color: #ddd;
}

#video_btn {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1111111;
}
</style>
