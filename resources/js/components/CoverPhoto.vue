<template>
    <!-- Begin: Cover Section -->
    <section class="bg-grey cover-photo pt-0 pb-5">
        <img :src="asset('images/cover-photo.jpg')" class="w-100" alt="">
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
                            <Link :href="$route('chatIndex')" class="themeBtn">Message</Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Cover Section -->
    <!--    <div class="coverPhoto">
            <div class="filSet changePhoto" v-if="!$store.state.Profile.is_another">
                <i class="fas fa-camera"></i><input type="file" @change.prevent="imageChange">
            </div>
            <img :src="profile_cover" alt="">
            <teleport v-if="form.progress" to="body">
                <ImageUploadingProgress :progress="form.progress.percentage"/>
            </teleport>
        </div>-->
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
    data() {
        return {
            form: useForm({
                cover: null
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
