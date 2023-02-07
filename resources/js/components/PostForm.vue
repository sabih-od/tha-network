<template>
    <div class="postBox" v-if="!people_in_my_network_flag && !blocked_users_flag">
        <form @submit.prevent="submit">
            <ul class="topList">
                <li><a href="#" @click.prevent><i class="fal fa-edit"></i> Make Post</a></li>
            </ul>
            <div class="textAreaWrap">
                <div class="iconWrap">
                    <img :src="auth_image" class="rounded-circle" alt="">
                </div>
                <textarea v-model="form.content" placeholder="Want to Share a Memory?"></textarea>
            </div>

            <div v-if="renderedFiles.length > 0" class="row mx-2">
                <div class="col-md-2 position-relative" v-for="(file, ind) in renderedFiles" :key="ind">
                    <img v-if="file.type === 'image'" :src="file.source" class="img-fluid w-100" alt="">
                    <i class="fas fa-times delete-icon" @click.prevent="removeFile($event, file.fileInd)"></i>
                </div>
            </div>

            <!-- Video Preview -->
            <ul class="bottomList">
<!--                <li><a href="#" @click.prevent="showFeelingModal" class="optBtn"><i-->
<!--                    :class="form.feeling_icon ?? 'fal fa-smile'"></i> Feeling {{ form.feeling_text ?? 'Activity' }}</a></li>-->
<!--                <li><a href="#" @click.prevent="populate_location" class="optBtn"><i-->
<!--                    class="fas fa-map-marker-alt"></i>Location</a></li>-->
                <li>
                    <a href="#" class="optBtn" @click.prevent="$refs.selMedia.click()">
                        <i class="fas fa-images"></i> Add Media
                    </a>
                    <input type="file" ref="selMedia" name="post_video[]" class="d-none" @change.prevent="filesSelect"
                           multiple>
                </li>
                <li class="ml-auto">
                    <a href="#" @click.prevent="submit" class="btnDesign">
                        {{ form.processing ? 'Posting...' : 'Post Now' }}
                    </a>
                </li>
            </ul>
        </form>
        <teleport v-if="form.progress" to="body">
            <ImageUploadingProgress :progress="form.progress.percentage"/>
        </teleport>
    </div>
    <div class="modal fade activityModal" id="activityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-header py-0">
                    <form action="">
                        <div class="form-group">
                            <input type="text" placeholder="Search" class="form-control">
                            <button><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-sm-6" v-for="item in feelings">
                                <a href="" @click.prevent="setFormEmoji(item)" class="activityCont">
                                    <i :class="item.icon"></i>
                                    <span>Feeling {{ item.text }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-header py-0">
                    <form action="">
                        <div class="form-group">
                            <input type="text" placeholder="Search" class="form-control">
                            <button><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row align-items-center">
                            <div class="col-sm-6" v-for="item in feelings">
                                <a href="" @click.prevent class="activityCont">
                                    <i :class="item.icon"></i>
                                    <span>{{ item.text }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {useToast} from "vue-toastification";
import ImageUploadingProgress from "./ImageUploadingProgress";
import utils from "../mixins/utils";

export default {
    name: "PostForm",
    mixins: [utils],
    components: {ImageUploadingProgress},
    data() {
        return {
            form: useForm({
                content: "",
                files: [],
                location: null,
                feeling_text: null,
                feeling_icon: null
            }),
            people_in_my_network_flag: false,
            blocked_users_flag: false,
            renderedFiles: [],
            feelings: [
                {
                    text: 'Happy',
                    icon: 'fas fa-smile'
                },
                {
                    text: 'Blessed',
                    icon: 'fas fa-smile'
                },
                {
                    text: 'Loved',
                    icon: 'fas fa-grin-hearts'
                },
                {
                    text: 'Sad',
                    icon: 'fas fa-sad-tear'
                },
                {
                    text: 'Lovely',
                    icon: 'fas fa-kiss-wink-heart'
                },
                {
                    text: 'Thankful',
                    icon: 'fas fa-laugh'
                },
                {
                    text: 'Excited',
                    icon: 'fas fa-grin-stars'
                },
                {
                    text: 'In Love',
                    icon: 'fas fa-grin-hearts'
                },
                {
                    text: 'Crazy',
                    icon: 'fas fa-grin-tongue-wink'
                },
                {
                    text: 'Grateful',
                    icon: 'fas fa-laugh-beam'
                },
            ],
            geocoder: null
        }
    },
    mounted() {
        const scriptTag = document.createElement('script')
        scriptTag.src = 'http://maps.googleapis.com/maps/api/js?sensor=false'
        scriptTag.onload = () => {
            console.log('script loaded')
        }
        document.head.appendChild(scriptTag)

        let _t = this;
        this.$emitter.on('people_in_my_network_on', function() {
            _t.people_in_my_network_flag = true;
        })
        this.$emitter.on('people_in_my_network_off', function() {
            _t.people_in_my_network_flag = false;
        })
        this.$emitter.on('blocked_users_on', function() {
            _t.blocked_users_flag = true;
        })
        this.$emitter.on('blocked_users_off', function() {
            _t.blocked_users_flag = false;
        })
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
                    preserveState: true,
                    preserveScroll: true,
                    forceFormData: true,
                    onSuccess: () => {
                        this.renderedFiles = [];
                        this.form.reset();
                        this.showSuccessMessage()
                        this.$emitter.emit('post-created')
                    },
                    onFinish: () => {
                        this.showErrorMessage()
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
                        const key = this.$store.getters['Utils/uuid']
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
        },
        async populate_location() {
            this.form.location = await this.getUserLocation();
            (useToast()).success('Location Added Successfully!');
        },
        async getUserLocation() {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(function (position) {
                    resolve(JSON.stringify({
                        longitude: position.coords.longitude,
                        latitude: position.coords.latitude
                    }))
                }, err => {
                    reject(err)
                })
            })
        },
        showFeelingModal() {
            $('.activityModal').modal('show');
        },
        setFormEmoji(item) {
            this.form.feeling_text = item.text;
            this.form.feeling_icon = item.icon;
            $('.activityModal').modal('hide');
        },

        init() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(this.successFunction, this.errorFunction);
            }
            this.initialize();
        },
        successFunction(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            this.codeLatLng(lat, lng)
        },
        errorFunction(){
            alert("Geocoder failed");
        },
        initialize() {
            this.geocoder = new google.maps.Geocoder();
        },
        codeLatLng(lat, lng) {

            var latlng = new google.maps.LatLng(lat, lng);
            this.geocoder.geocode({'latLng': latlng}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    console.log(results)
                    if (results[1]) {
                        //formatted address
                        alert(results[0].formatted_address)
                        //find country name
                        for (var i=0; i<results[0].address_components.length; i++) {
                            for (var b=0;b<results[0].address_components[i].types.length;b++) {

                                //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                                if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
                                    //this is the object you are looking for
                                    city= results[0].address_components[i];
                                    break;
                                }
                            }
                        }
                        //city data
                        alert(city.short_name + " " + city.long_name)


                    } else {
                        alert("No results found");
                    }
                } else {
                    alert("Geocoder failed due to: " + status);
                }
            });
        }

    }
}
</script>

<style scoped lang="scss">
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

#activityModal,
#locationModal {
    .modal-content {
        padding: 3rem 0;

        .modal-body {
            max-height: 350px;
            overflow-y: auto;
        }

        .row {
            gap: 1.25rem 0;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 11;
        }
    }

    form {
        width: 100%;

        .form-group {
            position: relative;

            input {
                outline: none;
                box-shadow: none;
                height: 40px;
            }

            button {
                position: absolute;
                top: 0;
                right: 0;
                background: var(--primary);
                border: none;
                color: #fff;
                height: 40px;
                width: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 0;
                outline: none;
                box-shadow: none;
            }
        }
    }

    .activityCont {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        background: rgba(255, 255, 255, 0.15);
        justify-content: center;
        border-radius: 10px;
        padding: 0.5rem 0;
        box-shadow: 0 0 15px #ddd;
        cursor: pointer;

        i {
            color: var(--primary);
            font-size: 1.25rem;
        }

        span {
            font-size: 1rem;
            font-weight: 500;
            color: #000;
        }

        &:hover {
            background: var(--primary);

            i,
            span {
                color: #fff;
            }
        }
    }
}
</style>
