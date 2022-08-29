<template>
    <div class="cardWrap">
        <h2 v-if="only == 'new_members'">This Weeks New Members to Tha Network</h2>
        <h2 v-if="only == 'friends'">Friends List</h2>
        <form action="">
            <div class="searchlist">
                <i class="fal fa-search"></i>
                <input type="search" placeholder="Search messages" name="search" v-model="search" @keyup.prevent="initateSearch()" autocomplete="off">
                <button><i class="fal fa-sliders-h"></i></button>
            </div>
        </form>
        <div class="userList" v-for="user in peoples">
            <div class="userInfo">
                <Link :href="$route('userProfile', user.id)"><img :src="asset('images/user1.jpg')" class="rounded-circle" alt=""></Link>
                <h3>
                    <Link :href="$route('userProfile', user.id)">
                        <strong>{{user.profile.first_name +' '+ user.profile.last_name}}</strong>
                    </Link>
                    <a href="#">Connect</a>
                </h3>
            </div>
<!--            follow button-->
<!--            <a href="#" class="nav-icons"><i class="fal fa-user-plus"></i></a>-->
            <FollowUserButton :user_id="user.id" :is_followed="user.is_followed" @update_is_followed="user.is_followed = !user.is_followed"></FollowUserButton>
        </div>

        <div style="text-align: center!important;" v-if="peoples.length == 0 && search == ''">
            <h6>There are no new users yet.</h6>
        </div>
    </div>
</template>

<script>
import utils from "../../mixins/utils";
import {Link, usePage} from '@inertiajs/inertia-vue3'
import FollowUserButton from "../FollowUserButton";
import HeaderProfileMenu from "../HeaderProfileMenu";
import Chat from "../../Pages/Chat";

export default {
    name: "NewMembersList",
    mixins: [utils],
    components: {
        Link,
        FollowUserButton
    },
    computed: {
        page_type: function page_type() {
            return usePage().component.value;
        }
    },
    data() {
        return {
            search: '',
            peoples: [],
            debounce: null,
            user_id: null,
            only: 'new_members'
        }
    },
    mounted() {
        this.initateSearch();
        if (this.page_type === 'UserProfile') {
            this.user_id = this.$parent.user.id;
            this.only = 'friends';
        } else {
            this.user_id = null;
            this.only = 'new_members';
        }
    },
    methods: {
        initateSearch() {
            clearTimeout(this.debounce);
            this.peoples = []
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: [this.only],
                    params: {
                        search: this.search,
                        user_id: this.user_id
                    }
                }).then(res => {
                    if (this.only == 'new_members')
                        this.peoples = res?.new_members?.data.filter(element => element.has_blocked == false) ?? []
                    else
                        this.peoples = res?.friends?.data.filter(element => element.is_followed == true && element.has_blocked == false) ?? []
                }).finally(() => {
                    // this.loading = false
                })
            }, 600);
        }
    }
}
</script>

<style scoped>

</style>
