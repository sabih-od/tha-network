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
                    :readonly="!isEdit">
                    <option value="married">Married</option>
                    <option value="single">Single</option>
                </select>

                <label for="bio">Gender</label>
                <select
                    id="gender"
                    v-model="form.gender"
                    class="form-control"
                    :readonly="!isEdit">
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

export default {
    name: "BioUpdate",
    data() {
        return {
            isEdit: false,
            form: useForm({
                bio: usePage().props.value?.profile?.bio,
                marital_status: usePage().props.value?.profile?.marital_status,
                gender: usePage().props.value?.profile?.gender,
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

            if(!this.stripe_account_id && !this.paypal_account_details) {
                return (useToast()).error('You Must Provide PayPal or Stripe account information before proceeding.  If you do not have a stripe or a Paypal account create one and return to this page and enter the information.', { timeout: false });
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
        showSuccessMessage () {
            if (!this.form.clear_all) {
                this.$store.dispatch('Utils/showSuccessMessage')
            }
        },
        discardChanges () {
            this.form.bio = '';
            this.form.marital_status = '';
            this.form.gender = '';
            this.form.clear_all = true;
            this.submit();
        }
    }
}
</script>

<style scoped>

</style>
