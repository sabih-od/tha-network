<template>
    <div class="cardWrap">
        <div class="df aic jcsb mb-3">
            <h2 class="m-0">People In My Network</h2>
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
            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>
        </div>

        <div style="text-align: center!important;" v-if="peoples.length == 0 && search == ''">
            <h6>There is no user in my network.</h6>
        </div>
    </div>
</template>

<script>
import utils from "../../mixins/utils";
import {Link} from '@inertiajs/inertia-vue3'

export default {
    name: "PeopleList",
    mixins: [utils],
    components: {
        Link
    },
    data() {
        return {
            search: '',
            peoples: [],
            debounce: null
        }
    },
    mounted() {
        this.initateSearch();
    },
    methods: {
        initateSearch() {
            clearTimeout(this.debounce);
            this.peoples = []
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: ['friends'],
                    params: {
                        search: this.search
                    }
                }).then(res => {
                    this.peoples = res?.friends?.data.filter(element => element.is_followed == true) ?? []
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
