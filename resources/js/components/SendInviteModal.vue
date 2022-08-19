<template>
    <div class="modal fade modal_invite" ref="inviteModal" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Send Invitation</h5>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title" id="exampleModalLabel"><small>Send invitations to people to join your network</small></h5>
                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <input class="form-control" placeholder="Email" v-model="form.email" :disabled="form.processing">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">
                                {{ form.processing ? 'Please wait...' : 'Send' }}
                            </button>
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
    name: "SendInviteModal",
    mixins: [utils],
    components: {
        PostMainData
    },
    data() {
        return {
            modal: null,
            post: null,
            form: useForm({
                email: '',
                processing: false
            }),
        }
    },
    mounted() {
        const modalEl = this.$refs.inviteModal
        this.modal = new bootstrap.Modal(modalEl, {
            keyboard: false,
            backdrop: 'static'
        })
        // this.$emitter.on('share-post-modal', (post) => {
        //     this.post = post
        //     this.form.post_id = post.id
        //     this.show()
        // })
    },
    unmounted() {
        // this.$emitter.off('share-post-modal')
    },
    methods: {
        show() {
            this.modal.show()
        },
        hide() {
            this.modal.hide()
        },
        submit() {
            if (this.form.processing) return;

            this.form.post(this.$route('sendInvitation'), {
                replace: true,
                onSuccess: (response) => {
                    console.log(response);
                    setTimeout(() => console.log('asd'), 3000);
                    (useToast()).options = {
                        "showDuration": "3000",
                    };
                    (useToast()).success('The invitation has been sent.');
                    this.form.reset()
                    // this.showSuccessMessage()
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

</style>
