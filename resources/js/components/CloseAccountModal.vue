<template>
    <div class="modal fade modal_close_account" ref="closeAccountModal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                </div>
                <div class="modal-body">
                    <h5><small>If you choose <span style="color: red;">YES</span>, your account will be <span style="color: red;">Permantly Closed</span>, All Membership re-occurring payments will stop, All Referral Payments made to you will Stop, and You will lose your entire Referral Network.</small></h5>
                    <form @submit.prevent="submit">
                        <div class="form-group float-right">
                            <button type="button" class="themeBtnSmall mr-2" @click.prevent="hide">No</button>
                            <button type="submit" class="dangerBtn">YES</button>
                        </div>
                    </form>
                </div>
                <!--                <div class="modal-footer">-->
                <!--                    <button type="button" class="btn btn-primary" @click.prevent="sendInvite()">Send</button>-->
                <!--                </div>-->
            </div>
        </div>
    </div>
</template>

<script>
import PostMainData from "./PostMainData";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import utils from "../mixins/utils";

export default {
    name: "CloseAccountModal",
    mixins: [utils],
    components: {
        PostMainData
    },
    computed: {
        user() {
            const page = usePage().component.value
            if (page === 'UserProfile')
                return usePage().props.value?.user ?? null
            return usePage().props.value?.auth ?? null
        },
        name: () => {
            const profile = usePage().props.value?.profile ?? null
            return profile ? profile?.first_name + ' ' + profile?.last_name : ''
        }
    },
    data() {
        return {
            modal: null,
            post: null,
            form: useForm({
                processing: false,
            }),
        }
    },
    mounted() {
        const modalEl = this.$refs.closeAccountModal
        this.modal = new bootstrap.Modal(modalEl, {
            keyboard: false,
            backdrop: 'static'
        })
    },
    unmounted() {
        // this.$emitter.off('share-post-modal')
    },
    methods: {
        show() {
            this.modal.show()
        },
        hide() {
            // this.modal.hide()
            $('.modal_close_account').modal('hide');
        },
        submit() {
            //set state variable for login video
            this.$store.commit('Misc/setHasLoggedOut', true);
            this.hide();

            if (this.form.processing) return;

            this.form.post(this.$route('closeMyAccount'), {
                replace: true,
                onSuccess: (response) => {
                    // console.log(response);
                    // setTimeout(() => console.log('asd'), 3000);
                    // (useToast()).options = {
                    //     "showDuration": "3000",
                    // };
                    // (useToast()).success('The invitation has been sent.');
                    // this.hide();
                    // this.form.reset()
                },
                onFinish: () => {
                    this.showErrorMessage()
                }
            })
        },
    }
}
</script>

<style scoped>
.modal-header {
    background: var(--primary);
}

.modal-title {
    color: #fff;
}
</style>
