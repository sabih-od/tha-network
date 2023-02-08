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

                        <form>
                            <!-- Add a hidden field with the lookup_key of your Price -->

                            <div class="form-group">
                                <label for="">Card Number</label>
                                <input type="number" name="card_no" v-model="card_number" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Expiration Month</label>
                                <input type="number" name="exp_mon" v-model="exp_month" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Expiration Year</label>
                                <input type="number" name="exp_year" v-model="exp_year" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">CCV Code</label>
                                <input type="password" name="cvv" v-model="cvc" class="form-control">
                            </div>
                            <button class="themeBtn" id="checkout-and-portal-button" type="button" @click.prevent="stripe_subscribe" :disabled="form_loading">
                                {{ form_loading ? 'Please Wait' : 'Subscribe' }}</button>
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

export default {
    name: "StripePayment",
    mixins: [utils],
    props: {
        client_secret: String,
        checkout_session: Object,
        error: String
    },
    watch: {
        checkout_session: function(nVal, oVal) {
            if(nVal) {
                window.location.href = this.checkout_session.url;
            }
        },
        error: function(nVal, oVal) {
            if(nVal) {
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
    },
    data() {
        return {
            card_number: '',
            exp_month: '',
            exp_year: '',
            cvc: '',
            form_loading: false,
        }
    },
    mounted() {

    },
    methods: {
        async stripe_subscribe() {
            this.form_loading = true;
            Inertia.post(this.$route('createStripeCheckoutSession'), {
                card_number: this.card_number,
                exp_month: this.exp_month,
                exp_year: this.exp_year,
                cvc: this.cvc
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {
                    console.log(res);
                    // alert(res);
                },
                onFinish: () => {
                    this.form_loading = false;
                }
            })
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
