<template>
    <div class="modal fade modal_terms" ref="modal_terms" id="modal_terms" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Terms & Conditions</h5>
                </div>
                <div class="modal-body">
                    <p>Due to California having the strictest online membership laws. Let's make sure to include the following disclosure on the payment page with an acknowledgement check field which state that I agree and acknowledge the terms disclosed. An automated email receipt of the disclosure should be sent to the new member along with a welcome to the network message.</p>

                    <p>
                        <strong>As a member of ThaNetwork each member's payment method will incur a monthly membership fee of $29.99 for the life of the member's membership. Monthly payments will be deducted from the member's payment method on the 1st day of each month. A member may end their membership at any time by cancelling the membership from the member's Profile Edit page. If you understand and agree with the terms, acknowledge this agreement by selecting the box below.</strong>
                        (I agree and acknowledge to the recurring terms listed above)
                    </p>

                    <h5 style="text-decoration: underline;">Requirements</h5>
                    <ol>
                        <li>When a member joins ThaNetwork, a payment receipt will be sent to the member's personal email address each time a payment is deducted from the member's payment method, and when payments are made to the member.</li>
                        <li>Membership payments will be made deducted on the 1st of each month. When new member's join they must be a member for 30days before the first automated payment is deducted from the member's payment method.</li>
                        <li>Membership payments will be credited to each member's account on the 15th of each month. New referral payment credit will not be paid until the new member has been a member for 30 days.</li>
                        <li>When a new member joins ThaNetwork, the referring member will not receive payment until the payment has been processed. Once the payment is processed the funds will be credited to the member's account and the member will receive an email and notification of receiving payment with the new member's name and username included in the message.</li>
                        <li>If a member cancels their membership they will not receive a refund, and if the member has referred member's in their personal network they will no longer receive payments for those members. The payments will go to the company</li>
                    </ol>

                    <p>
                        <strong>California State Law for Recurring Online Membership Payments</strong>
                        (This information is not required to be added to the page, just added here for information purposes)
                        A businesses is required to:
                    </p>
                    <ol>
                        <li>clearly and conspicuously disclose certain automatic renewal offer terms</li>
                        <li>obtain consumers’ affirmative consent to the automatic renewal terms;</li>
                        <li>provide an acknowledgment after purchase that includes the automatic renewal terms and cancellation policy in a manner that can be retained by the consumer</li>
                        <li>provide consumers with a readily-accessible mechanism for canceling an automatic renewal contract; and</li>
                        <li>notify consumers about any material changes to the automatic renewal terms</li>
                    </ol>

                    <h5 style="text-decoration: underline;">
                        <strong>The FTC’s new Enforcement Policy</strong>
                    </h5>
                    <p>
                        Statement provides a roadmap for how the FTC may enforce against allegedly unfair and deceptive practices related to automatically recurring subscriptions.
                    </p>
                    <ul>
                        <li class="ml-5" style="list-style-type: circle;">
                            <strong>Clearly and conspicuously disclose material terms of the transaction</strong>
                            such as the amount that consumers will be charged, the frequency of any recurring charges, any deadlines by which consumers must take actions to prevent the charges, and information necessary to cancel the contract.
                        </li>
                        <li class="ml-5" style="list-style-type: circle;">
                            <strong>Obtain consumers’ express, informed consent</strong>
                            before charging the consumer.
                        </li>
                        <li class="ml-5" style="list-style-type: circle;">
                            <strong>Provide simple and easy cancellation methods</strong>
                            that are as easy to use as the method the consumer used to sign up for the subscription.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PostMainData from "./PostMainData";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import utils from "../mixins/utils";

export default {
    name: "TermsModal",
    mixins: [utils],
    components: {
    },
    computed: {

    },
    data() {
        return {
            modal: null,
        }
    },
    mounted() {
        const modalEl = this.$refs.modal_terms
        this.modal = new bootstrap.Modal(modalEl, {
            keyboard: false,
            backdrop: 'static'
        })
    },
    unmounted() {
        // this.$emitter.off('share-post-modal')
    },
    methods: {
        show() {
            this.modal.show()
        },
        hide() {
            // this.modal.hide()
            $('.modal_terms').modal('hide');
        },
    }
}
</script>

<style scoped>
.modal-header {
    background: var(--primary);
}

.modal-title {
    color: #fff;
}
</style>
