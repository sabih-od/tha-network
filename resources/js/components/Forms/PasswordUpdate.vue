<template>
    <form class="edit-card" @submit.prevent="submit">
        <a v-if="!isEdit" href="#" @click.prevent="isEdit = true" class="editProBtn">Edit</a>
        <template v-if="isEdit">
            <div class="d-flex">
                <template v-if="!form.processing">
                    <a href="#" @click.prevent="cancel" class="editProBtn w-auto">Cancel</a>
                    <a href="#" @click.prevent="submit" class="editProBtn ml-2">Save</a>
                </template>
                <a v-else href="#" @click.prevent disabled class="editProBtn w-auto">Saving...</a>
            </div>
        </template>
        <div class="cardWrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="oldpass">Your Current Password</label>
                        <span class="mr-4" v-if="pwh_is_visible">{{pwh}}</span>
                        <button v-if="pwhgi" class="btn btn-primary btn-sm" @click.prevent="pwh_is_visible = !pwh_is_visible">{{ pwh_is_visible ? 'Hide' : 'Show Current Password' }}</button>
                        <input type="password" name="oldpass" class="form-control"
                               placeholder="*********" v-model="form.oldpass" :readonly="!isEdit">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Create New Password</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="*********" v-model="form.password" :readonly="!isEdit">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Verify Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="*********" v-model="form.password_confirmation" :readonly="!isEdit">
                    </div>
                </div>
            </div>
            <p class="m-0">The password should be at least 8 characters long with (1 upper case letter,
                1 number, 1 special character (!@#$%^&*)</p>
        </div>
    </form>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from 'vue-toastification'

export default {
    name: "InfoUpdate",
    data() {
        return {
            isEdit: false,
            form: useForm({
                oldpass: '',
                password: '',
                password_confirmation: '',
            }),
            pwh_is_visible: false
        }
    },
    props: {
        stripe_account_id: String,
        paypal_account_details: String,
        pwh: String,
    },
    methods: {
        submit() {
            if (this.form.processing) return

            if(!this.stripe_account_id && !this.paypal_account_details) {
                return (useToast()).error('You Must Provide PayPal or Stripe account information before proceeding.  If you do not have a stripe or a Paypal account create one and return to this page and enter the information.', { timeout: false });
            }

            this.form.post(this.$route('updateProfile'), {
                replace: true,
                onSuccess: () => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                },
                onFinish: () => {
                    this.$store.dispatch('Utils/showErrorMessages').then(res => {
                        this.isEdit = false
                    })
                }
            })
        },
        cancel() {
            this.form.oldpass = ''
            this.form.password = ''
            this.form.password_confirmation = ''
            this.isEdit = false
        }
    }
}
</script>

<style scoped>

</style>
