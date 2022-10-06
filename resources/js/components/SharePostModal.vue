<template>
    <div class="modal fade" ref="shareModal" tabindex="-1"
         aria-labelledby="sharePostModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <h2>Share Post</h2>

                    <div class="feedBox">
                        <PostMainData v-if="post" is-sharable :post="post"/>

                        <div class="form-group">
                            <textarea v-model="form.content" class="form-control" rows="5"></textarea>
                        </div>

                        <div class="d-flex">
                            <button class="btn btn-danger ml-auto" @click.prevent="hide">Close</button>
                            <button class="btn btn-success ml-2" @click.prevent="submit">
                                {{ form.processing ? 'Sharing...' : 'Share' }}
                            </button>
                        </div>
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
import utils from "../mixins/utils";

export default {
    name: "SharePostModal",
    mixins: [utils],
    components: {
        PostMainData
    },
    data() {
        return {
            modal: null,
            post: null,
            form: useForm({
                content: "",
                post_id: null
            }),
        }
    },
    mounted() {
        const modalEl = this.$refs.shareModal
        this.modal = new bootstrap.Modal(modalEl, {
            keyboard: false,
            backdrop: 'static'
        })
        this.$emitter.on('share-post-modal', (post) => {
            this.post = post
            this.form.post_id = post.id
            this.show()
        })
    },
    unmounted() {
        this.$emitter.off('share-post-modal')
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

            this.form
                .post(this.$route('sharePost'), {
                    replace: true,
                    onSuccess: () => {
                        this.hide();
                        this.form.reset();
                        this.showSuccessMessage()
                        this.$emitter.emit('post-shared')
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
.modal-body > h2 {
    font-size: 1.75rem;
    padding: 1rem 0 0 1rem;
}
</style>
