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
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="one-pane" role="tabpanel"
                                 aria-labelledby="one-tab">
                                <form @submit.prevent="submit">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" v-model="form.email" id="email" placeholder=""
                                               class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="text" v-model="form.phone" id="phone" placeholder=""
                                               class="form-control">
                                    </div>
                                    <div class="form-group form-check mb-3">
                                        <h3>Where do you want to receive the code</h3>
                                        <template v-for="(type, key) in send_code_types" :key="key">
                                            <input type="radio" class="form-check-input1" :value="key"
                                                   :id="key+'_check'"
                                                   v-model="form.send_code_type">&nbsp;
                                            <label class="form-check-label1" :for="key+'_check'">{{ type }}</label>
                                        </template>
                                    </div>
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
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "SignUpInvitation",
    mixins: [utils],
    data() {
        return {
            send_code_types: {
                'email': 'Email',
                'phone': 'Phone',
            },
            form: useForm({
                email: "",
                phone: "",
                send_code_type: ""
            })
        }
    },
    methods: {
        submit() {
            if (this.form.processing) return;

            this.form.post(this.$route('sendInvitationCode'), {
                replace: true,
                onSuccess: () => {
                    this.form.reset()
                    this.showSuccessMessage()
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
