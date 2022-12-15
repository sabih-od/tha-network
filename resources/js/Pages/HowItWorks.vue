<template>
    <div>
        <HowItWorksMenu :visitedByCode="visitedByCode"/>

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
                                <h3>Become a Member</h3>
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
                                    <!--                                    <button id="pip" class="themeBtn"-->
                                    <!--                                            v-if="video_classes === 'introVideo columned'"-->
                                    <!--                                            @click.prevent="togglePictureInPicture"><i-->
                                    <!--                                        class="fal fa-photo-video"></i><span>PIP Mode</span>-->
                                    <!--                                    </button>-->
                                </div>
                            </figure>
                        </div>
                    </div>
                    <!--                    <div class="col-md-6">-->
                    <!--                        <h3>Membership Benefits</h3>-->
                    <!--                        <p>Once you become a member the Sky is the Limit in your earning potential. We have developed-->
                    <!--                            this site to help everyone earn extra cash while also donating a portion of the sites-->
                    <!--                            proceeds to charities. Enjoy a full network of-->
                    <!--                            people to chat with, share information with, send daily post, and brag about the number of-->
                    <!--                            members in your Network!!! Earn Financial Freedom!! Membership has never been this-->
                    <!--                            great!!!!</p>-->
                    <!--                        <p>Have fun, keep the invites coming, build your network!!!</p>-->
                    <!--                        <p>Become a member today!!!!</p>-->
                    <!--&lt;!&ndash;                        <a href="login.php" class="themeBtn">Join Us</a>&ndash;&gt;-->
                    <!--                    </div>-->
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
                        <!--                        <a href="login.php" class="themeBtn">Join Us</a>-->
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
                        <!--                        <a href="login.php" class="themeBtn">Join Us</a>-->
                    </div>
                </div>
                <div class="row aic">
                    <div class="col-md-6">
                        <figure><img :src="asset('images/benefit3.jpg')" alt=""></figure>
                    </div>
                    <div class="col-md-6">
                        <h3>Membership Pays</h3>
                        <p>Join Today for $29.99 per month!!! This will be the best investment you have ever made!!!!!
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
                        <!--                        <a href="login.php" class="themeBtn">Join Us</a>-->
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
                        <ul>
                            <li>
                                <p>
                                    As a member your membership payment will never change, but you will have the ability
                                    to earn more cash when the site becomes More Popular!
                                </p>
                            </li>
                            <li>
                                <p>
                                    Now is the time to become a member While Membership Rates are Inexpensive!!!!!!
                                </p>
                            </li>
                        </ul>
                        <p class="text-center">
                            <strong>So What Are You Waiting For JOIN TODAY!!!</strong>
                        </p>
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


        <!-- Begin: Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 wow fadeInLeft" data-wow-delay="1.2s">
                        <a href="index.php" class="d-block text-center"><img :src="asset('images/logo.png')" alt="logo"></a>
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
            video_classes: 'introVideo minimized',
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
