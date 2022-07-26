<template>
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
                            <!--                            <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="cardname">Name on Card</label>
                                                                    <input type="text" name="cardname" placeholder="" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="cardNo">Card Number</label>
                                                                    <input type="text" name="cardNo" placeholder="" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-2">
                                                                    <label for="expireDate">Expiration Date</label>
                                                                    <input type="text" name="expireDate" placeholder="MM / YY" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="cvv">CVV Code</label>
                                                                    <input type="text" name="cvv" placeholder="" class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="zipCode">Billing Zipcode</label>
                                                                    <input type="text" name="zipCode" placeholder="" class="form-control">
                                                                </div>
                                                            </div>
                                                        </div>-->
                            <template v-if="!mountLoading">
                                <button type="submit" class="themeBtn mt-3">
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
</template>

<script>
import utils from "../mixins/utils";
import {useToast} from "vue-toastification";
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "Payment",
    mixins: [utils],
    props: {
        client_secret: String
    },
    data() {
        return {
            elements: null,
            stripe: null,
            mountLoading: true,
            formLoading: false,
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
        }
    }
}
</script>

<style scoped>

</style>
