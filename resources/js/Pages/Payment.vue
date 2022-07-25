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
                            <img src="images/payment.png" alt="">
                        </div>
                        <form @submit.prevent="">
<!--                            <h3 class="text-secondary" v-if="formLoading">Please wait...</h3>-->
<!--                            <div id="payment-element"></div>-->
                            <div class="row">
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
                            </div>
                            <Link type="button" class="themeBtn" :href="$route('registerForm')" replace>
                                CONFIRM PAYMENT
                            </Link>
                            <p class="color-grey mt-3">This payment information will be used for recurring payments
                                every month. If you would like to cancel recurring payments go to your edit profile page
                                to stop recurring payments.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import utils from "../mixins/utils";

export default {
    name: "Payment",
    mixins: [utils],
    props: {
        client_secret: String
    },
    data() {
        return {
            elements: null,
            formLoading: true
        }
    },
    mounted() {
        // console.log(this.client_secret, document.head.querySelector('#stripe-js'))
        /*if (!document.head.querySelector('#stripe-js')) {
            const scriptTag = document.createElement('script')
            scriptTag.src = 'https://js.stripe.com/v3/'
            scriptTag.onload = () => {
                console.log('script loaded')
                this.initialize()
            }
            document.head.appendChild(scriptTag)
        }else{
            this.initialize()
        }*/
    },
    methods: {
        initialize() {
            const stripe = Stripe("pk_test_asd5yoHRyR9Wn14y6FwNcrCm");
            this.elements = stripe.elements({clientSecret: this.client_secret})

            const paymentElement = this.elements.create("payment");
            paymentElement.mount("#payment-element");

            this.formLoading = false
        }
    }
}
</script>

<style scoped>

</style>
