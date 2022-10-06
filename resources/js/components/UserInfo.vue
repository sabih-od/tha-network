<template>
    <div class="userInfo">
        <div class="profileImg">
            <img :src="auth_image" alt="">
            <a class="btn_edit_avatar" @click.prevent="showAvatarModal()" hidden="true">
                <i class="fas fa-edit"></i>
            </a>
            <!--            <div class="filSet">-->
            <!--                <i class="fas fa-camera"></i><input type="file">-->
            <!--            </div>-->
        </div>
        <h2>{{ name }} <span>@{{ user?.username }}</span></h2>
        <teleport to="body">
            <CreateAvatar/>
        </teleport>
    </div>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from 'vue-toastification'
import CreateAvatar from './CreateAvatar'
import ImageUploadingProgress from "./ImageUploadingProgress";
import {Inertia} from "@inertiajs/inertia";
import utils from "../mixins/utils";

export default {
    name: "UserInfo",
    mixins: [utils],
    components: {ImageUploadingProgress, CreateAvatar},
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
        },
        showAvatarModal() {
            $('.modal_create_avatar').modal('show');
        }
    }
}
</script>

<style lang="scss">
/*.profileImg > img {
    width: 200px;
    height: 200px;
}*/
.btn_edit_avatar {
    position: absolute;
    z-index: 99;
    bottom: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.75);
    color: #fff !important;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 30px;
    cursor: pointer;

    &:hover {
        color: var(--primary) !important;
    }
}
</style>
