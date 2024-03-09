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
    <section class="loginSection">
        <div class="loginWrap">
            <div class="row mx-md-0 no-gutters position-relative">
                <div class="col-md-7">
                    <figure>
                        <img :src="asset('images/loginImg.png')" class="loginImg" alt="">
                        <img :src="asset('images/user-logo.png')" class="login-logo" alt="">
                    </figure>
                </div>

                <div class="col-md-5">
                    <div class="contentWrap">
                        <a href="#"><img :src="asset('images/logo.png')" alt="logo"></a>
                        <div class="d-sm-flex justify-content-sm-between mt-3">
                            <h2 class="m-0">Payment Method</h2>
                            <img :src="asset('images/payment.png')" alt="">
                        </div>
                        <div class="df jcsb mt-3 mb-4">
                            <p class="m-0">Welcome to Tha Network and we appreciate your interest in becoming a member
                                of our Exclusive Community. Membership for Tha Network is $29.99 per month. Read our
                                terms and conditions and check the box to acknowledge the disclosure. By checking the
                                box you give Tha Network permission to charge your payment selection $29.99 per month.
                                If at any time you wish to stop automatic payment, go to your Edit Profile Page and
                                select Cancel Membership.</p>
                        </div>

                        <form v-if="isMonthsFirst">
                            <!-- Add a hidden field with the lookup_key of your Price -->
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="customer_email" v-model="customer_email" class="form-control"
                                       placeholder="Enter Email Address">
                            </div>
                            <div class="form-group">
                                <label for="">Card Number</label>
                                <input type="number" name="card_no" v-model="card_number" class="form-control"
                                       placeholder="XXXX-XXXX-XXXX-XXXX">
                            </div>
                            <div class="form-group">
                                <label for="">Expiration Month</label>
                                <input type="text" name="exp_mon" v-model="exp_month" class="form-control" maxlength="2"
                                       placeholder="XX">
                            </div>
                            <div class="form-group">
                                <label for="">Expiration Year</label>
                                <input type="text" name="exp_year" v-model="exp_year" class="form-control" maxlength="4"
                                       placeholder="XXXX">
                            </div>
                            <div class="form-group">
                                <label for="">CCV Code</label>
                                <input type="password" name="cvv" v-model="cvc" class="form-control" maxlength="3"
                                       placeholder="XXX">
                            </div>
                            <div class="form-check mt-3 mb-0 getText terms_wrapper m-auto" style="padding-bottom: 0px;">
                                <input type="checkbox" class="form-check-input" v-model="agree_terms" id="agree_terms">
                                <span>
                                        <p>
                                            <label for="agree_terms">
                                                By checking this box you agree to the <a href="#"
                                                                                         @click.prevent="showTerms"
                                                                                         replace>Terms & Conditions</a>
                                            </label>
                                        </p>
                                    </span>
                            </div>
                            <button v-if="!is_excluded_country" class="themeBtn" id="checkout-and-portal-button"
                                    type="button" @click.prevent="stripe_subscribe" :disabled="form_loading">
                                {{ form_loading ? 'Please Wait' : 'Subscribe' }}
                            </button>
                            <span v-else>Not available in your country.</span>
                        </form>

                        <!--                        <form v-else action="/charge" method="post" id="payment-form">-->
                        <!--                            <div class="form-row">-->
                        <!--                                <label for="card-element">-->
                        <!--                                    Credit or debit card-->
                        <!--                                </label>-->
                        <!--                            </div>-->

                        <!--                            <div id="card-element">-->
                        <!--                                &lt;!&ndash; A Stripe Element will be inserted here. &ndash;&gt;-->
                        <!--                            </div>-->

                        <!--                            &lt;!&ndash; Used to display Element errors. &ndash;&gt;-->
                        <!--                            <div id="card-errors" role="alert"></div>-->

                        <!--                            <button>Submit Payment</button>-->
                        <!--                        </form>-->

                        <form v-else>
                            <!-- Add a hidden field with the lookup_key of your Price -->
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="customer_email" v-model="customer_email" class="form-control"
                                       placeholder="Enter Email Address">
                            </div>
                            <div class="form-group">
                                <label for="">Card Number</label>
                                <input type="number" name="card_no" v-model="card_number" class="form-control"
                                       placeholder="XXXX-XXXX-XXXX-XXXX">
                            </div>
                            <div class="form-group">
                                <label for="">Expiration Month</label>
                                <input type="text" name="exp_mon" v-model="exp_month" class="form-control" maxlength="2"
                                       placeholder="XX">
                            </div>
                            <div class="form-group">
                                <label for="">Expiration Year</label>
                                <input type="text" name="exp_year" v-model="exp_year" class="form-control" maxlength="4"
                                       placeholder="XXXX">
                            </div>
                            <div class="form-group">
                                <label for="">CCV Code</label>
                                <input type="password" name="cvv" v-model="cvc" class="form-control" maxlength="3"
                                       placeholder="XXX">
                            </div>
                            <div class="form-check mt-3 mb-0 getText terms_wrapper m-auto" style="padding-bottom: 0px;">
                                <input type="checkbox" class="form-check-input" v-model="agree_terms" id="agree_terms">
                                <span>
                                        <p>
                                            <label for="agree_terms">
                                                By checking this box you agree to the <a href="#"
                                                                                         @click.prevent="showTerms"
                                                                                         replace>Terms & Conditions</a>
                                            </label>
                                        </p>
                                    </span>
                            </div>
                            <button v-if="!is_excluded_country" class="themeBtn" id="checkout-and-portal-button"
                                    type="button" @click.prevent="stripeCharge" :disabled="form_loading">
                                {{ form_loading ? 'Please Wait' : 'Subscribe' }}
                            </button>
                            <span v-else>Not available in your country.</span>

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
import {Link, usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import _ from "lodash";
import {useToast} from "vue-toastification";
import {loadStripe} from '@stripe/stripe-js';
import * as Stripe from "stripe";
import TermsModal from "../components/TermsModal";

export default {
    name: "StripePayment",
    mixins: [utils],
    props: {
        client_secret: String,
        checkout_session: Object,
        error: String,
        isMonthsFirst: Boolean
    },
    watch: {
        checkout_session: function (nVal, oVal) {
            if (nVal) {
                window.location.href = this.checkout_session.url;
            }
        },
        error: function (nVal, oVal) {
            if (nVal) {
                (useToast()).error(nVal);
                // this.card_number = '';
                // this.exp_month = '';
                // this.exp_year = '';
                // this.cvc = '';
            }
        }
    },
    components: {
        Link,
        TermsModal
    },
    data() {
        return {
            customer_email: '',
            card_number: '',
            exp_month: '',
            exp_year: '',
            cvc: '',
            form_loading: false,
            agree_terms: false,
            is_excluded_country: false,
        }
    },
    mounted() {
        let _t = this;
        $.get("https://ipinfo.io", function (response) {
            const excluded_countries = [
                'AF',
                'AL',
                'BA',
                'BG',
                'HR',
                'GR',
                'ME',
                'MK',
                'RO',
                'RS',
                'SI',
                'BY',
                'MM',
                'CN',
                'CU',
                'ET',
                'HK',
                'IQ',
                'LB',
                'LY',
                'AT',
                'NI',
                'KR',
                'RU',
                'SO',
                'SD',
                'SS',
                'SY',
                'UA',
                'VE',
                'YE',
                'ZW'
            ];

            _t.is_excluded_country = (excluded_countries.includes(response.country));
        }, "jsonp");
    },
    methods: {
        async stripe_subscribe() {
            if (!this.agree_terms) {
                (useToast()).error('You must acknowledge the Terms and Conditions by checking the box above before continuing.');
                return;
            }
            this.form_loading = true;
            Inertia.post(this.$route('createStripeCheckoutSession'), {
                customer_email: this.customer_email,
                card_number: this.card_number,
                exp_month: this.exp_month,
                exp_year: this.exp_year,
                cvc: this.cvc
            }, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {
                    console.log(res);
                    // alert(res);
                },
                onFinish: () => {
                    this.form_loading = false;
                },
                onError: (e) => {
                    this.form_loading = false;

                    if (e && e.error) {
                        (useToast()).clear();
                        (useToast()).error(e.error);
                    } else if (e != '') {
                        (useToast()).clear();
                        (useToast()).error(e);
                    }
                },
            })
        },
        async stripeCharge() {
            if (!this.agree_terms) {
                (useToast()).error('You must acknowledge the Terms and Conditions by checking the box above before continuing.');
                return;
            }
            // const stripe = await Stripe('pk_test_0rY5rGJ7GN1xEhCB40mAcWjg');
            this.form_loading = true;
            const stripe = require('stripe')(process.env.MIX_STRIPE_SECRET_KEY);

            stripe.tokens.create({
                'card': {
                    number: this.card_number,
                    exp_month: this.exp_month,
                    exp_year: this.exp_year,
                    cvc: this.cvc
                }
            }).then(res => {
                if (res.id) {
                    Inertia.post(this.$route('createStripeCheckoutSession'), {
                        token_id: res.id,
                        customer_email: this.customer_email,
                        card_number: this.card_number,
                        exp_month: this.exp_month,
                        exp_year: this.exp_year,
                        cvc: this.cvc
                    }, {
                        preserveState: true,
                        preserveScroll: true,
                        onSuccess: (res) => {
                            console.log(res);
                            // alert(res);
                        },
                        onFinish: () => {
                            this.form_loading = false;
                        },
                        onError: (e) => {
                            this.form_loading = false;

                            if (e && e.error) {
                                (useToast()).clear();
                                (useToast()).error(e.error);
                            } else if (e != '') {
                                (useToast()).clear();
                                (useToast()).error(e);
                            }
                        },
                    })
                }
            });
        },
        showTerms() {
            $('.modal_terms').modal('show');
        }
    }
}
</script>

<style scoped>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}
</style>
