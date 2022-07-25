<template>
    <div :class="{'shared-post': isSharable}">
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

        <div class="df aic jcsb my-3 w-100">
            <Link :href="$store.getters['Utils/generateProfileLink'](post?.user?.id)" replace class="infoWrap">
                <div class="iconWrap"><img :src="profile_image" alt=""></div>
                <h2>{{ post?.user?.name }} <span>{{ $store.getters['Utils/fromNow'](post?.created_at) }} </span></h2>
            </Link>
            <template v-if="isFollowEnable">
                <FollowUserButton :user_id="post?.user?.id" :post_id="post?.id" :is-post-shared="isSharable"/>
            </template>
        </div>
    </div>
</template>

<script>
import FollowUserButton from "./FollowUserButton";
import {Link} from '@inertiajs/inertia-vue3'

export default {
    name: "PostMainData",
    components: {
        FollowUserButton,
        Link
    },
    props: {
        post: Object,
        isSharable: {
            default: false,
            type: Boolean
        },
        isFollowEnable: {
            default: true,
            type: Boolean
        }
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
        },
        profile_image() {
            return this.post?.user?.profile_img ?? this.$store.getters['Utils/public_asset']('images/ph-profile.jpg')
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
    object-fit: cover;
    height: 300px;
}
</style>
