<template>
    <Main>
        <CoverPhoto/>
        <slot></slot>
        <!--        <section>
                    <CoverPhoto/>
                    <div class="container">
                        <div class="topWrap">
                            <div class="row aic">
                                <div class="col-md-6">
                                    <UserInfo/>
                                </div>
                                <div class="col-md-6">
                                    <div class="btn-group">
                                        <a v-if="isFollowable" href="#" @click.prevent="blockToggle" class="themeBtn">{{
                                                blockText
                                            }}</a>
                                        <a v-if="isFollowable" href="#" @click.prevent="follow" class="themeBtn">{{
                                                buttonText
                                            }}</a>
                                        <a href="#" @click.prevent class="themeBtn">personal chat group</a>
                                        <Link v-if="!$store.state.Profile.is_another" replace :href="$route('editProfileForm')"
                                              class="themeBtn">Edit profile
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <ProfileLeftSide/>
                            <slot></slot>
                        </div>
                    </div>
                </section>-->
    </Main>
</template>

<script>
import Main from "./Main";
import ProfileLeftSide from "../components/ProfileLeftSide";
import UserInfo from "../components/UserInfo";
import {Link} from '@inertiajs/inertia-vue3'
import CoverPhoto from "../components/CoverPhoto";
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "ProfileLayout",
    components: {
        Main,
        ProfileLeftSide,
        Link,
        UserInfo,
        CoverPhoto,
    },
    data() {
        return {
            user_id: this.$page.props?.user?.id,
            d_is_following: this.$page.props?.is_following ?? false,
            d_has_blocked: this.$page.props?.has_blocked ?? false,
        }
    },
    computed: {
        isFollowable() {
            return this.user_id !== this.$page.props?.auth?.id
        },
        buttonText() {
            return this.isFollowing ? 'Following' : '+ Follow'
        },
        blockText() {
            return !this.isBlocked ? 'Block' : 'UnBlock'
        },
        isFollowing() {
            return this.d_is_following
        },
        isBlocked() {
            return this.d_has_blocked
        },
    },
    updated() {
        this.user_id = this.$page.props?.user?.id
        this.d_is_following = this.$page.props?.is_following ?? false
        this.d_has_blocked = this.$page.props?.has_blocked ?? false
    },
    mounted() {
        this.$emitter.on('post-follow-user-toggle', val => {
            if (this.user_id === val.user_id) {
                this.d_is_following = val.is_following
            }
        })
    },
    methods: {
        follow() {
            Inertia.post(this.$route('userFollowToggle'), {
                user_id: this.user_id
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    this.$store.commit('Post/setFollowStatus', {
                        user_id: this.user_id,
                        path: 'user.is_followed',
                        value: this.isFollowing
                    });
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        },
        blockToggle() {
            Inertia.post(this.$route('userBlockToggle'), {
                user_id: this.user_id
            }, {
                replace: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    /*this.$store.commit('Post/setFollowStatus', {
                        user_id: this.user_id,
                        path: 'user.is_followed',
                        value: this.isFollowing
                    });*/
                },
                onFinish: () => {
                    window.history.replaceState({}, '', this.$store.getters['Utils/baseUrl'])
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
