<template>
<!--    <div class="col-md-2">-->
<!--        <ChatSearchForm/>-->
<!--        <div class="groupList">-->
<!--            <h4 v-if="loading" class="text-white text-center my-3">Loading...</h4>-->
<!--            <ChatListingItem-->
<!--                v-else-if="!loading && channels.length > 0"-->
<!--                v-for="channel in channels"-->
<!--                :channel_id="channel.id"-->
<!--                :cover="channel.cover_detail"-->
<!--                :key="channel.id"-->
<!--            />-->
<!--            <h5 v-else class="text-secondary text-center my-3 mx-3">No conversations at the moment!</h5>-->
<!--        </div>-->
<!--        <button class="add-new-chat" @click.prevent="showModal"><i class="fas fa-comment"></i></button>-->
<!--        <teleport to="body">-->
<!--            <CreateChatModal ref="createChatModal"/>-->
<!--        </teleport>-->
<!--    </div>-->

    <div class="col-md-3 col-sm-3 border-right p-0">
        <div class="chatSearch">
            <Link class="backBtn" :href="$route('home')">Go Back to Home</Link>
            <ChatSearchForm @search="search"></ChatSearchForm>
<!--                v-else-if="!loading && channels.length > 0"-->
            <ChatListingItem
                v-if="!loading && channels.length > 0"
                v-for="channel in channels"
                :channel_id="channel.id"
                :cover="channel.cover_detail"
                :key="channel.id"
                :is_auth_friend="channel.is_auth_friend"
                :is_in_my_network="channel.is_in_my_network"
            />

            <teleport to="body">
                <CreateChatModal ref="createChatModal"/>
            </teleport>
        </div>
    </div>
</template>

<script>
import ChatSearchForm from "./ChatSearchForm";
import CreateChatModal from "./CreateChatModal";
import {useToast} from "vue-toastification";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import ChatListingItem from "./ChatListingItem";
import _ from "lodash";
import {Link} from '@inertiajs/inertia-vue3'

export default {
    name: "ChatListing",
    components: {
        ChatSearchForm,
        CreateChatModal,
        ChatListingItem,
        Link,
    },
    computed: {
        user() {
            const page = usePage().component.value
            if (page === 'UserProfile')
                return usePage().props.value?.user ?? null
            return usePage().props.value?.auth ?? null
        }
    },
    data() {
        return {
            loading: false,
            next_page_url: null,
            channels: [],
            channels_notifications_counts: [],
            form: useForm({
                chat_type: 'individual',
                user_id: null
            }),
            search_query: '',
        }
    },
    mounted() {
        this.$emitter.on('chat_active', this.checkForEmptyList);
        this.$emitter.on('chat_user_select', this.onUserSelectListener);
        this.$emitter.on('conversation_deleted', this.conversationDeletedListener)

        // this.$emitter.on('activate-channel', this.onActivateChannelListener)
        // let active_user_id_check = this.$store.getters['Chat/activeUserId'];
        // if(active_user_id_check) {
        //     alert('user id: ' + active_user_id_check);
        //     this.$emitter.emit('chat_user_select', active_user_id_check);
        //     this.$store.commit('Chat/setActiveUserId', null);
        // }

        const user_id = location.hash.replace('#', '')
        if (user_id.trim()) {
            this.directChatLoad(user_id)
        } else {
            this.loadChatListing()
            //remove if unnecessary
            // if(this.channels.length == 0) {
            //     setTimeout(this.search(''), 10000);
            // }
        }

        this.loadChatListing()

        //create chat modal
        // $('.giftModal').modal('show');
    },
    unmounted() {
        this.$emitter.off('chat_user_select', this.onUserSelectListener)
        this.$emitter.off('user_leave_group')
    },
    methods: {
        showModal() {
            this.$refs.createChatModal.show()
        },
        onUserSelectListener(user_id) {
            this.form.user_id = user_id
            this.createOrGetChannel()
        },
        onUserLeaveGroupListener() {
            this.loadChatListing()
        },
        onActivateChannelListener(profile_id) {
            this.form.user_id = profile_id;
            this.createOrGetChannel();
        },
        conversationDeletedListener(id) {
            // alert('conversationDeletedListener');
            _.remove(this.channels, (val) => {
                return val.id === id
            })
            this.loadChatListing()
        },

        directChatLoad(user_id) {
            this.form.user_id = user_id
            this.createOrGetChannel()
        },
        loadChatListing(url = null, search = this.search_query) {
            if (this.loading) return;
            let isLoadMore = !!(url)
            url = url ?? this.$store.getters['Utils/baseUrl']

            Inertia.get(url, {
                search
            }, {
                replace: true,
                preserveScroll: true,
                preserveState: true,
                only: ['channels'],
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    console.log(visit);
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
                },
                // onError: (e) => {
                //     alert(e);
                // }
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
                        this.form.user_id = null
                        // console.log(usePage().props.value)
                        const v_data = usePage().props.value?.v_data
                        if (v_data) {
                            this.$emitter.emit('chat_active', v_data.channel.id)
                            this.$emitter.emit('chat_active_user_data', v_data.cover_data)
                        }
                        this.loadChatListing();
                        // this.$store.dispatch('Utils/showSuccessMessage')
                    },
                    onError: () => {
                        this.$store.dispatch('Utils/showErrorMessages')
                    }
                })
        },
        search(query) {
            this.loadChatListing(null, query);
        },
        checkForEmptyList() {
            // alert(this.channels.length);
            // if(this.channels.length == 0) {
            //     setTimeout(function() {
            //         this.search('');
            //     }, 5000);
            // }
        },
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

.backBtn{
    display: block;
    width: auto;
    text-align: center;
    padding: 0.5rem;
    background-color: var(--primary);
    color: #fff;
    margin-left: 1rem;
    border-radius: 0;
}
</style>
