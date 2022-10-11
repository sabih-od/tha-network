<template>
    <div class="comntBox replyBox">
        <a href="#" class="iconWrap"><img :src="profile_img"
                                          class="rounded-circle" alt=""></a>
        <div class="content">
            <Link :href="$store.getters['Utils/generateProfileLink'](reply.user.id)" replace>
                <h2>@{{ reply.user.username }}</h2>
            </Link>
            <p>{{ reply.comment }}</p>
            <ul>
<!--                <li><a href="#">Like</a></li>-->
                <ReplyLikeButton :reply="reply"></ReplyLikeButton>
                <li v-if="is_delete_able">
                    <a href="#" @click.prevent="deleteReply(reply.id)" class="text-danger">Delete</a>
                </li>
                <!--                                    <li><a href="javascript:void(0)" class="replyBtn">Reply</a></li>-->

            </ul>
            <p class="small-text">{{ $store.getters['Utils/fromNow'](reply.created_at) }}</p>
        </div>
    </div>
</template>

<script>
import {Inertia} from "@inertiajs/inertia";
import {useToast} from "vue-toastification";
import {usePage, Link} from "@inertiajs/inertia-vue3";
import ReplyLikeButton from "./ReplyLikeButton";

export default {
    name: "CommentReplyItem",
    components: {Link, ReplyLikeButton},
    props: {
        reply: Object
    },
    computed: {
        profile_img() {
            return this.reply.user?.profile_image ?? this.$store.getters['Utils/public_asset']('images/char-usr.png')
        },
        is_delete_able() {
            if (this.$page.props?.auth?.id && this.reply?.user?.id)
                return this.$page.props.auth.id === this.reply.user.id
            return false
        }
    },
    data() {
        return {
            loading: false
        }
    },
    methods: {
        deleteReply(id) {
            if (this.loading) return;

            Inertia.post(this.$route('postCommentDelete'), {
                id,
                _method: 'delete'
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    (useToast()).clear();
                    (useToast()).success(usePage().props.value?.flash?.success ?? 'Request submitted successfully!');
                    this.$emit('deleted')
                },
                onError: () => {
                    (useToast()).clear();
                    const errors = usePage().props.value?.errors ?? {};
                    for (const x in errors) {
                        (useToast()).error(errors[x]);
                        break
                    }
                },
                onFinish: () => {
                    this.loading = false
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        }
    }
}
</script>

<style scoped>
.comntBox {
    min-width: 250px;
}

.content {
    flex-grow: 1;
}

.small-text {
    font-size: 12px !important;
}
</style>
