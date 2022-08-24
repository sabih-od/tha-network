<template>
    <!-- Begin: Cover Section -->
    <section class="bg-grey cover-photo pt-0 pb-5">
        <div class="filSet changePhoto" hidden="true" v-if="!$store.state.Profile.is_another" style="background-color: #d3cbcb;">
            <input type="file" class="form-control" @change.prevent="imageChange" style="position: relative; border: 1px solid red!important;">
            <i class="fas fa-edit"></i>
            Edit Cover Photo
        </div>
<!--        <img :src="asset('images/cover-photo.jpg')" class="w-100" alt="">-->
        <img :src="profile_cover" class="w-100" alt="">
        <div class="container-fluid">
            <div class="topWrap">
                <div class="row aic">
                    <div class="col-md-6">
                        <UserInfo/>
                        <!--                        <div class="userInfo">
                                                    <div class="profileImg">
                                                        <img src="images/char-usr.png" alt="">
                                                        &lt;!&ndash; <div class="filSet">
                                                            <i class="fas fa-camera"></i><input type="file">
                                                        </div> &ndash;&gt;
                                                    </div>
                                                    <h2>John Smith <span>@johnsmith22</span></h2>
                                                </div>-->
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="awardWrap profilePage">
                            <img :src="asset('images/cup.png')" alt="">
                        </a>
                        <div class="btn-group">
<!--                            <a href="#" class="themeBtn">Message</a>-->
                            <button v-if="!edit_profile_active" class="themeBtn btn_invite" @click.prevent="inviteModal()">Invite</button>
<!--                            <a v-if="!edit_profile_active" href="#" @click="$emitter.emit('chat-with-profile')" class="themeBtn btn_message">Message</a>-->
                            <Link v-if="!edit_profile_active" href="#" class="themeBtn btn_message" data-profile="asd" @click.prevent="chatWithProfile()">Message</Link>
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

export default {
    name: "CoverPhoto",
    mixins: [utils],
    components: {
        SendInviteModal,
        Link,
        ImageUploadingProgress,
        UserInfo
    },
    computed: {
        errors: () => usePage().props.value?.errors ?? null,
        profile_cover() {
            return usePage().props.value?.profile_cover ?? this.$store.getters['Utils/public_asset']('images/cover-photo.jpg')
        },
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
            })
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
            // this.channelForm.user_id = $('.btn_message').data('profile');
            // this.channelForm
            //     .post(this.$route('channelStore'), {
            //         replace: true,
            //         preserveScroll: true,
            //         preserveState: true,
            //         onSuccess: () => {
            //             this.form.user_id = null
            //             // console.log(usePage().props.value)
            //             const v_data = usePage().props.value?.v_data
            //             if (v_data) {
            //                 this.$emitter.emit('chat_active', v_data.channel.id)
            //                 this.$emitter.emit('chat_active_user_data', v_data.cover_data)
            //             }
            //             // this.loadChatListing();
            //             // this.$store.dispatch('Utils/showSuccessMessage')
            //         },
            //         onError: () => {
            //             this.$store.dispatch('Utils/showErrorMessages')
            //         }
            //     })

            // Inertia.get(this.$route('chatIndex'), {
            //     profile_id: $('.btn_message').data('profile')
            // }, {
            //     replace: true,
            //     preserveScroll: true,
            //     preserveState: true,
            //     only: ['profile_id'],
            //     onStart: () => {
            //         this.loading = true
            //     },
            //     onSuccess: visit => {
            //         console.log(visit);
            //     },
            //     onFinish: () => {
            //         this.loading = false
            //         window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
            //     }
            // })

            this.$store.commit('Chat/setActiveUserId', $('.btn_message').data('profile'));
            Inertia.get(this.$route('chatIndex'), {

            }, {
                replace: true,
                preserveScroll: true,
                preserveState: true,
                onStart: () => {
                    this.loading = true
                },
                onSuccess: visit => {
                    // console.log(visit);
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
</style>
