<template>
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
    <section class="loginSection create-profile">
        <div class="loginWrap">
            <div class="row mx-0 no-gutters">
                <div class="col-md-7">
                    <figure>
                        <img :src="asset('images/loginImg.png')" class="loginImg" alt="">
                        <img :src="asset('images/user-logo.png')" class="login-logo" alt="">
                    </figure>
                </div>

                <div class="col-md-5">
                    <div class="contentWrap">
                        <a href="#"><img :src="asset('images/logo.png')" alt="logo"></a>
                        <div class="df jcsb mt-3">
                            <h2 class="m-0">Payment Method</h2>
                            <img :src="asset('images/payment.png')" alt="">
                        </div>
                        <form @submit.prevent="submit">
                            <h3 class="text-secondary" v-if="mountLoading">Please wait...</h3>
                            <div id="payment-element"></div>
                            <template v-if="!mountLoading">
                                <div class="form-check mt-3 mb-0 getText terms_wrapper m-auto" style="padding-bottom: 0px;">
                                    <input type="checkbox" class="form-check-input" v-model="agree_terms" id="agree_terms">
                                    <span>
                                        <p>
                                            I agree with the <a href="#" @click.prevent="showTerms" replace>Terms & Conditions</a> of the website
                                        </p>
                                    </span>
                                </div>
                                <button type="submit" class="themeBtn mt-3" :disabled="!agree_terms">
                                    {{ formLoading ? 'Please wait...' : 'CONFIRM PAYMENT' }}
                                </button>
                                <p class="color-grey mt-3">This payment information will be used for recurring payments
                                    every month. If you would like to cancel recurring payments go to your edit profile
                                    page
                                    to stop recurring payments.</p>
                            </template>
                        </form>
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
import utils from "../mixins/utils";
import {useToast} from "vue-toastification";
import {Link, usePage} from "@inertiajs/inertia-vue3";
import TermsModal from "../components/TermsModal";

export default {
    name: "Payment",
    mixins: [utils],
    props: {
        client_secret: String
    },
    components: {
        Link,
        TermsModal
    },
    data() {
        return {
            elements: null,
            stripe: null,
            mountLoading: true,
            formLoading: false,
            video_classes: 'introVideo fullScreen',
            video_Styling: '',
            agree_terms: false
        }
    },
    mounted() {
        console.log(this.client_secret, document.head.querySelector('#stripe-js'))
        if (!document.head.querySelector('#stripe-js')) {
            const scriptTag = document.createElement('script')
            scriptTag.src = 'https://js.stripe.com/v3/'
            scriptTag.onload = () => {
                console.log('script loaded')
                this.initialize()
            }
            document.head.appendChild(scriptTag)
        } else {
            this.initialize()
        }
    },
    methods: {
        initialize() {
            this.stripe = Stripe("pk_test_0rY5rGJ7GN1xEhCB40mAcWjg");
            this.elements = this.stripe.elements({clientSecret: this.client_secret})

            const paymentElement = this.elements.create("payment");
            paymentElement.mount("#payment-element");
            paymentElement.on('ready', () => {
                this.mountLoading = false
            })
        },
        async submit() {
            this.formLoading = true
            if(!this.agree_terms) {
                $('.terms_wrapper').css('background-color', '#FFFF00');
                return;
            } else {
                $('.terms_wrapper').css('background-color', 'transparent');
            }
            const {error} = await this.stripe.confirmPayment({
                elements: this.elements,
                confirmParams: {
                    return_url: this.$route('successPayment'),
                },
            });

            (useToast()).clear();
            if (error.type === "card_error" || error.type === "validation_error") {
                (useToast()).error(error.message);
            } else {
                (useToast()).error("An unexpected error occurred.");
            }
            this.formLoading = false
        },
        minimizeVideo() {
            this.video_classes = 'introVideo minimized';
        },
        maximizeVideo() {
            this.video_classes = 'introVideo fullScreen';
        },
        skipVideo() {
            this.video_Styling = 'display: none;';
        },
        showTerms() {
            $('.modal_terms').modal('show');
        }
    }
}
</script>

<style scoped>

</style>
