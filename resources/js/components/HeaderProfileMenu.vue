<template>
    <div class="dropdown nav-icons">
        <button class="dropdown-toggle" type="button" id="profileDropDown" data-toggle="dropdown" aria-expanded="false">
            <img :src="generic_avatar_image" class="rounded-circle" alt="">
        </button>
        <div class="dropdown-menu" aria-labelledby="profileDropDown">
            <Link class="dropdown-item" replace :href="$route('profile')">How others see your profile</Link>
            <Link class="dropdown-item" replace :href="$route('home')" @click.prevent="peopleInMtNetworkOn">People in my network</Link>
            <Link class="dropdown-item" replace :href="$route('home')" @click.prevent="blockedUsers">Blocked Users</Link>
            <Link class="dropdown-item" replace :href="$route('loginForm')">Home</Link>
            <Link class="dropdown-item" replace :href="$route('editProfileForm')">Edit Profile</Link>
            <Link class="dropdown-item" replace :href="$route('work')">Introduction</Link>
            <div class="dropdown-divider"></div>
            <Link @click="hasLoggedOut()" :href="$route('logout')" method="post" replace :headers="logoutHeaders"
                  class="dropdown-item">Logout
            </Link>
        </div>
    </div>

    <!--    <div class="dropdown">
            <button class="dropdown-toggle" type="button" id="profileDropDown" data-toggle="dropdown"
                    aria-expanded="false">
                <img :src="profile_img" class="rounded-circle profileImgIcon" alt="">
            </button>
            <div class="dropdown-menu" aria-labelledby="profileDropDown">
                <Link replace class="dropdown-item" :href="$route('profile')">My Profile</Link>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#tokenModal">Buy
                    Tokens</a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#changePassModal">Change
                    Password</a>
                <div class="dropdown-divider"></div>
                <Link :href="$route('logout')" method="post" replace :headers="logoutHeaders"
                      class="dropdown-item">Logout
                </Link>
            </div>
        </div>-->
</template>

<script>
import {Link, usePage} from "@inertiajs/inertia-vue3";
import utils from "../mixins/utils";

export default {
    name: "HeaderProfileMenu",
    mixins: [utils],
    components: {
        Link
    },
    data() {
        return {
            logoutHeaders: {
                'Cache-Control': 'nocache, no-store, max-age=0, must-revalidate',
                'Pragma': 'nocache, no-store, max-age=0, must-revalidate',
                'Expires': 'Fri, 01 Jan 1990 00:00:00 GMT',
            }
        }
    },
    mounted() {
        // console.log(usePage().props.value?.auth ?? null)
    },
    methods: {
        hasLoggedOut() {
            this.$store.commit('Misc/setHasLoggedOut', true);
        },
        peopleInMtNetworkOn() {
            this.$store.commit('Misc/setPeopleInMyNetworkFlag', true);
            this.$store.commit('Misc/setBlockedUsersFlag', false);
            this.$store.commit('Misc/setMyFriendsFlag', false);
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#ref_post_list_item0").offset().top - 400
            }, 1000);
        },
        blockedUsers() {
            this.$store.commit('Misc/setBlockedUsersFlag', true);
            this.$store.commit('Misc/setPeopleInMyNetworkFlag', false);
            this.$store.commit('Misc/setMyFriendsFlag', false);
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#ref_post_list_item0").offset().top - 400
            }, 1000);
        }
    }
}
</script>

<style scoped>
.profileImgIcon {
    max-width: 40px;
}
</style>
