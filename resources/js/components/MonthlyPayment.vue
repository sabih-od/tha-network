<template>
    <div class="edit-card">
        <div class="df aic jcsb mb-2">
            <h4>
                This month's subscription payment
                <h6 v-if="!has_made_monthly_payment" style="color:red;">{{ monthNames[new Date().getMonth()]+' Payment Due' }}</h6>
                <h6 v-if="has_made_monthly_payment" style="color:green;">{{ monthNames[new Date().getMonth()]+' Payment Clear' }}</h6>
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

export default {
    name: "MonthlyPayment",
    mixins: [utils],
    props: {
        client_secret: String,
        monthly_payment_flash: String,
        has_made_monthly_payment: Boolean
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
                (useToast()).success(this.monthly_payment_flash);
            }, 4000);
        }

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
        }
    }
}
</script>

<style scoped>

</style>
