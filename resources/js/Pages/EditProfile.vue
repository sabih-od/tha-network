<template>
    <section class="bg-grey editProfile pt-0">
        <div class="container-md">
            <div class="row jcc">
                <div class="col-12">
                    <h2>Personal Information</h2>
                </div>
                <Link :href="$route('loginForm')" class="btn themeBtn mb-2" style="color: white;">
                    <span><h5 class="m-auto">Back to profile</h5></span>
                </Link>
                <div class="col-12">
                    <h3>Referral Payment Options</h3>
                    <h6>In order to receive Referral Payments you must include your Paypal or Stripe Account information.  If you do not have a Stripe or Paypal Account create one and provide the information below.If this information is not provided, you will not be able to receive your referral payments.</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>
                                Stripe
                                <input type="radio" name="preferred_payout_method" :checked="preferred_payout_form.preferred_payout_method === 'stripe'" @click="submitPreferredPayoutForm('stripe')" style="transform: scale(1.5);">
                            </h4>

                            <!--badge-->
<!--                            <span :class="'badge badge-pill badge-' + ((this.stripe_account_id && this.has_provided_stripe_payout_information) ? 'success' : 'danger')">{{ (this.stripe_account_id && this.has_provided_stripe_payout_information) ? 'Connected' : 'Not Connected' }}</span>-->
<!--                            <br />-->

                            <!--button-->
                            <button type="button" class="btn btn-success btn-sm" :disabled="preferred_payout_form.preferred_payout_method === 'paypal'" @click.prevent="connectStripeAccount()">{{ (this.stripe_account_id && this.has_provided_stripe_payout_information && preferred_payout_form.preferred_payout_method !== 'paypal') ? 'Connected' : 'Connect' }}</button>
                            <br />

<!--                            <span v-if="this.stripe_account_id && this.has_provided_stripe_payout_information">You have successfully connected your Stripe account.</span>-->
                            <span v-if="preferred_payout_form.preferred_payout_method === 'stripe' && this.stripe_account_id && this.has_provided_stripe_payout_information">You have successfully connected your Stripe account.</span>
                        </div>
                        <div class="col-md-6">
                            <h4>
                                Paypal
                                <input type="radio" name="preferred_payout_method" :checked="preferred_payout_form.preferred_payout_method === 'paypal'" @click="submitPreferredPayoutForm('paypal')" style="transform: scale(1.5);">
                            </h4>

                            <!--badge-->
<!--                            <span :class="'badge badge-pill badge-' + (this.paypal_account_details ? 'success' : 'danger')">{{ this.paypal_account_details ? 'Connected' : 'Not Connected' }}</span>-->
                            <input class="form-control" type="email" placeholder="Paypal Email" v-model="paypalForm.paypal_account_details">
                            <br />

                            <!--button-->
                            <button type="button" class="btn btn-success btn-sm" :disabled="preferred_payout_form.preferred_payout_method === 'stripe'" @click.prevent="connectPaypalAccount()">{{ (this.paypal_account_details && preferred_payout_form.preferred_payout_method !== 'stripe') ? 'Connected' : 'Connect' }}</button>
                            <br />

<!--                            <span v-if="this.paypal_account_details">You have successfully connected your Paypal account.</span>-->
                            <span v-if="preferred_payout_form.preferred_payout_method === 'paypal' && this.paypal_account_details">You have successfully connected your Paypal account.</span>
                        </div>
                        <br />

                        <span class="ml-3">
                            <strong>
                                <i class="fas fa-info" style="color: blue;"></i>
                                If you would like to change the payment account please select the check box above.
                            </strong>
                        </span>
                    </div>
                    <br />

                    <BioUpdate ref="bioUpdate" :stripe_account_id="stripe_account_id" :paypal_account_details="paypal_account_details" />

                    <InfoUpdate :stripe_account_id="stripe_account_id" :paypal_account_details="paypal_account_details" />

                    <AddressUpdate ref="addressUpdate" :stripe_account_id="stripe_account_id" :paypal_account_details="paypal_account_details" />

                    <PasswordUpdate :stripe_account_id="stripe_account_id" :paypal_account_details="paypal_account_details" :pwh="user?.pwh" />

                    <MonthlyPayment
                        :client_secret="client_secret"
                        :monthly_payment_flash="monthly_payment_flash"
                        :has_made_monthly_payment="has_made_monthly_payment"
                        :stripe_checkout_session_id="stripe_checkout_session_id"
                        :stripe_portal_session="stripe_portal_session"
                    ></MonthlyPayment>

                    <CloseAccountModal></CloseAccountModal>

                    <ChangePreferredPayoutMethodModal :preferred_payout_method="preferred_payout_form.preferred_payout_method"></ChangePreferredPayoutMethodModal>

                    <div class="btn-group gap1">
                        <button v-if="$store.getters['Misc/isNewlyRegistered']" type="submit" class="themeBtn" @click.prevent="showWeeklyGoalNotification()">Update Profile</button>
                        <Link :href="$route('home')" class="themeBtn">Back To Profile</Link>
                        <button class="themeBtn discard" @click="discardChanges">Discard Changes</button>
                    </div>

                    <br />
                    <br />
                    <div class="btn-group gap1">
                        <button @click.prevent="closeAccountModal()" type="button" class="dangerBtn">CLOSE MY ACCOUNT</button>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <div :class="notification_modal.class" style="z-index: 999;">
        <div class="notiImgCont">
            <figure>
                <img :src="notification_modal.img" alt="">
            </figure>
        </div>
        <div class="notiBody">
            <p v-html="notification_modal.text"></p>
        </div>
        <div class="notiFooter">
            <Link v-if="notification_modal.redirect_url != '#'" @click.prevent="notification_modal.on_click" :href="notification_modal.redirect_url"><i class="fas fa-check"></i><span>Ok</span></Link>
            <button v-else @click.prevent="hideNotification()"><i class="fas fa-check"></i><span>Ok</span></button>
        </div>
    </div>
</template>

<script>
import Main from "../Layouts/Main";
import UserInfo from "../components/UserInfo";
import ProfileLeftSide from "../components/ProfileLeftSide";
import {useForm, usePage, Link} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import FormLoading from "../components/FormLoading";
import CoverPhoto from "../components/CoverPhoto";
import {Inertia} from "@inertiajs/inertia";
import ProfileLayout from "../Layouts/ProfileLayout";
import BioUpdate from "../components/Forms/BioUpdate";
import InfoUpdate from "../components/Forms/InfoUpdate";
import AddressUpdate from "../components/Forms/AddressUpdate";
import PasswordUpdate from "../components/Forms/PasswordUpdate";
import MonthlyPayment from "../components/MonthlyPayment";
import CloseAccountModal from "../components/CloseAccountModal";
import ChangePreferredPayoutMethodModal from "../components/ChangePreferredPayoutMethodModal";

export default {
    name: "EditProfile",
    components: {
        AddressUpdate,
        BioUpdate,
        InfoUpdate,
        PasswordUpdate,
        Main,
        UserInfo,
        Link,
        ProfileLeftSide,
        FormLoading,
        CoverPhoto,
        MonthlyPayment,
        CloseAccountModal,
        ChangePreferredPayoutMethodModal
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object,
        client_secret: String,
        monthly_payment_flash: String,
        has_made_monthly_payment: Boolean,
        stripe_account_id: String,
        paypal_account_details: String,
        stripe_checkout_session_id: String,
        stripe_portal_session: Object,
        has_provided_stripe_payout_information: Boolean,
        preferred_payout_method: String
    },
    computed: {
        userProfile() {
            return this.$store.state.Profile?.data
        },
        personalLinks() {
            let data = []
            if (this.userProfile?.personal_links) {
                let chunks = this.userProfile.personal_links.split('\n')
                for (const chunk of chunks) {
                    if (chunk)
                        data.push(chunk)
                }
            }
            return data
        },
        filteredBrandsForFemales () {

        }
    },
    data() {
        return {
            form: useForm({
                name: this.user?.name ?? '',
                email: this.user?.email ?? '',
                phone: this.profile?.phone ?? '',
                gender: this.profile?.gender ?? 'Male',
                dob: this.profile?.dob ?? '',
                marital_status: this.profile?.marital_status ?? 'Single',
                country_of_residence: this.profile?.country_of_residence ?? '',
                city: this.profile?.city ?? '',
                bio: this.profile?.bio ?? '',
                personal_links: this.profile?.personal_links ?? ''
            }),
            paypalForm: useForm({
                paypal_account_details: this.paypal_account_details
            }),
            preferred_payout_form: useForm({
                preferred_payout_method: this.preferred_payout_method
            }),
            genders: [
                'Male',
                'Female',
                'Rather Not Say'
            ],
            marital_statuses: [
                'Single',
                'Married',
                'Divorced',
                'Widowed',
                'Complicated'
            ],
            notification_modal: {
                text: '',
                img: '',
                class: 'notifyPopup',
                redirect_url: "#",
                on_click: this.hideNotification
            }
        }
    },
    mounted() {
        this.$store.commit('Profile/setProfile', this.profile);
        //hide message button
        $('.btn_message').prop('hidden', true);
        $('.btn_edit_avatar').prop('hidden', false);
        $('.info_edit_avatar').prop('hidden', false);
        $('.changePhoto').prop('hidden', false);

        $('.btn_add_friend').prop('hidden', true);
        $('.btn_accept_request').prop('hidden', true);
        $('.btn_reject_request').prop('hidden', true);
        $('.btn_unfriend').prop('hidden', true);
        $('.btn_block').prop('hidden', true);
        $('.btn_invite').prop('hidden', true);

        //show notification if user just paid
        let _t = this;
        this.$emitter.on('payment_made', function() {
            _t.showPaymentMadeNotification();
        });

        this.$emitter.on('revert_preferred_payout_method', function() {
            if (_t.preferred_payout_form.preferred_payout_method === "" || _t.preferred_payout_form.preferred_payout_method == null) {
                return;
            }
            _t.preferred_payout_form.preferred_payout_method = _t.preferred_payout_form.preferred_payout_method === 'stripe' ? 'paypal' : 'stripe';
        });

    },
    unmounted() {
        //un-hide message button
        $('.btn_message').prop('hidden', false);
        $('.btn_edit_avatar').prop('hidden', true);
        $('.info_edit_avatar').prop('hidden', true);
        $('.changePhoto').prop('hidden', true);
        $('.btn_invite').prop('hidden', false);
    },
    methods: {
        submit() {
            if (this.form.processing) return;
            this.form.post(this.$route('updateProfile'), {
                replace: true,
                onSuccess: () => {
                    (useToast()).clear();
                    (useToast()).success(usePage().props.value?.flash?.success ?? 'Profile updated successfully!');
                    this.$store.commit('Profile/setProfile', {
                        phone: this.profile?.phone,
                        gender: this.profile?.gender,
                        dob: this.profile?.dob,
                        marital_status: this.profile?.marital_status,
                        country_of_residence: this.profile?.country_of_residence,
                        city: this.profile?.city,
                        bio: this.profile?.bio,
                        personal_links: this.profile?.personal_links
                    })
                }
            })
        },
        closeAccountModal() {
            $('.modal_close_account').modal('show');
        },
        showNotification(img, text, redirect_url = "#", on_click = this.hideNotification) {
            this.notification_modal.img = img;
            this.notification_modal.text = text;
            this.notification_modal.redirect_url = redirect_url;
            this.notification_modal.on_click = on_click;
            this.notification_modal.class = 'notifyPopup show';
        },
        hideNotification() {
            this.notification_modal.class = 'notifyPopup'
        },
        showWeeklyGoalNotification() {
            //if newly registered - profile completed (SetWeeklyGoal)
            if(this.$store.getters['Misc/isNewlyRegistered']) {
                let img = this.$store.getters['Utils/public_asset']('images/notifications/SetWeeklyGoal.png');
                let text = 'Your Weekly goals has been set. Complete your goals to get promoted to the next grade';
                this.showNotification(img, text);

                let _t = this;
                setTimeout(function() {
                    // _t.showPromotionNotification();
                }, 4000);
            }
        },
        showPromotionNotification() {
            this.hideNotification();

            //AfterRegistrationAppPromotion
            let img = this.$store.getters['Utils/public_asset']('images/notifications/AfterRegistrationAppPromotion.png');
            let text = 'Now that you are a member and have completed setting up your account, please go to your App store and download the APP!! Let’s get started making some CASH!!!';
            this.showNotification(img, text);

            //set newly registered back to false (flow ended)
            this.$store.commit('Misc/setIsNewlyRegistered', false);
        },
        showPaymentMadeNotification() {
            this.hideNotification();

            //PaymentMade
            let img = this.$store.getters['Utils/public_asset']('images/notifications/PaymentMade.png');
            let text = 'Your payment of $29.99 was made for your membership with THA NETWORK Thanks for your Payment!! ';
            this.showNotification(img, text);

            //set newly registered back to false (flow ended)
            this.$store.commit('Misc/setIsNewlyRegistered', false);
        },
        connectStripeAccount() {
            Inertia.get(this.$route('connect-stripe'), {

            }, {
                replace: true,
                preserveScroll: true,
                preserveState: true,
                onStart: () => {
                    console.log('starting');
                },
                onSuccess: res => {
                    console.log('res: ', res.props.v_data);
                    window.location.href = res.props?.v_data;
                    window.onbeforeunload = function() {
                        history.back();
                    };
                },
                onFinish: () => {

                },
            })
        },
        connectPaypalAccount() {
            if(!this.paypalForm.paypal_account_details)
                return

            this.paypalForm.post(this.$route('connect-paypal'), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    this.form.reset();
                    this.propmtForAvatarCreation();
                },
                onError: () => {

                },
                onFinish: () => {
                    this.$store.dispatch('Utils/showErrorMessages');
                }
            })
        },
        propmtForAvatarCreation() {
            // $('.btn_edit_avatar').click();
            this.$emitter.emit('prompt_for_avatar_creation');
        },
        discardChanges () {
            this.$refs.addressUpdate.discardChanges();
            this.$refs.bioUpdate.discardChanges();
            (useToast()).success('You Have Discarded Your Changes.');
        },
        paypalInit () {
            // if (!document.head.querySelector('#stripe-js')) {
            //     const scriptTag = document.createElement('script')
            //     scriptTag.src = 'https://www.paypalobjects.com/js/external/api.js'
            //     scriptTag.onload = () => {
            //         console.log('script loaded')
            //
            //         paypal.use(['login'], function (login) {
            //             console.log("login", login)
            //             // login.render({
            //             //     "appid": "AcKwbyi3-LtcW9orYwnWecAHjTaU6SDpJ6JiVW6FIP3lO-9yY-DjWoPNoo6vTbfEW2Xitkmkiiz5O1le",
            //             //     "authend": "sandbox",
            //             //     "scopes": "email",
            //             //     "containerid":"paypalConnectContainer",
            //             //     "responseType": "code id_Token",
            //             //     "locale": "en-us",
            //             //     "buttonType": "CWP",
            //             //     "buttonShape": "pill",
            //             //     "buttonSize": "lg",
            //             //     "fullPage": "true",
            //             //     "returnurl": "http://127.0.0.1:8000/edit-profile"
            //             // });
            //         });
            //     }
            //     document.head.appendChild(scriptTag)
            // }

            // loadScript({
            //     "client-id": 'AcKwbyi3-LtcW9orYwnWecAHjTaU6SDpJ6JiVW6FIP3lO-9yY-DjWoPNoo6vTbfEW2Xitkmkiiz5O1le',
            //     "data-page-type": "connect",
            //     // "scope": "email",
            // })
            //     .then((paypal) => {
            //         // start to use the PayPal JS SDK script
            //         console.log(paypal)
            //         // paypal.use(['login'], function (login) {
            //         //     login.render({
            //         //         "appid": "AcKwbyi3-LtcW9orYwnWecAHjTaU6SDpJ6JiVW6FIP3lO-9yY-DjWoPNoo6vTbfEW2Xitkmkiiz5O1le",
            //         //         "authend": "sandbox",
            //         //         "scopes": "email",
            //         //         "containerid":"paypalConnectContainer",
            //         //         "responseType": "code id_Token",
            //         //         "locale": "en-us",
            //         //         "buttonType": "CWP",
            //         //         "buttonShape": "pill",
            //         //         "buttonSize": "lg",
            //         //         "fullPage": "true",
            //         //         "returnurl": "http://127.0.0.1:8000/edit-profile"
            //         //     });
            //         // });
            //
            //         paypal
            //             .Buttons()
            //             .render("#paypalConnectContainer")
            //             .catch((error) => {
            //                 console.error("failed to render the PayPal Buttons", error);
            //             });
            //     })
            //     .catch((err) => {
            //         console.error("failed to load the PayPal JS SDK script", err);
            //     });
        },
        submitPreferredPayoutForm (val) {
            this.preferred_payout_form.preferred_payout_method = val;
            $('.modal_change_preferred_payout_method_modal').modal('show');
        }
    }
}
</script>

<style scoped>

</style>
