<template>
    <!--  to view video in full screen add fullScreen class -->
    <!--  to view video in minimize screen add minimized class -->
    <!--    <figure :class="video_classes" :style="video_Styling">-->
    <!--        <video autoplay muted controls id="video_element">-->
    <!--            <source :src="asset('video/introVideo.mp4')">-->
    <!--        </video>-->
    <!--        <div class="videoControllers">-->
    <!--            <button id="minimize" class="themeBtn" v-if="video_classes == 'introVideo fullScreen'" @click.prevent="minimizeVideo"><i class="fas fa-compress-arrows-alt"></i><span>Minimize</span></button>-->
    <!--            <button id="minimize" class="themeBtn" v-if="video_classes == 'introVideo minimized'" @click.prevent="maximizeVideo"><i class="fas fa-compress-arrows-alt"></i><span>Maximize</span></button>-->
    <!--            <button id="skip" class="themeBtn" @click.prevent="skipVideo"><i class="far fa-forward"></i><span>Skip</span></button>-->
    <!--        </div>-->
    <!--    </figure>-->

    <marquee direction="right" behavior="alternate" class="headline">Under maintenance: Please refrain from creating new accounts until maintenance is complete.
    </marquee>

    <section class="loginSection">

        <div class="loginWrap">
            <div class="row mx-md-0 no-gutters position-relative align-items-center">
                <div class="col-md-6">
                    <figure>
                        <img :src="asset('images/loginImg.png')" class="w-100 bg-img" alt="">
                        <img :src="asset('images/user-logo.png')" class="login-logo" alt="">
                    </figure>
                </div>

                <div class="col-md-6">
                    <div class="contentWrap py-0">
                        <a href="#"><img :src="asset('images/logo.png')" alt="logo"></a>
                        <ul class="nav login-tabs" id="myTab" role="tablist" :hidden="isCode">
                            <li>
                                <a class="nav-link" :class="{'active': !isCode}" id="one-tab" data-toggle="tab"
                                   href="#one-pane" role="tab"
                                   aria-controls="one-pane" aria-selected="true">Login</a>
                            </li>
                            <li>
                                <a class="nav-link" :class="{'active': isCode}" id="two-tab" data-toggle="tab"
                                   href="#two-pane" role="tab"
                                   aria-controls="two-pane" aria-selected="false">Invitation Code</a>
                            </li>
                            <li>
                                <a class="nav-link" id="two-tab" data-toggle="tab"
                                   href="#three-pane" role="tab"
                                   aria-controls="two-pane" aria-selected="false">Continue Creating Your Profile</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" :class="{'active show': !isCode}" id="one-pane" role="tabpanel"
                                 aria-labelledby="one-tab">
                                <form @submit.prevent="submit">
                                    <div class="form-group">
                                        <label for="email">Username or Email Address</label>
                                        <input type="text" id="email" v-model="form.email" placeholder=""
                                               class="form-control" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <label for="pass">Password</label>
                                        <Link :href="$route('forgotPasswordForm')" tabindex="-1">Forgot Password?</Link>
                                        <input type="password" id="pass" v-model="form.password" placeholder=""
                                               class="form-control">
                                    </div>
                                    <div class="form-group form-check mb-3">
                                        <input type="checkbox" class="form-check-input" v-model="form.remember"
                                               id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Remember Me</label>
                                    </div>
                                    <div class="getText">
                                        <span>
                                            <p>
                                                1. If you are not a member you will need an invitation code to enter the site.<br/>
                                                2. If a member referred you and gave you an invitation code, use that code to enter the site.<br/>
                                                3. If you are visiting the site for the first time and interested in learning more about the site you will need to click <Link
                                                :href="$route('invitationCodeForm')" replace>here</Link> to receive an invitation code.
                                            </p>
                                        </span>
                                    </div>
                                    <button type="submit" class="themeBtn" :disabled="form.processing">
                                        {{ form.processing ? 'Please wait...' : 'LOGIN' }}
                                    </button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="two-pane" :class="{'active show': isCode}" role="tabpanel"
                                 aria-labelledby="two-tab">
                                <form @submit.prevent="verifyCode">
                                    <div class="form-group">
                                        <label for="code">Enter your Invitation Code</label>
                                        <input type="text" v-model="codeForm.code" id="code" placeholder=""
                                               class="form-control">
                                    </div>
                                    <button type="submit" class="themeBtn" :disabled="codeForm.processing">
                                        {{ form.processing ? 'Please wait...' : 'Submit' }}
                                    </button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="three-pane" role="tabpanel"
                                 aria-labelledby="three-tab">
                                <form @submit.prevent="verifyChargeId">
                                    <div class="form-group">
                                        <label for="code">Enter your Stripe Subscription ID</label>
                                        <p style="font-size: 12px !important;">Note: (If you were unable to create your
                                            profile page after joining the site, please use the Subscription ID that was
                                            emailed to you from the "Thanks for Payment" email.)</p>
                                        <input type="text" v-model="alreadyPayment.stripe_subscription_id" id="code"
                                               placeholder=""
                                               class="form-control">
                                    </div>
                                    <button type="submit" class="themeBtn" :disabled="alreadyPayment.processing">
                                        {{ form.processing ? 'Please wait...' : 'Submit' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        <ul class="list-unstyled footerSocial justify-content-start mt-4">
                            <li><a href="https://www.facebook.com/Tha-Network-150057600527324/" target="_blank"
                                   class="themeBtn p-0"><i
                                class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://twitter.com/ThaNetwork4" target="_blank" class="themeBtn p-0"><i
                                class="fab fa-twitter"></i></a>
                            </li>
                            <li><a href="https://www.youtube.com/channel/UCBf0MeQqY_T1Oqtw2qOK7Fg" target="_blank"
                                   class="themeBtn p-0"><i
                                class="fab fa-youtube"></i></a></li>
                            <li><a href="https://www.tiktok.com/@_thanetwork_?lang=en" target="_blank"
                                   class="themeBtn p-0"><img
                                :src="asset('images/simple-tiktok.png')" alt=""></a></li>
                            <li><a href="https://www.instagram.com/_thanetwork_/" target="_blank"
                                   class="themeBtn p-0"><i
                                class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--    <footer>-->
    <!--        <div class="container-md">-->
    <!--            <div class="row">-->
    <!--                <div class="col-lg-12 col-md-12 col-sm-12 wow fadeInLeft" data-wow-delay="1.2s">-->
    <!--                    <a href="#" class="d-block text-center"><img :src="asset('images/logo.png')" alt="logo"></a>-->
    <!--                    <ul class="links">-->
    <!--                        <li>-->
    <!--                            <Link href="">How It Works</Link>-->
    <!--                        </li>-->
    <!--                        <li>-->
    <!--                            <Link href="">Membership Benefits</Link>-->
    <!--                        </li>-->
    <!--                        <li>-->
    <!--                            <Link href="">Terms & Conditions</Link>-->
    <!--                        </li>-->
    <!--                        <li>-->
    <!--                            <Link href="">Privacy Policy</Link>-->
    <!--                        </li>-->
    <!--                        &lt;!&ndash;                            <li><Link :href="$route('contact')">Contact Us</Link></li>&ndash;&gt;-->
    <!--                    </ul>-->
    <!--                    <ul class="list-unstyled footerSocial">-->
    <!--                        <li><a href="https://www.facebook.com/Tha-Network-150057600527324/" target="_blank"><i-->
    <!--                            class="fab fa-facebook-f"></i></a></li>-->
    <!--                        <li><a href="https://twitter.com/ThaNetwork4" target="_blank"><i class="fab fa-twitter"></i></a>-->
    <!--                        </li>-->
    <!--                        <li><a href="https://www.youtube.com/channel/UCBf0MeQqY_T1Oqtw2qOK7Fg" target="_blank"><i-->
    <!--                            class="fab fa-youtube"></i></a></li>-->
    <!--                        <li><a href="https://www.tiktok.com/@_thanetwork_?lang=en" target="_blank"><img-->
    <!--                            :src="asset('images/simple-tiktok.png')" alt=""></a></li>-->
    <!--                        <li><a href="https://www.instagram.com/_thanetwork_/" target="_blank"><i-->
    <!--                            class="fab fa-instagram"></i></a></li>-->
    <!--                    </ul>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="row copyRight">-->
    <!--                <div class="col-lg-12 col-md-12 wow fadeInLeft" data-wow-delay="0.5s">-->
    <!--                    <p>Copyright © 2022 ThaNetwork</p>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </footer>-->
    <teleport to="body">
        <TermsModal/>
    </teleport>
</template>

<script>
import {useForm, Link} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import utils from "../../mixins/utils";

export default {
    name: "Login",
    mixins: [utils],
    props: {
        errors: Object,
    },
    components: {
        Link,
    },
    mounted() {
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });

        this.isCode = params['send-code'] && params['send-code'] == 'success';

        let _t = this;
        $('#video_element').on('ended', function () {
            _t.skipVideo();
        });

        //stop video if recently logged out
        if (this.$store.getters['Misc/hasLoggedOut']) {
            this.skipVideo();
            this.$store.commit('Misc/setHasLoggedOut', false);
        }
    },
    data() {
        return {
            isCode: false,
            form: useForm('loginForm', {
                email: "",
                password: "",
                remember: false
            }),
            codeForm: useForm('codeForm', {
                code: ''
            }),
            alreadyPayment: useForm('alreadyPayment', {
                stripe_subscription_id: ''
            }),
            video_classes: 'introVideo fullScreen',
            video_Styling: ''
        }
    },
    methods: {
        submit() {
            if (this.form.processing) return;

            this.form.post(this.$route('login'), {
                replace: true,
                onSuccess: () => {
                    this.form.reset()
                },
                onFinish: () => {
                    this.showErrorMessage()
                }
            })
        },
        verifyCode() {
            if (this.codeForm.processing) return;
            this.codeForm.post(this.$route('verifyCode'), {
                replace: true,
                onSuccess: () => {
                    this.codeForm.reset()
                },
                onFinish: () => {
                    this.showErrorMessage()
                }
            })
        },
        verifyChargeId() {
            if (this.alreadyPayment.processing) return;
            this.alreadyPayment.post(this.$route('verifyStripeCharge'), {
                replace: true,
                onSuccess: () => {
                    this.alreadyPayment.reset()
                },
                onFinish: () => {
                    this.showErrorMessage()
                }
            })
        },
        minimizeVideo() {
            this.video_classes = 'introVideo minimized';
        },
        maximizeVideo() {
            this.video_classes = 'introVideo fullScreen';
        },
        skipVideo() {
            this.video_Styling = 'display: none;';
        }
    }
}

</script>

<style scoped>

</style>
