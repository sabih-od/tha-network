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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" id="fname" v-model="form.first_name" class="form-control" placeholder="John"
                               :readonly="!isEdit">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" id="lname" v-model="form.last_name" class="form-control" placeholder="Smit"
                               :readonly="!isEdit">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" v-model="form.email" class="form-control"
                               :readonly="!isEdit"
                               placeholder="Johnsmit23@gmail.com">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" v-model="form.phone" class="form-control"
                               :readonly="!isEdit"
                               placeholder="+123-456-789">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="phone">Username</label>
                        <input type="text" id="phone" v-model="form.username" class="form-control"
                               :readonly="!isEdit"
                               placeholder="Username"
                               @keydown.space.prevent>
                    </div>
                </div>
            </div>
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
                first_name: usePage().props.value?.profile?.first_name,
                last_name: usePage().props.value?.profile?.last_name,
                phone: usePage().props.value?.profile?.phone,
                email: usePage().props.value?.auth?.email,
                username: usePage().props.value?.auth?.username,
            })
        }
    },
    props: {
        stripe_account_id: String,
        paypal_account_details: String,
    },
    methods: {
        submit() {
            if (this.form.processing) return

            // if(!this.stripe_account_id && !this.paypal_account_details) {
            if(!this.stripe_account_id) {
                return (useToast()).error('You Must Provide  Stripe account information before proceeding.  If you do not have a stripe account create one and return to this page and enter the information.', { timeout: false });
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
            this.form.first_name = usePage().props.value?.profile?.first_name
            this.form.last_name = usePage().props.value?.profile?.last_name
            this.form.phone = usePage().props.value?.profile?.phone
            this.form.email = usePage().props.value?.auth?.email
            this.isEdit = false
        }
    }
}
</script>

<style scoped>

</style>
