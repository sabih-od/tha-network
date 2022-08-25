<template>
<!--    <a href="#" @click.prevent="select" class="grpName">-->
<!--        <ProfileImageIconRounded :profile_img="cover?.profile_img"/>-->
<!--        <h2>{{ cover?.name }}</h2>-->
<!--    </a>-->

    <a href="#" @click.prevent="select">
        <div class="friend-drawer friend-drawer--onhover">

            <ProfileImageIconRounded :profile_img="cover?.profile_img"/>
            <div class="text">
                <h6>{{ cover?.profile?.first_name + ' ' + cover?.profile?.last_name}}</h6>
            </div>
<!--            <button class="form-control-sm btn text-danger btn-sm"><i class="fas fa-trash"></i></button>-->

            <div class="dropdown ml-auto">
                <button type="button" id="dropdownNotifications" data-toggle="dropdown"
                        aria-expanded="false">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownNotifications">
                    <button class="dropdown-item" @click.prevent="deleteConversation" type="button">Delete Conversation</button>
                </div>
            </div>
        </div>
    </a>
</template>

<script>
import ProfileImageIconRounded from "./ProfileImageIconRounded";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
    name: "ChatListingItem",
    components: {
        ProfileImageIconRounded
    },
    props: {
        channel_id: String,
        cover: Object
    },
    data() {
        return {
            form: useForm({
                id: this.channel_id
            })
        }
    },
    mounted() {
        let active_user_id_check = this.$store.getters['Chat/activeUserId'];
        if(active_user_id_check) {
            // alert('user id: ' + active_user_id_check);
            // this.$emitter.emit('chat_user_select', active_user_id_check);
            // this.$store.commit('Chat/setActiveUserId', null);

            if(active_user_id_check == this.cover.id) {
                this.select();
                this.$store.commit('Chat/setActiveUserId', null);
            }
        }
    },
    methods: {
        select() {
            this.$emitter.emit('chat_active', this.channel_id)
            this.$emitter.emit('chat_active_user_data', this.cover)
        },
        deleteConversation() {
            if (this.form.processing) return;

            this.form.delete(this.$route('channelDestroy'), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    // this.$store.dispatch('Utils/showSuccessMessage');
                    // this.$emit('deleted')
                    // this.$emitter.emit('conversation_deleted', this.form.id);
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
            this.$store.dispatch('Utils/showSuccessMessage');
            this.$emitter.emit('conversation_deleted', this.form.id);
        },
    }
}
</script>

<style scoped>
.imgWrap img {
    width: 50px;
    height: 50px;
    object-fit: cover;
}
</style>
