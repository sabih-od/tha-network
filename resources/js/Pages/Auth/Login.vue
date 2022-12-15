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
    <section class="loginSection">
        <div class="loginWrap">
            <div class="row mx-0 no-gutters">
                <div class="col-md-7">
                    <figure>
                        <img :src="asset('images/loginImg.png')" class="w-100" alt="">
                        <img :src="asset('images/user-logo.png')" class="login-logo" alt="">
                    </figure>
                </div>

                <div class="col-md-5">
                    <div class="contentWrap">
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
                                        <a href="forgot-password.php" tabindex="-1">Forget Password?</a>
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
                                                1. If you are not a member you will need an invitation code to enter the site.<br />
                                                2. If a member referred you and gave you an invitation code, use that code to enter the site.<br />
                                                3. If you are visiting the site for the first time and interested in learning more about the site you will need to click <Link :href="$route('invitationCodeForm')" replace>here</Link> to receive an invitation code.
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
        $('#video_element').on('ended', function() {
            _t.skipVideo();
        });

        //stop video if recently logged out
        if(this.$store.getters['Misc/hasLoggedOut']) {
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
