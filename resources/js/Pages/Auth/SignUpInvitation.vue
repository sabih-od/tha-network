<template>
    <section class="loginSection">
        <div class="loginWrap">
            <div class="row mx-0 no-gutters">
                <div class="col-md-7">
                    <figure>
                        <img :src="asset('images/loginImg.png')" class="w-100" alt="">
                        <img :src="asset('images/user-logo.png')" class="login-logo"
                             alt="">
                    </figure>
                </div>

                <div class="col-md-5">
                    <div class="contentWrap invitationInner">
                        <a href="#"><img :src="asset('images/logo.png')" alt="logo"></a>
                        <h2>Get Invitation Code</h2>
<!--                        <h3>You may receive an invitation code by email or text.</h3>-->
                        <h3>Receive an invitation code via email</h3>
                        <br />
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="one-pane" role="tabpanel"
                                 aria-labelledby="one-tab">
                                <form @submit.prevent="submit">
<!--                                    <div class="form-group form-check mb-3">-->
<!--                                        <h3>Where do you want to receive the code</h3>-->
<!--                                        <template v-for="(type, key) in send_code_types" :key="key">-->
<!--                                            <input type="radio" class="form-check-input1" :value="key"-->
<!--                                                   :id="key+'_check'"-->
<!--                                                   v-model="form.send_code_type" required>&nbsp;-->
<!--                                            <label class="form-check-label1" :for="key+'_check'">{{ type }}</label>-->
<!--                                        </template>-->
<!--                                    </div>-->
<!--                                    <div class="form-group" v-if="form.send_code_type == 'email'">-->
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" v-model="form.email" id="email" placeholder="" class="form-control" :required="form.send_code_type == 'email'">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_email">Confirm Email</label>
                                        <input type="text" v-model="form.confirm_email" id="confirm_email" placeholder="" class="form-control" >
                                    </div>
<!--                                    <div class="form-group" v-if="form.send_code_type == 'phone'">-->
<!--                                        <label for="phone">Phone Number</label>-->
<!--                                        <input type="text" v-model="form.phone" id="phone" placeholder="" class="form-control" :required="form.send_code_type == 'phone'">-->
<!--                                    </div>-->
                                    <button type="submit" class="themeBtn" :disabled="form.processing">
                                        {{ form.processing ? 'Please wait...' : 'GET CODE' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import utils from "../../mixins/utils";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";

export default {
    name: "SignUpInvitation",
    mixins: [utils],
    data() {
        return {
            send_code_types: {
                'email': 'Email',
                'confirm_email': 'Confirm Email',
                'phone': 'Phone',
            },
            form: useForm({
                email: "",
                confirm_email: "",
                phone: "",
                send_code_type: "email"
            })
        }
    },
    methods: {
        submit() {
            if (this.form.processing) return;

            this.form.post(this.$route('sendInvitationCode'), {
                replace: true,
                onSuccess: () => {
                    setTimeout(() => console.log('asd'), 3000);
                    (useToast()).options = {
                        "showDuration": "3000",
                    };
                    (useToast()).success('The invitation code has been sent.');
                    this.form.reset()
                    // this.showSuccessMessage()
                },
                onFinish: () => {
                    this.showErrorMessage()
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
