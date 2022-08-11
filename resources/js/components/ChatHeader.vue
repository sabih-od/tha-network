<template>
    <header class="wow fadeInDown" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; animation-name: fadeInDown;">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="tha-network.php">
                    <img src="images/logo.png" alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-bars"></span>
                </button>
                <form class="searchBar">
                    <input type="search" placeholder="Search for creators, inspiration, projects..." name="search">
                    <!-- <button type="submit"><i class="fal fa-search"></i></button> -->
                    <div class="expandSearch">
                        <p>Search for creators, inspiration, projects...</p>
                    </div>
                </form>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navIconsList ml-auto">
                        <li>
                            <a class="nav-icons" href="#"><i class="fal fa-headset"></i></a>
                        </li>
                        <li>
                            <a class="nav-icons" href="#"><i class="fal fa-comment-lines"></i></a>
                        </li>
                        <li>
                            <a class="nav-icons" href="#"><i class="fal fa-bell"></i></a>
                        </li>
                        <li>
                            <a class="nav-icons" href="#"><i class="fal fa-user"></i></a>
                        </li>
                        <li>
                            <div class="dropdown nav-icons">
                                <button class="dropdown-toggle" type="button" id="profileDropDown" data-toggle="dropdown" aria-expanded="false">
                                    <img src="images/small-character.jpg" class="rounded-circle" alt="">
                                </button>
                                <div class="dropdown-menu" aria-labelledby="profileDropDown">
                                    <a class="dropdown-item" href="profile.php">Profile</a>
                                    <a class="dropdown-item" href="edit-profile.php">Edit Profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Logout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
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
