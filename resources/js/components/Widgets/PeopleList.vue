<template>
    <div class="cardWrap">
        <div class="df aic jcsb mb-3">
            <h2 v-if="page_type != 'UserProfile'" class="m-0">People in My Network</h2>
            <h2 v-if="page_type == 'UserProfile'" class="m-0">People in User's Network </h2>
            <a href="#" @click.prevent="seeAll()" class="viewBtn">{{ all ? 'Collapse' : 'See all' }}</a>
        </div>
        <form action="">
            <div class="searchlist">
                <i class="fal fa-search"></i>
                <input type="search" placeholder="Search" name="search" v-model="search" @keyup.prevent="initateSearch()" autocomplete="off">
                <Link :href="$route('chatIndex')"><i class="fal fa-sliders-h"></i></Link>
            </div>
        </form>
        <div class="userList" v-for="user in peoples">
            <div class="userInfo">
                <Link :href="$route('userProfile', user.id)"><img :src="user.profile_img ? user.profile_img : asset('images/char-usr.png')" class="rounded-circle" alt=""></Link>
                <h3>
                    <Link :href="$route('userProfile', user.id)">
                        <strong>{{user.profile ? user.profile.first_name +' '+ user.profile.last_name : ''}}</strong>
                    </Link>
                    <a href="#">Connect</a>
                </h3>
            </div>
            <FollowUserButton v-if="!isMe(user.id)" :user_id="user.id" :is_followed_by_auth="user.is_followed_by_auth" :is_followed="user.is_followed" :request_sent="user.request_sent" :request_received="user.request_received" @update_is_followed="user.is_followed = !user.is_followed"></FollowUserButton>
<!--            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>-->
        </div>

        <div style="text-align: center!important;" v-if="peoples.length == 0 && search == ''">
            <h6>There is no user in my network.</h6>
        </div>
    </div>
</template>

<script>
import utils from "../../mixins/utils";
import {Link, usePage} from '@inertiajs/inertia-vue3'
import FollowUserButton from "../FollowUserButton";

export default {
    name: "PeopleList",
    mixins: [utils],
    components: {
        Link,
        FollowUserButton
    },
    computed: {
        page_type: function page_type() {
            return usePage().component.value;
        },
        isMe() {
            return (user_id) => {
                if (user_id && usePage().props.value?.auth?.id)
                    return user_id === usePage().props.value?.auth?.id
                return false
            }
        }
    },
    data() {
        return {
            search: '',
            peoples: [],
            debounce: null,
            user_id: null,
            all: false
        }
    },
    mounted() {
        this.initateSearch();
        if (this.page_type === 'UserProfile') {
            this.user_id = this.$parent.user.id;
        }
    },
    methods: {
        initateSearch() {
            clearTimeout(this.debounce);
            this.peoples = []
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: ['network_members'],
                    params: {
                        search: this.search,
                        user_id: this.user_id,
                        all: this.all
                    }
                }).then(res => {
                    this.peoples = res?.network_members?.data.filter(element => element.has_blocked == false) ?? []
                }).finally(() => {
                    // this.loading = false
                })
            }, 600);
        },
        seeAll() {
            this.all = !this.all;
            this.initateSearch();
        }
    }
}
</script>

<style scoped>

</style>
