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
            <div class="form-group mb-0">
                <label for="bio">bio</label>
                <textarea
                    id="bio"
                    v-model="form.bio"
                    cols="10"
                    class="form-control"
                    placeholder="Enter Bio"
                    :readonly="!isEdit"
                    rows="3"></textarea>

                <label for="bio">Marital Status </label>
                <select
                    id="marital_status"
                    v-model="form.marital_status"
                    class="form-control"
                    :disabled="!isEdit">
                    <option value="married">Married</option>
                    <option value="single">Single</option>
                </select>

                <label for="bio">Gender</label>
                <select
                    id="gender"
                    v-model="form.gender"
                    class="form-control"
                    :disabled="!isEdit">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
        </div>
    </form>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from 'vue-toastification'
import {Inertia} from "@inertiajs/inertia";
import {mapGetters, mapState} from "vuex";

export default {
    name: "BioUpdate",
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
                bio: usePage().props.value?.profile?.bio,
                marital_status: usePage().props.value?.profile?.marital_status,
                gender: usePage().props.value?.profile?.gender,
            }),
            discardForm1: useForm({
                bio: '',
                marital_status: '',
                gender: '',
            }),
            discardForm2: useForm({
                address: '',
                country: '',
                city: '',
                postal_code: '',
                clear_all: true
            }),
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
            if (!this.stripe_account_id && !this.isAdmin) {
                return (useToast()).error('You must Create a Stripe account or log into your Stripe account by selecting the “Create Stripe Account” button before continuing.', {timeout: false});
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
            this.form.bio = usePage().props.value?.profile?.bio
            this.form.marital_status = usePage().props.value?.profile?.marital_status
            this.isEdit = false
        },
        showSuccessMessage() {
            this.$store.dispatch('Utils/showSuccessMessage')
        },
        discardChanges() {
            this.form.bio = '';
            this.form.marital_status = '';
            this.form.gender = '';

            this.discardForm1.post(this.$route('updateProfile'), {
                replace: true,
                onSuccess: () => {
                    this.discardForm2.post(this.$route('updateProfile'), {
                        replace: true,
                        onSuccess: () => {
                            this.showSuccessMessage();
                            return window.location.reload();
                            // Inertia.get(this.$route('editProfileForm'));
                        },
                        onFinish: () => {

                        }
                    })
                },
                onFinish: () => {

                }
            })
        }
    }
}
</script>

<style scoped>

</style>
