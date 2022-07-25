<template>
    <div class="col-md-2">
        <ChatSearchForm/>
        <div class="groupList">
            <h4 v-if="loading" class="text-white text-center my-3">Loading...</h4>
            <ChatListingItem
                v-else-if="!loading && channels.length > 0"
                v-for="channel in channels"
                :channel_id="channel.id"
                :cover="channel.cover_detail"
                :key="channel.id"
            />
            <h5 v-else class="text-secondary text-center my-3 mx-3">No conversations at the moment!</h5>
        </div>
        <button class="add-new-chat" @click.prevent="showModal"><i class="fas fa-comment"></i></button>
        <teleport to="body">
            <CreateChatModal ref="createChatModal"/>
        </teleport>
    </div>
</template>

<script>
import ChatSearchForm from "./ChatSearchForm";
import CreateChatModal from "./CreateChatModal";
import {useToast} from "vue-toastification";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import ChatListingItem from "./ChatListingItem";

export default {
    name: "ChatListing",
    components: {
        ChatSearchForm,
        CreateChatModal,
        ChatListingItem
    },
    data() {
        return {
            loading: false,
            next_page_url: null,
            channels: [],
            form: useForm({
                user_id: null
            })
        }
    },
    methods: {
        showModal() {
            this.$refs.createChatModal.show()
        },
        onUserSelectListener(user_id) {
            this.form.user_id = user_id
            this.createOrGetChannel()
        },
        loadChatListing(url = null) {
            if (this.loading) return;

            let isLoadMore = !!(url)
            url = url ?? this.$store.getters['Utils/baseUrl']

            Inertia.get(url, {
                // search
            }, {
                replace: true,
                preserveScroll: true,
                preserveState: true,
                only: ['channels'],
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    this.next_page_url = visit.props?.channels?.next_page_url ?? null
                    if (isLoadMore)
                        this.channels = [
                            ...this.channels,
                            ...(visit.props?.channels?.data ?? [])
                        ]
                    else
                        this.channels = visit.props?.channels?.data ?? []
                    // this.$store.dispatch('LoadingQueue/reInit')
                },
                onFinish: () => {
                    this.loading = false
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        createOrGetChannel() {
            if (this.form.processing) return

            this.form
                .post(this.$route('channelStore'), {
                    replace: true,
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.form.reset();
                        this.loadChatListing();
                        (useToast()).clear();
                        (useToast()).success(usePage().props.value?.flash?.success ?? 'Request submitted successfully!');
                    },
                    onError: () => {
                        (useToast()).clear();
                        const errors = usePage().props.value?.errors ?? {};
                        for (const x in errors) {
                            (useToast()).error(errors[x]);
                            break
                        }
                        if (usePage().props.value?.flash?.error) {
                            (useToast()).error(usePage().props.value.flash.error);
                        }
                    }
                })
        },
    },
    mounted() {
        this.$emitter.on('chat_user_select', this.onUserSelectListener)
        this.loadChatListing()
    },
    unmounted() {
        this.$emitter.off('chat_user_select', this.onUserSelectListener)
    },
}
</script>

<style scoped>
.groupList {
    height: calc(100% - 68px);
}

.add-new-chat {
    position: absolute;
    bottom: 20px;
    right: 20px;
    margin: 0 auto;
    font-size: 25px;
    color: white;
    background-color: #121f2b;
    height: 50px;
    width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    box-shadow: 0 2px 5px -1px white;
    border: 0;
    transition: 300ms;
}

.add-new-chat:hover {
    background-color: white;
    color: #121f2b;
    box-shadow: 0 2px 5px 1px white;
}
</style>
