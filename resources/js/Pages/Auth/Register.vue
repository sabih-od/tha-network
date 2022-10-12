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
                                               class="form-control">
                                    </div>
                                    <!--                                    <p class="color-danger">The username is already taken try to use a different
                                                                            one*</p>-->
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="email">Email</label>
                                        <input type="text" id="email" v-model="form.email" placeholder=""
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="phone">Phone</label>
                                        <input type="text" id="phone" v-model="form.phone" placeholder=""
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
                                        <label for="securityNo">Social Security Number</label>
                                        <input type="text" id="securityNo" v-model="form.social_security_number"
                                               placeholder="" class="form-control">
                                    </div>
                                    <p class="color-danger">All United State citizens/residents are required to enter
                                        their social security numbers for Tax purposes. Your information will never be
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
    <!--    <section class="loginSection">
            <div class="loginWrap">
                <div class="row align-items-center mx-0 no-gutters">
                    <div class="col-md-7">
                        <figure><img src="images/loginImg.png" alt=""></figure>
                    </div>

                    <div class="col-md-5">
                        <div class="contentWrap">
                            <a href="#"><img src="images/logo.png" alt="logo"></a>
                            <h2>Sign up</h2>
                            <form @submit.prevent="submit">
                                <div class="form-group" :class="{'mb-0': errors.name}">
                                    <i class="fas fa-user"></i>
                                    <input type="text" placeholder="Full Name" v-model="form.name" class="form-control">
                                </div>
                                <p v-if="errors.name" class="small text-danger">{{ errors.name }}</p>

                                <div class="form-group" :class="{'mb-0': errors.email}">
                                    <i class="fas fa-user"></i>
                                    <input type="email" v-model="form.email" placeholder="Email Adress"
                                           class="form-control">
                                </div>
                                <p v-if="errors.email" class="small text-danger">{{ errors.email }}</p>

                                <div class="form-group" :class="{'mb-0': errors.password}">
                                    <i class="fas fa-lock-open-alt"></i>
                                    <input type="password" v-model="form.password" placeholder="Password"
                                           class="form-control">
                                </div>
                                <p v-if="errors.password" class="small text-danger">{{ errors.password }}</p>

                                <div class="form-group" :class="{'mb-0': errors.password_confirmation}">
                                    <i class="fas fa-lock-open-alt"></i>
                                    <input type="password" v-model="form.password_confirmation"
                                           placeholder="Confirm Password" class="form-control">
                                </div>
                                <p v-if="errors.password_confirmation" class="small text-danger">
                                    {{ errors.password_confirmation }}</p>

                                <button type="submit">Sign Up</button>
                                <div class="df jcc aic">
                                    <p>Already Have an Account?
                                        <Link :href="$route('loginForm')" replace>Sign In</Link>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>-->
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
            })
        }
    },
    methods: {
        submit() {
            this.form.post(this.$route('register'), {
                replace: true,
                onSuccess: () => {
                    this.form.reset()
                },
                onFinish: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
