<template>
    <div>
        <div class="comntBox">
            <a href="#" class="iconWrap"><img :src="profile_image"
                                              class="rounded-circle" alt=""></a>
            <div class="content">
                <Link :href="profile_link(comment.user.id)" replace>
                    <h2>@{{ comment.user.username }}</h2>
                </Link>
                <p>{{ comment.comment }}</p>
                <ul>
<!--                    <li><a href="#">Like</a></li>-->
                    <CommentLikeButton :comment="comment"></CommentLikeButton>

                    <li><a href="#" @click.prevent="repliesToggle"
                           class="replyBtn">{{ repliesCount }}</a></li>
                    <li v-if="is_delete_able"><a href="#" class="text-danger"
                                                 @click.prevent="deleteComment(comment.id)">Delete</a>
                    </li>
                </ul>
                <p class="small-text">
                    {{ $store.getters['Utils/fromNow'](comment.created_at) }}
                </p>
                <ReplyForm
                    ref="replyForm"
                    @created="replyCreated"
                    :comment_id="comment.id"/>
            </div>
        </div>
        <a v-if="next_page_url" href="#" @click.prevent="loadReplies(comment.id, next_page_url)"
           class="link-primary load-more-link mb-2 d-inline-block">Load previous replies</a>
        <CommentReplyItem
            v-for="reply in replies"
            :key="reply.id"
            :reply="reply"
            @deleted="replyDeleted"/>
        <a v-if="prev_page_url" href="#" @click.prevent="loadReplies(comment.id, prev_page_url)"
           class="link-primary load-more-link mb-3 d-inline-block">Load next replies</a>
    </div>
</template>

<script>
import ReplyForm from "./ReplyForm";
import CommentReplyItem from "./CommentReplyItem";
import CommentLikeButton from "./CommentLikeButton";
import _ from "lodash";
import {Inertia} from "@inertiajs/inertia";
import {useToast} from "vue-toastification";
import {usePage, Link} from "@inertiajs/inertia-vue3";
import utils from "../mixins/utils";

export default {
    name: "CommentItem",
    mixins: [utils],
    components: {
        ReplyForm,
        CommentReplyItem,
        CommentLikeButton,
        Link
    },
    computed: {
        replies() {
            return _.reverse(this.commentData.replies ?? [])
        },
        repliesCount() {
            const replyText = this.commentData.replies_count > 1 ? 'Replies' : 'Reply'
            return (this.commentData.replies_count > 0 ? `(${this.commentData.replies_count}) ` : '') + replyText
        },
        profile_image() {
            return this.comment.user.profile_image ?? this.$store.getters['Utils/public_asset']('images/char-usr.png')
        },
        is_delete_able() {
            if (this.$page.props?.auth?.id && this.comment?.user?.id)
                return this.$page.props.auth.id === this.comment.user.id
            return false
        }
    },
    props: {
        comment: Object
    },
    data() {
        return {
            loading: false,
            next_page_url: null,
            prev_page_url: null,
            showReplyForm: false,
            commentData: this.comment
        }
    },
    methods: {
        repliesToggle() {
            this.showReplyForm = !this.showReplyForm
            $(this.$refs.replyForm.$el).slideToggle().delay(200).css('display', 'flex')
            if (this.showReplyForm)
                this.loadReplies(this.comment.id)
        },
        replyCreated() {
            this.showReplyForm = false
            this.commentData.replies_count += 1
            this.loadReplies(this.comment.id)
        },
        replyDeleted() {
            this.commentData.replies_count -= 1
            this.loadReplies(this.comment.id)
        },
        loadReplies(comment_id, url = null) {
            if (this.loading) return;

            url = url ?? this.$store.getters['Utils/baseUrl']

            this.loading = true
            this.$store.dispatch('HttpUtils/getReq', {
                url: url,
                only: ['replies'],
                params: {
                    comment_id
                }
            }).then(res => {
                this.next_page_url = res?.replies?.next_page_url ?? null
                this.prev_page_url = res?.replies?.prev_page_url ?? null
                this.commentData.replies = res?.replies?.data ?? []
            }).finally(() => {
                this.loading = false
            })

            /*Inertia.get(url, {
                comment_id
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                only: ['replies'],
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    this.next_page_url = visit.props?.replies?.next_page_url ?? null
                    this.prev_page_url = visit.props?.replies?.prev_page_url ?? null
                    this.commentData.replies = visit.props?.replies?.data ?? []
                },
                onFinish: () => {
                    this.loading = false
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })*/
        },
        deleteComment(id) {
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
.load-more-link {
    margin-left: 3rem;
}

.small-text {
    font-size: 12px !important;
}
</style>
