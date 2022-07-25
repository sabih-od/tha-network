<template>
    <div class="coverPhoto">
        <div class="filSet changePhoto" v-if="!$store.state.Profile.is_another">
            <i class="fas fa-camera"></i><input type="file" @change.prevent="imageChange">
        </div>
        <img :src="profile_cover" alt="">
        <teleport v-if="form.progress" to="body">
            <ImageUploadingProgress :progress="form.progress.percentage"/>
        </teleport>
    </div>
</template>

<script>
import ImageUploadingProgress from './ImageUploadingProgress'
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "CoverPhoto",
    components: {
        ImageUploadingProgress
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
