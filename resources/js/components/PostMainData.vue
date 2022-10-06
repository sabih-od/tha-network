<template>
    <div :class="{'shared-post': isSharable}">
        <div class="df aic jcsb mb-3 w-100">
            <Link replace :href="profile_link(post.user.id)" class="infoWrap">
                <div class="userWrap"><img :src="profile_image(post?.user?.profile_image)" class="rounded-circle" alt="">
                </div>
                <div class="userNameWrap">
                    <h2>@{{ post.user.username }} <span>{{ $store.getters['Utils/fromNow'](post?.created_at) }}</span></h2>
                    <span>-</span>
                    <h6><i class="fas fa-smile"></i><span>Feeling Happy!</span></h6>
                    <h5>at<span>LA, California</span></h5>
                </div>
            </Link>
            <div v-if="isCreatedByMe && !isSharable" class="dropdown">
                <button class="dropdown-toggle" type="button" id="feed-option" data-toggle="dropdown"
                        aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="feed-option">
                    <a class="dropdown-item" href="#" @click.prevent="postDelete">Delete Post</a>
                </div>
            </div>
        </div>
        <template v-if="isMedia">
            <div class="row">
                <div
                    v-for="(item, ind) in media_items"
                    :key="ind"
                    :class="{
                        'col-md-12': isOneMedia,
                        'col-md-6 mb-3': !isOneMedia,
                        'd-none': isIndexGreaterThan4(ind+1)
                    }">
                    <div :href="item?.url" :data-fancybox="fancyBoxId" class="videoImg">
                        <span v-if="!isImage(item?.mime_type)"><i class="fas fa-play"></i></span>
                        <img :src="!isImage(item?.mime_type) ? item?.video_thumb:item?.url"
                             alt="video"
                             class="img-fluid w-100 img-fit">
                    </div>

                </div>
            </div>
        </template>
        <p v-if="post?.content" class="mb-3 text-prewrap" :class="{'mt-0': !isMedia}">{{ post?.content }}</p>
    </div>
</template>

<script>
import {Link, usePage} from '@inertiajs/inertia-vue3'
import utils from "../mixins/utils";
import {Inertia} from "@inertiajs/inertia";
import _ from "lodash";

export default {
    name: "PostMainData",
    mixins: [utils],
    components: {
        Link
    },
    props: {
        post: Object,
        isSharable: {
            default: false,
            type: Boolean
        },
    },
    data() {
        return {
            media_items: this.post?.media_items ?? []
        }
    },
    mounted() {
        for (const mediaItemsKey in this.media_items) {
            if (!this.isImage(this.media_items[mediaItemsKey]?.mime_type)) {
                this.getVideoThumb(this.media_items[mediaItemsKey].url).then(res => {
                    this.media_items[mediaItemsKey]['video_thumb'] = res
                })
            }
        }
    },
    computed: {
        isCreatedByMe() {
            if (this.post?.user_id && usePage().props.value?.auth?.id)
                return this.post?.user_id === usePage().props.value?.auth?.id
            return false
        },
        isMedia() {
            return this.post?.media_items && this.post.media_items.length > 0
        },
        isImage() {
            return (type) => /^(image\/)[\w]+$/.test(type)
        },
        fancyBoxId() {
            return (this.isSharable ? 'share_' + (Date.now().toString(36) + Math.random().toString(36).substr(2)) : '') + this.post?.id
        },
        isOneMedia() {
            return (this.media_items?.length ?? 0) < 2
        },
        isIndexGreaterThan4() {
            return (ind) => ind > 4
        }
    },
    methods: {
        getVideoThumb(file_url) {
            return new Promise(res => {
                let reader = new FileReader();
                reader.onload = function () {
                    res(reader.result)
                }
                this.$store.dispatch('Utils/getVideoCover', {
                    file_url,
                    seekTo: 2
                }).then(res => {
                    reader.readAsDataURL(res);
                })
            })
        },
        postDelete() {
            Inertia.delete(this.$route('postDelete', this.post.id), {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    this.$emitter.emit('post-deleted')
                    this.showSuccessMessage()
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
.shared-post {
    border: 2px solid #e7e7e7;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
}

.img-fit {
    object-fit: contain;
    max-height: 300px;
}

.videoImg > span {
    background-color: #ffffff80;
    position: absolute;
    left: calc(50% - 25px);
    top: calc(50% - 25px);
    height: 50px;
    width: 50px;
    border-radius: 50%;
    z-index: 999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.text-prewrap {
    white-space: pre-wrap;
}

.userNameWrap {
    display: flex;
    align-items: self-start;
    gap: 0.25rem;
}

.userNameWrap h6 {
    font-size: 0.75rem;
    margin-top: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--primary);
}

.userNameWrap h5 {
    font-size: 0.75rem;
    font-weight: 300;
    margin-top: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    color: #000;
}

.userNameWrap h5 span{
    font-weight: 600;
    color: var(--primary);
}
</style>
