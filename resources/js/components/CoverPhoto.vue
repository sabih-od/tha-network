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
                            <button v-if="!edit_profile_active" class="themeBtn btn_message" @click.prevent="inviteModal()">Invite</button>
                            <Link v-if="!edit_profile_active" :href="$route('chatIndex')" class="themeBtn btn_message">Message</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    <div class="modal fade modal_invite" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Send Invitation</h5>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title" id="exampleModalLabel"><small>Send invitations to people to join your network</small></h5>
                    <form @submit.prevent="sendInvite()">
                        <div class="form-group">
                            <input class="form-control" type="email" placeholder="Email" v-model="invite_form.email" :disabled="invite_form.processing">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary form-control">Send</button>
                        </div>
                    </form>
                </div>
<!--                <div class="modal-footer">-->
<!--                    <button type="button" class="btn btn-primary" @click.prevent="sendInvite()">Send</button>-->
<!--                </div>-->
            </div>
        </div>
    </div>
</template>

<script>
import ImageUploadingProgress from './ImageUploadingProgress'
import {Link, useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import {Inertia} from "@inertiajs/inertia";
import utils from "../mixins/utils";
import UserInfo from "./UserInfo";

export default {
    name: "CoverPhoto",
    mixins: [utils],
    components: {
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
        console.log(usePage().props.value.profile_cover);
        // this.edit_profile_active = window.location.href.includes('edit-profile') ? true : false;
    },
    data() {
        return {
            form: useForm({
                cover: null
            }),
            invite_form: useForm({
                email: '',
                processing: false
            }),
            edit_profile_active: false
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
        sendInvite() {
            if (this.invite_form.processing) return;

            this.invite_form.post(this.$route('sendInvitation'), {
                replace: true,
                onSuccess: () => {
                    setTimeout(() => console.log('asd'), 3000);
                    (useToast()).options = {
                        "showDuration": "3000",
                    };
                    (useToast()).success('The invitation has been sent.');
                    this.invite_form.reset()
                    // this.showSuccessMessage()
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
