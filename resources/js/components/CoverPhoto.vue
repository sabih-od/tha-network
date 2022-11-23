<template>
    <!-- Begin: Cover Section -->
    <section class="bg-grey cover-photo pt-0 pb-5">
        <div class="filSet changePhoto" hidden="true" v-if="!$store.state.Profile.is_another">
            <input type="file" class="form-control" @change.prevent="imageChange"
                   style="border: 1px solid red!important;">
            <i class="fas fa-edit"></i>
        </div>
        <!--        <div style="max">-->
        <!--            <avataaars></avataaars>-->
        <!--        </div>-->
        <!--        <img :src="asset('images/cover-photo.jpg')" class="w-100" alt="">-->
        <img :src="profile_cover" class="w-100" alt="">
        <div class="container-fluid">
            <div class="topWrap">
                <div class="row aic">
                    <div class="col-md-6">
                        <UserInfo/>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="awardWrap profilePage">
                            <img :src="asset('images/cup.png')" alt="">
                        </a>
                        <div class="btn-group">
                            <!--                            <a href="#" class="themeBtn">Message</a>-->
                            <button v-if="!edit_profile_active" class="themeBtn btn_invite" :title="$route('joinByInvite', this.user.username)"
                                    @click.prevent="copy_my_referral_link">Share your profile
                            </button>
                            <button v-if="!edit_profile_active" class="themeBtn btn_invite"
                                    @click.prevent="inviteModal()">Make a Referral
                            </button>
                            <!--                            <a v-if="!edit_profile_active" href="#" @click="$emitter.emit('chat-with-profile')" class="themeBtn btn_message">Message</a>-->
                            <Link v-if="!edit_profile_active" :href="$route('chatIndex')" class="themeBtn btn_message"
                                  data-profile="asd" @click.prevent="chatWithProfile()">Message
                            </Link>
                            <Link v-if="!edit_profile_active" href="#" class="themeBtn btn_add_friend"
                                  data-profile="asd" @click.prevent="addFriend()"><i class="fa fa-plus"
                                                                                     aria-hidden="true"></i>Add Friend
                            </Link>
                            <Link v-if="!edit_profile_active" href="#" class="themeBtn btn_unfriend" data-profile="asd"
                                  @click.prevent="unfriend()">Unfriend
                            </Link>
                            <Link v-if="!edit_profile_active" href="#" class="themeBtn btn_block" data-profile="asd"
                                  @click.prevent="block()">Block
                            </Link>
                            <Link v-if="!edit_profile_active" href="#" class="themeBtn btn_accept_request"
                                  data-profile="asd" @click.prevent="acceptRequest()">Accept Request
                            </Link>
                            <Link v-if="!edit_profile_active" href="#" class="themeBtn btn_reject_request"
                                  data-profile="asd" @click.prevent="rejectRequest()">Reject Request
                            </Link>
                            <!--hidden button to go to chat-->
                            <!--                            <Link :href="$route('chatIndex')" hidden ref="hiddenChatButton"></Link>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <teleport to="body">
            <SendInviteModal/>
        </teleport>
    </section>
    <!-- END: Cover Section -->
    <!--    <div class="coverPhoto">-->
    <!--        <div class="filSet changePhoto" v-if="$store.state.Profile.is_another">-->
    <!--            <i class="fas fa-camera"></i><input type="file" @change.prevent="imageChange">-->
    <!--        </div>-->
    <!--        <img :src="profile_cover" alt="">-->
    <!--        <teleport v-if="form.progress" to="body">-->
    <!--            <ImageUploadingProgress :progress="form.progress.percentage"/>-->
    <!--        </teleport>-->
    <!--    </div>-->
</template>

<script>
import ImageUploadingProgress from './ImageUploadingProgress'
import SendInviteModal from "./SendInviteModal";
import {Link, useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import {Inertia} from "@inertiajs/inertia";
import utils from "../mixins/utils";
import UserInfo from "./UserInfo";
import Avataaars from 'vuejs-avataaars/src/Avataaars';

export default {
    name: "CoverPhoto",
    mixins: [utils],
    components: {
        SendInviteModal,
        Link,
        ImageUploadingProgress,
        UserInfo,
        Avataaars
    },
    computed: {
        errors: () => usePage().props.value?.errors ?? null,
        profile_cover() {
            return usePage().props.value?.profile_cover ?? this.$store.getters['Utils/public_asset']('images/cover-photo.jpg')
        },
        user() {
            return usePage().props.value?.auth ?? null
        }
    },
    mounted() {
        // console.log(usePage().props.value.profile_cover);
        // this.edit_profile_active = window.location.href.includes('edit-profile') ? true : false;
    },
    data() {
        return {
            form: useForm({
                cover: null
            }),
            edit_profile_active: false,
            channelForm: useForm({
                chat_type: 'individual',
                user_id: null
            }),
            friendRequestForm: useForm({})
        }
    },
    methods: {
        imageChange(e) {
            const _this = this
            if (e.target.files[0] && !_this.form.processing) {
                _this.form.cover = e.target.files[0]
                _this.form.post(this.$route('profileCoverUpload'), {
                    replace: true,
                    forceFormData: true,
                    onSuccess() {
                        _this.form.reset();
                        (useToast()).clear();
                        (useToast()).success(usePage().props.value?.flash?.success ?? 'Cover uploaded successfully!');
                    }
                })
            } else {
                _this.form.cover = null
            }
        },
        inviteModal() {
            $('.modal_invite').modal('show');
        },
        chatWithProfile() {
            const profile_id = $('.btn_message').data('profile');
            this.$store.commit('Chat/setActiveUserId', profile_id);

            this.channelForm.user_id = profile_id;
            this.channelForm
                .post(this.$route('channelStore'), {
                    replace: true,
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.form.user_id = null
                        // const v_data = usePage().props.value?.v_data
                        // if (v_data) {
                        //     this.$emitter.emit('chat_active', v_data.channel.id)
                        //     this.$emitter.emit('chat_active_user_data', v_data.cover_data)
                        // }
                    },
                    onError: () => {
                        this.$store.dispatch('Utils/showErrorMessages')
                    }
                })
        },
        addFriend() {
            this.friendRequestForm.get($('.btn_add_friend').data('profile'), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                    $('.btn_add_friend').html('<i class="fa fa-check mr-2"></i>Request Sent');
                    $('.btn_add_friend').prop('disabled', true);
                    // this.$emit('deleted')
                    // this.$emitter.emit('chat_message_deleted', this.form.id)
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        acceptRequest() {
            this.friendRequestForm.get($('.btn_accept_request').data('profile'), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                    $('.btn_message').prop('hidden', false);
                    $('.btn_unfriend').prop('hidden', false);
                    $('.btn_accept_request').prop('hidden', true);
                    $('.btn_reject_request').prop('hidden', true);
                    // this.$emit('deleted')
                    // this.$emitter.emit('chat_message_deleted', this.form.id)
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        rejectRequest() {
            this.friendRequestForm.get($('.btn_reject_request').data('profile'), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                    $('.btn_add_friend').prop('hidden', false);
                    $('.btn_accept_request').prop('hidden', true);
                    $('.btn_reject_request').prop('hidden', true);
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        unfriend() {
            this.friendRequestForm.get($('.btn_unfriend').data('profile'), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                    $('.btn_message').prop('hidden', true);
                    $('.btn_unfriend').prop('hidden', true);
                    $('.btn_add_friend').prop('hidden', false);
                    // this.$emit('deleted')
                    // this.$emitter.emit('chat_message_deleted', this.form.id)
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        block() {
            const url = $('.btn_block').data('profile');
            this.friendRequestForm.get(url, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: visit => {
                    this.$store.dispatch('Utils/showSuccessMessage')
                    $('.btn_message').prop('hidden', true);
                    $('.btn_unfriend').prop('hidden', true);
                    $('.btn_add_friend').prop('hidden', false);
                    $('.btn_block').html(url.includes('unblock') ? 'Block' : 'Unblock');
                    let route_slug = url.includes('unblock') ? 'block' : 'unblock';
                    const block_url = this.$route(route_slug, usePage().props.value?.user.id);
                    $('.btn_block').data('profile', block_url);
                    // this.$emit('deleted')
                    // this.$emitter.emit('chat_message_deleted', this.form.id)
                },
                onError: () => {
                    this.$store.dispatch('Utils/showErrorMessages')
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        copy_my_referral_link() {
            navigator.clipboard.writeText(this.$route('joinByInvite', this.user.username));
            (useToast()).success('Link Copied!');
        }
    }
}
</script>

<style scoped>
.coverPhoto {
    max-height: 515px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.coverPhoto img {
    width: 100%;
}

.filSet.changePhoto {
    position: absolute;
    top: 0;
    right: 0;
    background: var(--primary);
    padding: 0.5rem .75rem;
    border-radius: 100%;
    color: #fff;
    border-top-right-radius: 0;
}


.filSet.changePhoto + img {
    max-height: 550px;
    width: 100%;
    object-fit: cover;
}
.filSet.changePhoto input {
    z-index: 1;
    cursor: pointer;
}
</style>
