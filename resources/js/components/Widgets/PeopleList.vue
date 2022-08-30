<template>
    <div class="cardWrap">
        <div class="df aic jcsb mb-3">
            <h2 v-if="page_type != 'UserProfile'" class="m-0">People In My Network</h2>
            <h2 v-if="page_type == 'UserProfile'" class="m-0">Prople In user's network </h2>
            <a href="#" class="viewBtn">See all</a>
        </div>
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
            <FollowUserButton :user_id="user.id" :is_followed="user.is_followed" :request_sent="user.request_sent" :request_received="user.request_received" @update_is_followed="user.is_followed = !user.is_followed"></FollowUserButton>
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
        }
    },
    data() {
        return {
            search: '',
            peoples: [],
            debounce: null,
            user_id: null
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
                        user_id: this.user_id
                    }
                }).then(res => {
                    this.peoples = res?.network_members?.data.filter(element => element.has_blocked == false) ?? []
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
