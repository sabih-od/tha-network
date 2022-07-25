<template>
    <div class="modal fade giftModal" :id="modalId" tabindex="-1"
         aria-labelledby="sharePostModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>-->
                <div class="modal-body p-0">
                    <h2>Share Post</h2>

                    <PostMainData v-if="post" is-sharable :is-follow-enable="false" :post="post"/>

                    <div class="form-group">
                        <textarea v-model="form.content" class="form-control" rows="5"></textarea>
                    </div>

                    <div class="d-flex">
                        <button class="btn btn-danger ml-auto" @click.prevent="hide">Close</button>
                        <button class="btn btn-success ml-2" @click.prevent="submit">Share</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PostMainData from "./PostMainData";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";

export default {
    name: "SharePostModal",
    components: {
        PostMainData
    },
    data() {
        return {
            form: useForm({
                content: "",
                post_id: null
            }),
        }
    },
    watch: {
        post(val) {
            this.form.post_id = val?.id ?? null
        }
    },
    computed: {
        modal() {
            return this.$store.state.SharePostModal.modal
        },
        modalId() {
            return this.$store.state.SharePostModal.modalId
        },
        post() {
            return this.$store.state.SharePostModal.post
        }
    },
    mounted() {
        const modalEl = document.getElementById(this.modalId)
        this.$store.commit('SharePostModal/setModal', modalEl)

        /*modalEl.addEventListener('hidden.bs.modal', function (event) {
            console.log("closed modal")
        })*/
    },
    methods: {
        hide() {
            this.$store.dispatch('SharePostModal/hideModal')
        },
        submit() {
            this.form
                .post(this.$route('sharePost'), {
                    replace: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.hide();
                        (useToast()).clear();
                        (useToast()).success(usePage().props.value?.flash?.success ?? 'Request submitted successfully!');
                        this.$emitter.emit('post-shared')
                    }
                })
        }
    }
}
</script>

<style scoped>

</style>
