<template>
    <div class="modal fade giftModal" :id="modalId" tabindex="-1"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <h2>Create a New Chat</h2>
                    <SearchForm @search="loadUsers(null, $event)"/>

                    <div class="row listing-container">
                        <div v-if="loading" class="col-md-12">
                            <h4 class="text-secondary">Loading...</h4>
                        </div>
                        <div v-else-if="users.length > 0" class="col-md-12" v-for="user in users" :key="user.id">
                            <SelectUserCheckbox @select="onUserSelect" :user="user"/>
                        </div>
                        <div v-else-if="users.length < 1" class="col-md-12">
                            <h4 class="text-secondary">No friends yet!</h4>
                        </div>

                    </div>

                    <!--                    <PostMainData v-if="post" is-sharable :is-follow-enable="false" :post="post"/>-->

                    <!--                    <div class="form-group">
                                            <textarea v-model="form.content" class="form-control" rows="5"></textarea>
                                        </div>-->

                    <div class="d-flex">
                        <button class="btn btn-danger ml-auto" @click.prevent="hide">Cancel</button>
                        <button class="btn btn-success ml-2" @click.prevent="submit">Create</button>
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
import SearchForm from "./SearchForm";
import SelectUserCheckbox from "./SelectUserCheckbox";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "CreateChatModal",
    components: {
        PostMainData,
        SearchForm,
        SelectUserCheckbox
    },
    data() {
        return {
            modal: null,
            loading: false,
            users: [],
            next_page_url: null,
        }
    },
    /*watch: {
        post(val) {
            this.form.post_id = val?.id ?? null
        }
    },*/
    computed: {
        /*modal() {
            return this.$store.state.SharePostModal.modal
        },*/
        modalId() {
            return "new-chat-create"
        },
        /* post() {
             return this.$store.state.SharePostModal.post
         }*/
    },
    mounted() {
        const el = document.getElementById(this.modalId)
        this.modal = new bootstrap.Modal(el, {
            keyboard: false,
            backdrop: 'static'
        })
    },
    methods: {
        show() {
            this.modal.show()
            this.loadUsers()
        },
        hide() {
            this.modal.hide()
        },
        onUserSelect(user_id) {
            this.$emitter.emit('chat_user_select', user_id)
            this.hide()
        },
        loadUsers(url = null, search = null) {
            if (this.loading) return;

            let isLoadMore = !!(url)
            url = url ?? this.$store.getters['Utils/baseUrl']

            Inertia.get(url, {
                search
            }, {
                replace: true,
                preserveScroll: true,
                preserveState: true,
                only: ['users'],
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    this.next_page_url = visit.props?.users?.next_page_url ?? null
                    if (isLoadMore)
                        this.users = [
                            ...this.users,
                            ...(visit.props?.users?.data ?? [])
                        ]
                    else
                        this.users = visit.props?.users?.data ?? []
                    // this.$store.dispatch('LoadingQueue/reInit')
                },
                onFinish: () => {
                    this.loading = false
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        submit() {
            /*this.form
                .post(this.$route('sharePost'), {
                    replace: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.hide();
                        (useToast()).clear();
                        (useToast()).success(usePage().props.value?.flash?.success ?? 'Request submitted successfully!');
                        this.$emitter.emit('post-shared')
                    }
                })*/
        }
    }
}
</script>

<style scoped>
.listing-container {
    max-height: calc(100vh - 280px);
    overflow: auto;
}
</style>
