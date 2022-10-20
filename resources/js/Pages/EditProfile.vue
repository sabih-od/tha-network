<template>
    <section class="bg-grey editProfile pt-0">
        <div class="container">
            <div class="row jcc">
                <div class="col-md-10" v-if="has_made_monthly_payment">
                    <h2>Personal Information</h2>
                </div>
                <div class="col-md-10">
                    <BioUpdate v-if="has_made_monthly_payment" />

                    <InfoUpdate v-if="has_made_monthly_payment" />

                    <AddressUpdate v-if="has_made_monthly_payment" />

                    <PasswordUpdate v-if="has_made_monthly_payment" />

                    <MonthlyPayment
                        :client_secret="client_secret"
                        :monthly_payment_flash="monthly_payment_flash"
                        :has_made_monthly_payment="has_made_monthly_payment"
                    ></MonthlyPayment>

                    <CloseAccountModal></CloseAccountModal>

                    <div class="btn-group gap1" v-if="has_made_monthly_payment">
                        <button type="submit" class="themeBtn" @click.prevent="showWeeklyGoalNotification()">Save</button>
                        <button class="themeBtn discard">Discard Changes</button>
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
        CloseAccountModal
    },
    layout: ProfileLayout,
    props: {
        user: Object,
        profile: Object,
        client_secret: String,
        monthly_payment_flash: String,
        has_made_monthly_payment: Boolean
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

    },
    unmounted() {
        //un-hide message button
        $('.btn_message').prop('hidden', false);
        $('.btn_edit_avatar').prop('hidden', true);
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
                    _t.showPromotionNotification();
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
            let text = 'Your payment of $29.99 was made for your membership with THA NETWORK on (Date of Payment) Thanks for your Payment!! ';
            this.showNotification(img, text);

            //set newly registered back to false (flow ended)
            this.$store.commit('Misc/setIsNewlyRegistered', false);
        },
    }
}
</script>

<style scoped>

</style>
