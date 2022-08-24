<template>
<!--    <a href="#" @click.prevent="select" class="grpName">-->
<!--        <ProfileImageIconRounded :profile_img="cover?.profile_img"/>-->
<!--        <h2>{{ cover?.name }}</h2>-->
<!--    </a>-->

    <a href="#" @click.prevent="select">
        <div class="friend-drawer friend-drawer--onhover">

            <ProfileImageIconRounded :profile_img="cover?.profile_img"/>
            <div class="text">
                <h6>{{ cover?.profile?.first_name + ' ' + cover?.profile?.last_name}}</h6>
            </div>
        </div>
    </a>
</template>

<script>
import ProfileImageIconRounded from "./ProfileImageIconRounded";

export default {
    name: "ChatListingItem",
    components: {
        ProfileImageIconRounded
    },
    props: {
        channel_id: String,
        cover: Object
    },
    methods: {
        select() {
            this.$emitter.emit('chat_active', this.channel_id)
            this.$emitter.emit('chat_active_user_data', this.cover)
        }
    }
}
</script>

<style scoped>
.imgWrap img {
    width: 50px;
    height: 50px;
    object-fit: cover;
}
</style>
