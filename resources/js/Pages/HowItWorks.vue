<template>
    <div>
        <HowItWorksMenu/>

        <div class="main-slider">
            <img class="img-fluid w-100" :src="asset('images/banner.jpg')" alt="First slide">
            <div class="carousel-caption">
                <div class="container">
                    <div class="row aic">
                        <div class="col-md-4">
                            <figure><img :src="asset('images/img1.png')" alt=""></figure>
                        </div>
                        <div class="col-md-4">
                            <div class="content">
                                <h3>Membership Pays</h3>
                                <Link :href="$route('paymentShow')" class="themeBtn" replace>Join Us</Link>
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
                        <h2 class="secHeading">hOW iT wORKS</h2>
                    </div>

                    <div class="col-md-4">
                        <div class="workCard">
                            <span class="sno">01</span>
                            <img :src="asset('images/line1.png')" alt="">
                            <div class="imgWrap">
                                <img :src="asset('images/icon1.png')" alt="">
                            </div>
                            <div class="content">
                                <h3>Register Account</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has been king to understand unchanged.</p>
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
                                <h3>Refer Friends</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has been king to understand unchanged.</p>
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
                                <h3>Earn Cash</h3>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                    Ipsum has been king to understand unchanged.</p>
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
                        <h2 class="secHeading">Our Benefits</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="videoBox">
                            <figure :class="video_classes" :style="video_Styling">
                                <video autoplay muted controls id="video_element">
                                    <source :src="asset('video/introVideo.mp4')">
                                </video>
                                <div class="videoControllers">
                                    <button id="minimize" class="themeBtn"
                                            v-if="video_classes == 'introVideo fullScreen'"
                                            @click.prevent="togglePictureInPicture"><i class="fal fa-photo-video"></i><span>Picture in Picture</span>
                                    </button>
                                </div>
                            </figure>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3>Membership Benefits</h3>
                        <p>Once you become a member the Sky is the Limit in your earning potential. We have developed
                            this site to help everyone earn extra cash while also donating a portion of the sites
                            proceeds to charities. Enjoy a full network of
                            people to chat with, share information with, send daily post, and brag about the number of
                            members in your Network!!! Earn Financial Freedom!! Membership has never been this
                            great!!!!</p>
                        <p>Have fun, keep the invites coming, build your network!!!</p>
                        <p>Become a member today!!!!</p>
                        <a href="login.php" class="themeBtn">Join Us</a>
                    </div>
                </div>
                <div class="row aic">
                    <div class="col-md-6">
                        <figure><img :src="asset('images/benefit1.jpg')" alt=""></figure>
                    </div>
                    <div class="col-md-6">
                        <h3>Membership Benefits</h3>
                        <p>Once you become a member the Sky is the Limit in your earning potential. We have developed
                            this site to help everyone earn extra cash while also donating a portion of the sites
                            proceeds to charities. Enjoy a full network of
                            people to chat with, share information with, send daily post, and brag about the number of
                            members in your Network!!! Earn Financial Freedom!! Membership has never been this
                            great!!!!</p>
                        <p>Have fun, keep the invites coming, build your network!!!</p>
                        <p>Become a member today!!!!</p>
                        <a href="login.php" class="themeBtn">Join Us</a>
                    </div>
                </div>
                <div class="row aic frr">
                    <div class="col-md-6">
                        <figure><img :src="asset('images/benefit2.jpg')" alt=""></figure>
                    </div>
                    <div class="col-md-6">
                        <h3>Let Me Break it Down for you by doing the math!</h3>
                        <p>If you invited 100 people to join your network (100 x $10 = $1,000) you could earn $1,000 if
                            each person joined the network. If each of the 100 people continue their membership with Tha
                            Network, you will continue to earn $1,000
                            per month for the life of their membership. Now let’s use real numbers, multiple the number
                            of (friends and followers) you have on all your social media sites by $10 per person!! This
                            is how much you could earn per month if
                            all your friends and followers joined your network. What are you waiting for JOIN
                            TODAY!!!</p>
                        <a href="login.php" class="themeBtn">Join Us</a>
                    </div>
                </div>
                <div class="row aic">
                    <div class="col-md-6">
                        <figure><img :src="asset('images/benefit3.jpg')" alt=""></figure>
                    </div>
                    <div class="col-md-6">
                        <h3>Membership Pays</h3>
                        <p>Join Today for $39.95 per month!!! This will be the best investment you have ever made!!!!!
                            You Should be Excited because WE ARE!!!</p>
                        <p><strong>HOW MANY PEOPLE ARE IN YOUR NETWORK!!!!</strong></p>
                        <img :src="asset('images/character.png')" class="character" alt="">
                        <p><strong>REMEMBER, MEMBERSHIP PAYS!!!!</strong></p>
                        <ul>
                            <li>This site was created to allow its members to earn cash by making referrals to the
                                people you know or meet, and allows you to enjoy the benefits that come with a social
                                media experience.
                            </li>
                            <li>You will earn Monthly Income by just convincing your contacts to join your network. Once
                                they join all you need to do is watch the cash add up in your account!
                            </li>
                            <li>The site does all the work for you automatically by sending payments directly to you
                                when your referrals join Tha Network.
                            </li>
                        </ul>
                        <a href="login.php" class="themeBtn">Join Us</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="planSec">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="planImgs">
                            <img :src="asset('images/plan1.jpg')" class="plan1" alt="">
                            <img :src="asset('images/plan2.jpg')" class="plan2" alt="">
                            <img :src="asset('images/plan3.jpg')" class="plan3" alt="">
                            <img :src="asset('images/plan4.jpg')" class="plan4" alt="">
                            <img :src="asset('images/plan5.jpg')" class="plan5" alt="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="secHeading mb-4">OUR MEMBERSHIP PLAN PAYS!</h2>
                        <h6>
                            List of forecasted membership fee increases along with member earnings for each Level.
                        </h6>
                        <ul>
                            <li>
                                <p>
                                    At the start of the business, membership will cost $29.99. Members will receive $10
                                    for referrals.
                                </p>
                            </li>
                            <li>
                                <p>
                                    At Level 2 after there are a total of 500,000 members the new membership price will
                                    increase to $59.99 for new members joining. Members will receive $15 per referral at
                                    this level.
                                </p>
                            </li>
                            <li>
                                <p>
                                    At level 3 and after 2,000,000 members the membership fee will increase to $99.99
                                    and the member will earn $25 per referral.
                                </p>
                            </li>
                            <li>
                                <p>
                                    At Level 4 after 5,000,000 members the membership fee will increase to $159.99 and
                                    the member will earn $50 per referral at this level.
                                </p>
                            </li>
                            <li>
                                <p>
                                    At Level 5 after 7,000,000 members the membership fee will increase to $299.99 and
                                    the member will earn $100 per referral at this level.
                                </p>
                            </li>
                            <li>
                                <p>
                                    At Level 6 after 10,000,000 members the membership fee will increase to $399.99 and
                                    the member will earn $150 per referral at this level.
                                </p>
                            </li>
                            <li>
                                <p>
                                    Next levels will depend on the traffic on the site. If the demand becomes greater,
                                    the membership price will increase to meet the demand. Notifications will be sent to
                                    member’s when the levels change and there will be a site maintenance pause on the
                                    site to accommodate the change.
                                </p>
                            </li>
                            <h6>As a member on the network you will always pay your original membership fee, only new
                                members will be required to pay membership fees at the level they join. All member's
                                will benefit from the referral fee increases for each level.</h6>
                        </ul>
                        <a href="login.php" class="themeBtn">Join Us</a>
                    </div>
                    <div class="col-12">

                    </div>
                </div>
            </div>
        </section>


        <!-- Begin: Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 wow fadeInLeft" data-wow-delay="1.2s">
                        <a href="index.php" class="d-block text-center"><img :src="asset('images/logo.png')" alt="logo"></a>
                        <ul class="links">
                            <li><a href="#">How It Works</a></li>
                            <li><a href="#">Membership Benefits</a></li>
                            <li><a href="#">Terms & Conditions</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                        <ul class="list-unstyled footerSocial">
                            <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-youtube"></i></a></li>
                            <li><a href="#" target="_blank"><img :src="asset('images/simple-tiktok.png')" alt=""></a>
                            </li>
                            <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
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
import {useForm} from "@inertiajs/inertia-vue3";
import gsap from 'gsap'
import {Draggable} from "gsap/Draggable";

gsap.registerPlugin(Draggable);

export default {
    name: "HowItWorks",
    mixins: [utils],
    components: {
        HowItWorksMenu
    },
    props: {
        inviter: Object
    },
    mounted() {
        let _t = this;
        $('#video_element').on('ended', function () {
            // _t.skipVideo();
        });
    },
    data() {
        return {
            video_classes: 'introVideo fullScreen',
            text_classes: 'hide',
            video_Styling: '',
            gridWidth: screen.availWidth,
            gridHeight: screen.availHeight
        }
    },
    methods: {
        minimizeVideo() {
            this.togglePictureInPicture()
        },
        async togglePictureInPicture(event) {
            const video = document.getElementById("video_element");
            const togglePipButton = document.getElementById("minimize");
            try {
                if (video !== document.pictureInPictureElement) {
                    await video.requestPictureInPicture();
                    togglePipButton.textContent = "Exit Pip Mode";
                }
                else {
                    await document.exitPictureInPicture();
                    togglePipButton.textContent = "Enable Pip Mode";
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

.introVideo {
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
</style>
