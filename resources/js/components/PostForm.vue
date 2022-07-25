<template>
    <div class="postBox">

        <ul class="topList">
            <li><a href="#" @click.prevent><i class="fal fa-edit"></i> Make Post</a></li>
            <li>
                <a href="#"><i class="fal fa-video-plus"></i>Upload Photos/Videos
                    <input type="file" name="post_video[]" class="postFileUploader" @change.prevent="filesSelect"
                           multiple>
                </a>
            </li>
            <li><a href="#" @click.prevent><img :src="$store.getters['Utils/public_asset']('images/live.png')" alt="">
                Live Video</a></li>
        </ul>
        <div v-if="renderedFiles.length > 0" class="row mx-2">
            <div class="col-md-2 position-relative" v-for="(file, ind) in renderedFiles" :key="ind">
                <img v-if="file.type === 'image'" :src="file.source" class="img-fluid w-100" alt="">
                <i class="fas fa-times delete-icon" @click.prevent="removeFile($event, file.fileInd)"></i>
            </div>
        </div>
        <div class="textAreaWrap">
            <div class="iconWrap"><img :src="profile_img" alt=""></div>
            <textarea v-model="form.content" name="post" placeholder="Want to Share a Memory?" id=""></textarea>
            <!--            <p v-if="errors?.content" class="small text-danger">{{ errors.content }}</p>-->
        </div>
        <!-- Video Preview -->
        <div id="up_images">
            <div class="cst_imgs">
            </div>
        </div>
        <!-- Video Preview -->
        <ul class="bottomList">
            <!--            <li><a href="#" class="optBtn"><i class="fal fa-smile"></i> Feeling Activity</a>
                        </li>
                        <li><a href="#" class="optBtn"><i class="fas fa-map-marker-alt"></i>Location</a>
                        </li>-->
            <li class="ml-auto"><a @click.prevent="submit"
                                   href="#"
                                   class="themeBtn">Post Now</a></li>
        </ul>
        <teleport v-if="form.progress" to="body">
            <ImageUploadingProgress :progress="form.progress.percentage"/>
        </teleport>
    </div>
</template>

<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import {Inertia} from "@inertiajs/inertia";
import ImageUploadingProgress from "./ImageUploadingProgress";

export default {
    name: "PostForm",
    components: {ImageUploadingProgress},
    computed: {
        errors: () => usePage().props.value?.errors ?? null,
        // flashError: () => usePage().props.value?.flash?.error ?? null,
        profile_img() {
            return usePage().props.value?.auth_profile_image ?? this.$store.getters['Utils/public_asset']('images/ph-profile.jpg')
        },
    },
    /*props: {
        errors: Object
    },*/
    watch: {
        errors(val) {
            for (const key in val) {
                (useToast()).error(val[key]);
            }
        },
        /*flashError(val) {
            if (val) {
                (useToast()).error(val);
            }
        }*/
    },
    data() {
        return {
            form: useForm({
                content: "",
                files: []
            }),
            renderedFiles: []
        }
    },
    methods: {
        submit() {
            if (this.form.processing) return

            this.form
                .transform((data) => {
                    let files = []
                    for (const item of data.files) {
                        files.push(item.file)
                    }
                    return {
                        ...data,
                        files: files
                    }
                })
                .post(this.$route('postCreate'), {
                    replace: true,
                    forceFormData: true,
                    onSuccess: () => {
                        this.renderedFiles = [];
                        this.form.reset();
                        (useToast()).clear();
                        (useToast()).success(usePage().props.value?.flash?.success ?? 'Post created successfully!');
                        this.$emitter.emit('post-created')
                    },
                    onFinish: () => {
                        if (usePage().props.value?.flash?.error) {
                            (useToast()).clear();
                            (useToast()).error(usePage().props.value?.flash?.error ?? 'Server error!');
                        }
                    }
                })
        },
        filesSelect(e) {
            const _this = this

            for (const filesKey in e.target.files) {
                const fileType = e.target.files[filesKey]?.type
                if (fileType) {
                    if (
                        (/^(image\/)[\w]+$/.test(fileType) || /^(video\/)[\w]+$/.test(fileType))
                    ) {
                        const key = (new Date()).getTime()
                        _this.form.files.push({
                            fileInd: key,
                            file: e.target.files[filesKey]
                        })
                        // render files
                        let reader = new FileReader();
                        reader.onload = function () {
                            _this.renderedFiles.push({
                                fileInd: key,
                                type: 'image',
                                source: reader.result
                            })
                        }
                        if (/^(image\/)[\w]+$/.test(fileType)) {
                            reader.readAsDataURL(e.target.files[filesKey]);
                        } else {
                            _this.$store.dispatch('Utils/getVideoCover', {
                                file_url: URL.createObjectURL(e.target.files[filesKey]),
                                seekTo: 2
                            }).then(res => {
                                reader.readAsDataURL(res);
                            }).catch(err => {
                                (useToast()).clear();
                                (useToast()).error(err);
                            })

                        }
                    } else {
                        (useToast()).clear();
                        (useToast()).error("Invalid file selected!");
                    }
                }
            }
            e.target.value = ""
        },
        removeFile(e, fileInd) {
            e.preventDefault()
            _.remove(this.form.files, {fileInd})
            _.remove(this.renderedFiles, {fileInd})
        }
    }
}
</script>

<style scoped>
.iconWrap {
    overflow: hidden;
}

.delete-icon {
    position: absolute;
    right: 16px;
    top: 6px;
    color: white;
    background-color: #ff00009e;
    display: flex;
    height: 15px;
    width: 15px;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 12px;
    cursor: pointer;
}
</style>
