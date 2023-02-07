<template v-if="">
    <Link href="#" class="nav-icons" @click.prevent="acceptRequest()">
        <i class="fal fa-check"></i>
    </Link>

    <Link href="#" class="nav-icons" @click.prevent="rejectRequest()">
        <i class="fal fa-times"></i>
    </Link>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import {useForm, Link} from "@inertiajs/inertia-vue3";
import SendInviteModal from "./SendInviteModal";
import ImageUploadingProgress from "./ImageUploadingProgress";
import UserInfo from "./UserInfo";

export default {
    name: "RequestButtonSection",
    components: {
        Link,
    },
    props: {
        user_id: String,
    },
    data() {
        return {
            initialUrl: this.$page.url,
            friendRequestForm: useForm({
                "redirect": false
            }),
            request_sent: this.request_sent,
            request_received: this.request_received,
        }
    },
    methods: {
        acceptRequest() {
            this.friendRequestForm.get(this.$route('acceptRequest', this.user_id), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                    this.$emitter.emit('remove_fr_section');
                    // $('.btn_message').prop('hidden', false);
                    // $('.btn_unfriend').prop('hidden', false);
                    // $('.btn_accept_request').prop('hidden', true);
                    // $('.btn_reject_request').prop('hidden', true);
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    // window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        rejectRequest() {
            this.friendRequestForm.get(this.$route('rejectRequest', this.user_id), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                    this.$emitter.emit('remove_fr_section');
                    // $('.btn_add_friend').prop('hidden', false);
                    // $('.btn_accept_request').prop('hidden', true);
                    // $('.btn_reject_request').prop('hidden', true);
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    // window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
    }
}
</script>

<style scoped>

</style>
