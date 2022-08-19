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
        console.log(usePage().props.value.profile_cover);
        // this.edit_profile_active = window.location.href.includes('edit-profile') ? true : false;
    },
    data() {
        return {
            form: useForm({
                cover: null
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
