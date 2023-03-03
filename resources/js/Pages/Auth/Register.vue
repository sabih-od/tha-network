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
                        <h2>Create Profile</h2>
                        <form @submit.prevent="submit">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">First Name</label>
                                        <input type="text" id="fname" v-model="form.first_name" placeholder=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" id="lname" v-model="form.last_name" placeholder=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lname">Gender</label>
                                        <select id="gender" v-model="form.gender" class="form-control">
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label for="username">UserName</label>
                                        <input type="text" id="username" v-model="form.username" placeholder=""
                                               class="form-control" @keydown.space.prevent>
                                    </div>
                                    <!--                                    <p class="color-danger">The username is already taken try to use a different
                                                                            one*</p>-->
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" v-model="form.email" placeholder=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="phone">Phone</label>
                                        <input type="number"  id="phone" v-model="form.phone" placeholder=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" id="password" v-model="form.password" placeholder=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" id="cpassword" v-model="form.password_confirmation"
                                               placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p class="color-black">The password should be at least 8 characters long with (1
                                        upper case letter, 1 number, 1 special character (!@#$%^&*)</p>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <input id="is_non_us_citizen" type="checkbox"
                                               placeholder="" v-model="is_non_us_citizen" @change="toggleIsNonUsCitizen">
                                        <label class="ml-2" for="is_non_us_citizen">Non United States Citizens check here</label>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="securityNo">Social Security Number</label>
                                        <input type="text" id="securityNo" v-model="form.social_security_number" maxlength="11"
                                               placeholder="" class="form-control" :readonly="is_non_us_citizen" :disabled="is_non_us_citizen" :required="!is_non_us_citizen" :aria-required="!is_non_us_citizen" >
                                    </div>
                                    <p class="color-danger">All United State citizens/residents are required to enter
                                        their social security number for Tax purposes. Your information will never be
                                        shared or used for any other purposes. If a social is
                                        not provided for US citizens/residents payments will not be distributed until
                                        your social is provided."</p>
                                </div>
                            </div>
                            <button type="submit" class="themeBtn">
                                {{ form.processing ? 'Please wait...' : 'NEXT' }}
                            </button>
                        </form>
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
import {useForm, Link} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import utils from "../../mixins/utils";

export default {
    name: "Register",
    mixins: [utils],
    props: {
        errors: Object,
        inviter_id: null
    },

    mounted() {
        console.log(this.inviter_id);
        this.showPaymentMadeNotification();

        //format social security number
        $('#securityNo').on('keydown keyup mousedown mouseup', function() {
            let val = $(this).val().replace(/[^0-9]/g, '');
            if (val.length == 4) {
                let res = val.substr(0, 3) + '-' + val.substr(3, 1);
                $(this).val(res);
            } else if (val.length == 6) {
                let res = val.substr(0, 3) + '-' + val.substr(3, 2) + '-' + val.substr(5, 1);
                $(this).val(res);
            } else if (val.length == 9) {
                let res = val.substr(0, 3) + '-' + val.substr(3, 2) + '-' + val.substr(5, 4);
                $(this).val(res);
            }
        });
    },
    data() {
        return {
            form: useForm({
                first_name: '',
                last_name: '',
                gender: '',
                username: '',
                email: '',
                phone: '',
                password: '',
                password_confirmation: '',
                social_security_number: '',
                inviter_id: this.inviter
            }),
            notification_modal: {
                text: '',
                img: '',
                class: 'notifyPopup',
                redirect_url: "#",
                on_click: this.hideNotification
            },
            is_non_us_citizen: false
        }
    },
    methods: {
        submit() {
            //change state var for newly registered account
            this.$store.commit('Misc/setIsNewlyRegistered', true);
            // alert('first', this.$store.getters['Misc/isNewlyRegistered']);

            //register
            this.form.post(this.$route('register'), {
                replace: true,
                onSuccess: () => {
                    this.form.reset()
                },
                onFinish: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                }
            })
        },

        // myfun(){
        // var x= document.getElementById("is_non_us_citizen").checked;
        // if(x==true){
        //     alert("checked");
        // }
        // else {
        //     alert("unchecked");
        // }
        // },

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
        showPaymentMadeNotification() {
            this.hideNotification();

            //PaymentMade
            let img = this.$store.getters['Utils/public_asset']('images/notifications/PaymentMade.png');
            let text = 'Your payment of $29.99 was made for your membership with THA NETWORK Thanks for your Payment!! ';
            this.showNotification(img, text);

            //set newly registered back to false (flow ended)
            this.$store.commit('Misc/setIsNewlyRegistered', false);
        },
        toggleIsNonUsCitizen () {
            if(this.getElementById("is_non_us_citizen").value=="Yes")
            {
                this.getElementById("securityNo");
            }
            else
            {
                this.getElementById("securityNo").required;
            }
        }
    }

}
</script>

<style scoped>

</style>
