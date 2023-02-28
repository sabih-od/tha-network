<template>
    <div class="modal fade modal_change_preferred_payout_method_modal" ref="changePreferredPayoutMethodModal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure?</h5>
                </div>
                <div class="modal-body">
                    <h5><small>Do you wish to change payment portals?</small></h5>
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
    name: "ChangePreferredPayoutMethodModal",
    mixins: [utils],
    components: {
        PostMainData
    },
    props: {
        preferred_payout_method: String
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
            preferred_payout_form: useForm({
                preferred_payout_method: this.preferred_payout_method
            }),
            emitToRevert: true
        }
    },
    mounted() {
        const modalEl = this.$refs.changePreferredPayoutMethodModal
        // this.modal = new bootstrap.Modal(modalEl, {
        //     keyboard: false,
        //     backdrop: 'static'
        // })
        $('.modal_change_preferred_payout_method_modal').modal({
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
            if (this.emitToRevert) {
                this.$emitter.emit('revert_preferred_payout_method');
            }

            $('.modal_change_preferred_payout_method_modal').modal('hide');
        },
        submit() {
            this.emitToRevert = false;

            this.preferred_payout_form.preferred_payout_method = this.preferred_payout_method;

            this.preferred_payout_form.post(this.$route('updateProfile'), {
                replace: true,
                onSuccess: () => {
                    (useToast()).success('You are now connected with '+this.preferred_payout_method.charAt(0).toUpperCase() + this.preferred_payout_method.slice(1)+' as your primary payment portal.');
                },
                onFinish: () => {
                    this.$store.dispatch('Utils/showErrorMessages').then(res => {
                        this.isEdit = false
                    })
                }
            })

            this.hide();
            this.emitToRevert = true;
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
