<template>
    <div class="edit-card">
        <div class="df aic jcsb mb-2">
            <h4>
                This month's subscription payment
                <button class="stripeBtn" @click.prevent="manageStripeSubscription" v-if="stripe_checkout_session_id">Manage Your Stripe Subscription</button>
<!--                <h6 v-if="!has_made_monthly_payment" style="color:red;">{{ monthNames[new Date().getMonth()]+' Payment Due' }}</h6>-->
                <h6 v-if="!has_made_monthly_payment" style="color:red;">{{ 'Payment Due ' + this_months_first }}</h6>
                <h6 v-if="has_made_monthly_payment" style="color:green;">{{ monthNames[new Date().getMonth()]+' Payment Clear' }}</h6>
                <h6 style="font-weight: 100; color: #817373; font-size: 14px;">Users must need to provide their payment details for receiving their referral payments.</h6>
            </h4>
            <div class="df aic gap1">
                <img src="images/payment2.png" alt="">
            </div>
        </div>
        <form @submit.prevent="submit" v-if="!has_made_monthly_payment">
            <h3 class="text-secondary" v-if="mountLoading">Please wait...</h3>
            <div id="monthly-payment-element"></div>
            <template v-if="!mountLoading">
                <button type="submit" class="themeBtn mt-3 mb-3">
                    {{ formLoading ? 'Please wait...' : 'CONFIRM PAYMENT' }}
                </button>
            </template>
        </form>
    </div>
</template>

<script>
import utils from "../mixins/utils";
import {useToast} from "vue-toastification";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "MonthlyPayment",
    mixins: [utils],
    props: {
        client_secret: String,
        monthly_payment_flash: String,
        stripe_checkout_session_id: String,
        has_made_monthly_payment: Boolean,
        stripe_portal_session: Object
    },
    computed: {
        this_months_first() {
            let todays_date = new Date((new Date()).getFullYear(), (new Date()).getMonth(), 1);
            let yyyy = todays_date.getFullYear();
            let mm = todays_date.getMonth() + 1;
            let dd = todays_date.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            return mm + '/' + dd + '/' + yyyy;
        },
    },
    data() {
        return {
            elements: null,
            stripe: null,
            mountLoading: true,
            formLoading: false,
            monthNames: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ]
        }
    },
    mounted() {
        //when successful monthly payment
        if(this.monthly_payment_flash) {
            setTimeout(() => {
                // (useToast()).success(this.monthly_payment_flash);
                this.$emitter.emit('payment_made');
            }, 4000);
        }

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
    watch: {
        stripe_portal_session: function(nVal, oVal) {
            if(nVal) {
                window.location.href = this.stripe_portal_session.url;
            }
        },
        "$page.props": function(e) {
            if(e.hasOwnProperty('errors') && e.errors.length > 0) {
                (useToast()).error(e.errors[0]);
            }
        }
    },
    methods: {
        initialize() {
            this.stripe = Stripe("pk_test_0rY5rGJ7GN1xEhCB40mAcWjg");
            this.elements = this.stripe.elements({clientSecret: this.client_secret})

            const paymentElement = this.elements.create("payment");
            paymentElement.mount("#monthly-payment-element");
            paymentElement.on('ready', () => {
                this.mountLoading = false
            })
        },
        async submit() {
            this.formLoading = true
            const {error} = await this.stripe.confirmPayment({
                elements: this.elements,
                confirmParams: {
                    return_url: this.$route('monthlySuccessPayment'),
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
        async manageStripeSubscription() {
            Inertia.post(this.$route('createStripePortalSession'), {

            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {
                    console.log(res);
                    // alert(res);
                },
            })
        }
    }
}
</script>

<style scoped>

</style>
