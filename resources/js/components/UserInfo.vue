<template>
    <div class="userInfo">
        <div class="profileImg">
            <img :src="asset('images/char-usr.png')" alt="">
            <a class="btn_edit_avatar" hidden="true" style="width: 40px;
            height: 40px;
            background: #9f9090;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            position: absolute;
            right: 2rem;
            bottom: 1rem;
            font-size: 1rem;
            color: white;
            z-index: 999;">
                <i class="fas fa-edit"></i>
            </a>
<!--            <div class="filSet">-->
<!--                <i class="fas fa-camera"></i><input type="file">-->
<!--            </div>-->
        </div>
        <h2>{{ name }} <span>@{{ user?.username }}</span></h2>
    </div>
    <!--    <div class="userInfo">
            <div class="profileImg">
                <img :src="profile_img" alt="">
                <div class="filSet" v-if="!$store.state.Profile.is_another">
                    <i class="fas fa-camera"></i><input type="file" @change.prevent="imageChange">
                </div>
            </div>
            <h2>{{ user?.name ?? '' }} <span>{{ user?.email ?? '' }}</span></h2>
            <teleport v-if="form.progress" to="body">
                <ImageUploadingProgress :progress="form.progress.percentage"/>
            </teleport>
        </div>-->
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from 'vue-toastification'
import ImageUploadingProgress from "./ImageUploadingProgress";
import {Inertia} from "@inertiajs/inertia";
import utils from "../mixins/utils";

export default {
    name: "UserInfo",
    mixins: [utils],
    components: {ImageUploadingProgress},
    computed: {
        user() {
            const page = usePage().component.value
            if (page === 'UserProfile')
                return usePage().props.value?.user ?? null
            return usePage().props.value?.auth ?? null
        },
        name: () => {
            const profile = usePage().props.value?.profile ?? null
            return profile ? profile?.first_name + ' ' + profile?.last_name : ''
        },
        errors: () => usePage().props.value?.errors ?? null,
        profile_img() {
            return usePage().props.value?.profile_image ?? this.$store.getters['Utils/public_asset']('images/small-character.jpg')
        },
    },
    data() {
        return {
            form: useForm({
                file: null
            })
        }
    },
    watch: {
        errors(val) {
            if (val?.file) {
                (useToast()).error(val.file)
            }
        }
    },
    methods: {
        imageChange(e) {
            const _this = this
            if (e.target.files[0] && !_this.form.processing) {
                _this.form.file = e.target.files[0]
                _this.form.post(this.$route('profileImgUpload'), {
                    replace: true,
                    forceFormData: true,
                    onSuccess() {
                        _this.form.reset();
                        (useToast()).clear();
                        (useToast()).success(usePage().props.value?.flash?.success ?? 'Image uploaded successfully!');
                    }
                })
            } else {
                _this.form.file = null
            }
        }
    }
}
</script>

<style scoped>
/*.profileImg > img {
    width: 200px;
    height: 200px;
}*/
</style>
