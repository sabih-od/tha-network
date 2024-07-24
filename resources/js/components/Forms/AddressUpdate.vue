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
                        <label for="address">Address</label>
                        <input type="text" id="address" v-model="form.address"
                               :disabled="!isEdit" class="form-control"
                               placeholder="Lorem ipsum 23578">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="country">Country</label>
                        <input type="text" id="country" class="form-control"
                               v-model="form.country"
                               :disabled="!isEdit"
                               placeholder="United State">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" class="form-control" v-model="form.city"
                               :disabled="!isEdit"
                               placeholder="New York">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="post_code">Postel/Zip Code</label>
                        <input type="text" id="post_code" class="form-control" v-model="form.postal_code"
                               :disabled="!isEdit" placeholder="564289">
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from 'vue-toastification'
import {mapGetters, mapState} from "vuex";

export default {
    name: "AddressUpdate",
    computed: {
        ...mapState({
            authUser: (state) => state.AuthUser.data
        }),
        ...mapGetters({
            isAdmin: 'AuthUser/isAdmin'
        })
    },
    data() {
        return {
            isEdit: false,
            form: useForm({
                address: usePage().props.value?.profile?.address,
                country: usePage().props.value?.profile?.country,
                city: usePage().props.value?.profile?.city,
                postal_code: usePage().props.value?.profile?.postal_code,
                clear_all: false
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

            if (!this.authUser?.role_id)
                return

            // if(!this.stripe_account_id && !this.paypal_account_details) {
            if (!this.stripe_account_id && !this.paypal_account_details && !this.isAdmin) {
                // return (useToast()).error('You must Create a Stripe account or log into your Stripe account by selecting the “Create Stripe Account” button before continuing.', {timeout: false});
                return (useToast()).error('Before continuing, please create or log into your Stripe or PayPal account below by selecting the "Create Stripe Account" or "Log into PayPal" button.', {timeout: false});
            }

            this.form.post(this.$route('updateProfile'), {
                replace: true,
                onSuccess: () => {
                    this.showSuccessMessage();
                },
                onFinish: () => {
                    if (this.form.clear_all) {
                        this.form.clear_all = false;
                    }
                    this.$store.dispatch('Utils/showErrorMessages').then(res => {
                        this.isEdit = false
                    })
                }
            })
        },
        cancel() {
            this.form.address = usePage().props.value?.profile?.address
            this.form.country = usePage().props.value?.profile?.country
            this.form.city = usePage().props.value?.profile?.city
            this.form.postal_code = usePage().props.value?.profile?.postal_code
            this.isEdit = false
        },
        showSuccessMessage() {
            if (!this.form.clear_all) {
                this.$store.dispatch('Utils/showSuccessMessage')
            }
        },
        discardChanges() {
            this.form.address = '';
            this.form.country = '';
            this.form.city = '';
            this.form.postal_code = '';
        }
    }
}
</script>

<style scoped>

</style>
