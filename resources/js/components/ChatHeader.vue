<template>
    <header class="wow fadeInDown" data-wow-delay="0.5s">
        <div class="container-fluid px-0">
            <div class="row aic mx-0 no-gutters">
                <div class="col-md-2">
                    <Link class="logo" :href="$route('home')" replace>
                        <img src="images/logo-white.png" alt="logo">
                    </Link>
                </div>
                <div class="col-md-8">
                    <div class="df aic">
                        <a v-if="chatSelect" href="#" @click.prevent class="grpName">
                            <ProfileImageIconRounded :profile_img="chatSelect?.profile_img"/>
                            <h2>{{ chatSelect?.name }}</h2>
                        </a>
                        <!--                        <p><span>#group challenge</span>Lorem ipsum dolor sit amet, consectetur adipiscing</p>-->
                        <div class="btn-group">
                            <a v-if="chatSelect" href="#"><i class="fal fa-video"></i></a>
                            <!--                            <div class="dropdown">
                                                            <button class="dropdown-toggle" type="button" id="dropdownNotifications"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-bell"></i><span>5</span>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownNotifications">
                                                                <a class="dropdown-item" href="#">Action</a>
                                                                <a class="dropdown-item" href="#">Another action</a>
                                                                <a class="dropdown-item" href="#">Something else here</a>
                                                            </div>
                                                        </div>-->
                            <HeaderProfileMenu/>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <ChatSearchForm/>
                </div>
            </div>
        </div>
    </header>
</template>

<script>
import ChatSearchForm from "./ChatSearchForm";
import {Link} from '@inertiajs/inertia-vue3'
import HeaderProfileMenu from "./HeaderProfileMenu";
import ProfileImageIconRounded from "./ProfileImageIconRounded";

export default {
    name: "ChatHeader",
    components: {
        ChatSearchForm,
        HeaderProfileMenu,
        ProfileImageIconRounded,
        Link
    },
    computed: {
        cover_image() {
            return (url) => {
                return url ?? this.$store.getters['Utils/public_asset']('images/small-character.jpg')
            }
        },
    },
    data() {
        return {
            chatSelect: null
        }
    },
    mounted() {
        this.$emitter.on('chat_active_user_data', this.onChatActiveUserData)
    },
    unmounted() {
        this.$emitter.off('chat_active_user_data')
    },
    methods: {
        onChatActiveUserData(data) {
            this.chatSelect = data
        }
    }
}
</script>

<style scoped>

</style>
