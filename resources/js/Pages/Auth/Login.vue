<template>
<!--  to view video in full screen add fullScreen class -->
<!--  to view video in minimize screen add minimize class -->
    <figure :class="video_classes" :style="video_Styling">
        <video autoplay muted>
            <source :src="asset('video/introVideo.mp4')">
        </video>
        <div class="videoControllers">
            <button id="minimize" class="themeBtn" v-if="video_classes == 'introVideo fullScreen'" @click.prevent="minimizeVideo"><i class="fas fa-compress-arrows-alt"></i><span>Minimize</span></button>
            <button id="minimize" class="themeBtn" v-if="video_classes == 'introVideo minimize'" @click.prevent="maximizeVideo"><i class="fas fa-compress-arrows-alt"></i><span>Maximize</span></button>
            <button id="skip" class="themeBtn" @click.prevent="skipVideo"><i class="far fa-forward"></i><span>Skip</span></button>
        </div>
    </figure>
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
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="pass">Password</label>
                                        <a href="forgot-password.php">Forget Password?</a>
                                        <input type="password" id="pass" v-model="form.password" placeholder=""
                                               class="form-control">
                                    </div>
                                    <div class="form-group form-check mb-3">
                                        <input type="checkbox" class="form-check-input" v-model="form.remember"
                                               id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Rememer Me</label>
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
    <!--    <section class="loginSection">
            <div class="loginWrap">
                <div class="row align-items-center mx-0 no-gutters">
                    <div class="col-md-7">
                        <figure><img src="images/loginImg.png" alt=""></figure>
                    </div>

                    <div class="col-md-5">
                        <div class="contentWrap">
                            <a href="#"><img src="images/logo.png" alt="logo"></a>
                            <h2>Login <span>Login to Continue</span></h2>
                            <form @submit.prevent="submit">
                                <div class="form-group" :class="{'mb-0': errors.email}">
                                    <i class="fas fa-user"></i>
                                    <input type="email" v-model="form.email" placeholder="Email Address"
                                           class="form-control">
                                </div>
                                <p v-if="errors.email" class="small text-danger">{{ errors.email }}</p>

                                <div class="form-group" :class="{'mb-0': errors.password}">
                                    <i class="fas fa-lock-open-alt"></i>
                                    <input type="password" v-model="form.password" placeholder="Password"
                                           class="form-control">
                                </div>
                                <p v-if="errors.password" class="small text-danger">{{ errors.password }}</p>
                                <button type="submit">LOGIN</button>
                                <div class="df jcsb aic">
                                    <div class="form-group form-check mb-0">
                                        <input type="checkbox" class="form-check-input" v-model="form.remember"
                                               id="saveUser">
                                        <label class="form-check-label" for="saveUser">Save User</label>
                                    </div>
                                    <a href="forgot-password.php">Forgot your password?</a>
                                </div>
                                <div class="df jcc aic mt-5">
                                    <p>Don't Have an Account?
                                        <Link :href="$route('registerForm')" replace>Sign Up</Link>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>-->
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
        Link
    },
    mounted() {
        const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
        });
        // console.log('-------------------------------');
        // console.log(params['send-code']);
        // console.log(params['send-code'] == 'success');
        // console.log('send-code=success' in window.location.href);
        // console.log('-------------------------------');
        this.isCode = params['send-code'] && params['send-code'] == 'success';
        // this.isCode = window.location.search.indexOf('send-code=success') > -1
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
            this.video_classes = 'introVideo minimize';
        },
        maximizeVideo() {
            this.video_classes = 'introVideo fullScreen';
        },
        skipVideo() {
            this.video_Styling = 'display: none;';
        },
    }
}

</script>

<style scoped>

</style>
