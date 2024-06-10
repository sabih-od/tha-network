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
                                <label for="">First Name</label>
                                <input type="text" name="customer_first_name" v-model="customer_first_name"
                                       class="form-control"
                                       placeholder="Enter First Name">
                            </div>
                            <div class="form-group">
                                <label for="">Last Name</label>
                                <input type="text" name="customer_last_name" v-model="customer_last_name"
                                       class="form-control"
                                       placeholder="Enter Last Name">
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="customer_email" v-model="customer_email" class="form-control"
                                       placeholder="Enter Email Address">
                            </div>

                            <div class="form-group">
                                <label for="confirm_customer_email">Confirm Email</label>
                                <input type="email" name="confirm_customer_email" v-model="confirm_customer_email"
                                       class="form-control"
                                       placeholder="Enter Email Address">
                            </div>

                            <div class="form-group">
                                <label for="">Address</label>
                                <textarea type="text" name="customer_address" v-model="customer_address"
                                          class="form-control"
                                          placeholder="Enter Address"></textarea>
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
                                <label for="">CVV Code</label>
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
                            <!--                            <button v-if="!is_excluded_country" class="themeBtn" id="checkout-and-portal-button"-->
                            <!--                                    type="button" @click.prevent="stripe_subscribe" :disabled="form_loading">-->
                            <!--                                {{ form_loading ? 'Please Wait' : 'Subscribe' }}-->
                            <!--                            </button>-->

                            <div class="d-flex align-items-center justify-content-between">
                                <button v-if="!is_excluded_country" class="themeBtn" id="checkout-and-portal-button"
                                        type="button" @click.prevent="checkStripeSupportCountry"
                                        :disabled="form_loading">
                                    {{ form_loading ? 'Please Wait' : 'Subscribe' }}
                                </button>

                                <span v-else>Not available in your country.</span>

                                <button class="themeBtn backBtn" @click="backToHome" type="button">
                                    <i class="fas fa-chevron-left mr-1"></i> Home
                                </button>
                            </div>

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
                                <label for="">First Name</label>
                                <input type="text" name="customer_first_name" v-model="customer_first_name"
                                       class="form-control"
                                       placeholder="Enter First Name">
                            </div>
                            <div class="form-group">
                                <label for="">Last Name</label>
                                <input type="text" name="customer_last_name" v-model="customer_last_name"
                                       class="form-control"
                                       placeholder="Enter Last Name">
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="customer_email" v-model="customer_email" class="form-control"
                                       placeholder="Enter Email Address">
                            </div>

                            <div class="form-group">
                                <label for="confirm_customer_email">Confirm Email</label>
                                <input type="email" name="confirm_customer_email" v-model="confirm_customer_email"
                                       class="form-control"
                                       placeholder="Enter Email Address">
                            </div>

                            <div class="form-group">
                                <label for="">Address</label>
                                <textarea type="text" name="customer_address" v-model="customer_address"
                                          class="form-control"
                                          placeholder="Enter Address"></textarea>
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
                                <label for="">CVV Code</label>
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
                            <!--                            <button v-if="!is_excluded_country" class="themeBtn" id="checkout-and-portal-button"-->
                            <!--                                    type="button" @click.prevent="stripeCharge" :disabled="form_loading">-->
                            <!--                                {{ form_loading ? 'Please Wait' : 'Subscribe' }}-->
                            <!--                            </button>-->

                            <div class="d-flex align-items-center justify-content-between">
                                <button v-if="!is_excluded_country" class="themeBtn" id="checkout-and-portal-button"
                                        type="button" @click.prevent="checkStripeSupportCountry"
                                        :disabled="form_loading">
                                    {{ form_loading ? 'Please Wait' : 'Subscribe' }}
                                </button>

                                <span v-else>Not available in your country.</span>

                                <button class="themeBtn backBtn" @click="backToHome" type="button">
                                    <i class="fas fa-chevron-left mr-1"></i> Home
                                </button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <teleport to="body">
        <TermsModal/>
    </teleport>

    <div class="modal fade modal_stripe_country_support mt-5" ref="modal_stripe_country_support"
         id="modal_stripe_country_support"
         tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> What Country do you reside in</h5>
                </div>
                <div class="modal-body">
                    <p class="pt-2">Please select your country of residence to verify service coverage in your area.</p>
                    <!--                    <input type="text" class="form-control pt-2" v-model="client_stripe_country"-->
                    <!--                           placeholder="Please enter country" required @keyup.enter="vaidateStripeSupport"/>-->

                    <select class="form-control pt-2" v-model="client_stripe_country" required>
                        <option value="" disabled>Please select a country</option>
                        <option v-for="country in supportedCountries" :key="country" :value="country">
                            {{ formatCountryName(country) }}
                        </option>
                    </select>

                    <button class="themeBtn mt-4 float-right" @click="vaidateStripeSupport">Submit</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import utils from "../mixins/utils";
import {Link} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {useToast} from "vue-toastification";
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
        TermsModal,
    },
    data() {
        return {
            customer_email: '',
            customer_first_name: '',
            customer_last_name: '',
            customer_address: '',
            confirm_customer_email: '',
            card_number: '',
            exp_month: '',
            exp_year: '',
            cvc: '',
            form_loading: false,
            agree_terms: false,
            is_excluded_country: false,
            client_stripe_country: '',
            supportedCountries: [
                "australia",
                "austria",
                "belgium",
                "brazil",
                "bulgaria",
                "canada",
                "croatia",
                "cyprus",
                "czech-republic",
                "denmark",
                "estonia",
                "finland",
                "france",
                "germany",
                "gibraltar",
                "greece",
                "hong-kong",
                "hungary",
                "ireland",
                "italy",
                "japan",
                "latvia",
                "liechtenstein",
                "lithuania",
                "luxembourg",
                "malaysia",
                "malta",
                "mexico",
                "netherlands",
                "new-zealand",
                "norway",
                "poland",
                "portugal",
                "romania",
                "singapore",
                "slovakia",
                "slovenia",
                "spain",
                "sweden",
                "switzerland",
                "thailand",
                "united-arab emirates",
                "united-kingdom",
                "united-states"
            ]

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

        formatCountryName(country) {
            return country.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },

        backToHome() {
            Inertia.get(this.$route('howItWorks'), {}, {})

        },

        checkStripeSupportCountry() {
            $('.modal_stripe_country_support').modal('show');

        },

        async vaidateStripeSupport() {
            // INPUT COUNTRY VALIDATION
            // const country = this.client_stripe_country.toLowerCase().replace(/\s+/g, '-');
            // if (this.supportedCountries.includes(country)) {
            //     $('.modal_stripe_country_support').modal('hide');
            //     (useToast()).success('Country validate successfully, Please wait!');
            //
            //     if (this.isMonthsFirst) {
            //         await this.stripe_subscribe();
            //     } else {
            //         await this.stripeCharge();
            //     }
            // } else {
            //     $('.modal_stripe_country_support').modal('hide');
            //
            //     // Inertia.get(this.$route('home'), {}, {})
            //     (useToast()).error('ThaNetwork.org does not currently provide membership in your residing country' +
            //         ' Thank You for your interest!');
            // }

            const country = this.client_stripe_country;
            if (country != '') {
                $('.modal_stripe_country_support').modal('hide');
                if (this.isMonthsFirst) {
                    await this.stripe_subscribe();
                } else {
                    await this.stripeCharge();
                }
            } else {
                $('.modal_stripe_country_support').modal('hide');

                // Inertia.get(this.$route('home'), {}, {})
                (useToast()).error("ThaNetwork.org currently doesn't support membership in all countries. Please select a country from the list provided. Thank you for your interest!");
            }
        },

        async stripe_subscribe() {
            if (!this.agree_terms) {
                (useToast()).error('You must acknowledge the Terms and Conditions by checking the box above before continuing.');
                return;
            }
            (useToast()).success('Country validate successfully, Please wait!');

            this.form_loading = true;

            Inertia.post(this.$route('createStripeCheckoutSession'), {
                customer_email: this.customer_email,
                customer_first_name: this.customer_first_name,
                customer_last_name: this.customer_last_name,
                customer_address: this.customer_address,
                confirm_customer_email: this.confirm_customer_email,
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
                    console.log("eee", e)

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
                (useToast()).success('Country validate successfully, Please wait!');

                if (res.error) {
                    // Handle validation errors from Stripe
                    console.log("Stripe validation error:", res.error.message);
                    (useToast()).clear();
                    (useToast()).error(res.error.message);
                    this.form_loading = false;
                } else if (res.id) {
                    Inertia.post(this.$route('createStripeCheckoutSession'), {
                        token_id: res.id,
                        customer_email: this.customer_email,
                        customer_first_name: this.customer_first_name,
                        customer_last_name: this.customer_last_name,
                        customer_address: this.customer_address,
                        confirm_customer_email: this.confirm_customer_email,
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
                            console.log("e", e)
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
            }).catch(error => {
                // Handle unexpected errors
                console.log("Unexpected error:", error);
                (useToast()).clear();
                (useToast()).error("An error occurred while processing your card. Please try again with correct card details.");
                this.form_loading = false;
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
