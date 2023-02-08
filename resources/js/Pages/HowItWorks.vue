<template>
    <div>
        <HowItWorksMenu :visitedByCode="visitedByCode"/>

        <div class="main-slider">
            <img class="img-fluid w-100" :src="data.banner_image" alt="First slide">
            <div class="carousel-caption">
                <div class="container">
                    <div class="row aic">
                        <div class="col-md-4">
<!--                            <figure><img :src="data.banner_image" alt=""></figure>-->
                        </div>
                        <div class="col-md-4">
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="planImgs">
                            <img :src="data.section_3_plan_1_image" class="plan1" alt="">
                            <img :src="data.section_3_plan_2_image" class="plan2" alt="">
                            <img :src="data.section_3_plan_3_image" class="plan3" alt="">
                            <img :src="data.section_3_plan_4_image" class="plan4" alt="">
                            <img :src="data.section_3_plan_5_image" class="plan5" alt="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="secHeading mb-4">{{ data.section_3_title }}</h2>
                        <ul>
                            <li>
                                <p>{{data.section_3_description_line_1}}</p>
                            </li>
                            <li>
                                <p>{{data.section_3_description_line_2}}</p>
                            </li>
                        </ul>
                        <p class="text-center">
                            <strong>{{data.section_3_description_line_3}}</strong>
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
