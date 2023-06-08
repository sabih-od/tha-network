<template>
    <header class="wow fadeInDown" data-wow-delay="0.5s">
        <div class="container-fluid">
            <nav class="navbar navbar-expand">
                <Link replace class="navbar-brand" :href="$route('work')">
                    <img :src="asset('images/logo.png')" alt="logo">
                </Link>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navIconsList ml-auto">
                        <li class="dropdown">
                            <button class="dropdown-toggle nav-icons" type="button" id="support" data-toggle="dropdown" aria-expanded="false">
                                <i class="fal fa-headset"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="support">
                                <div class="dropdown-item">
                                    <p>
                                        If you need assistance or need to report another member, contact us at
                                        <a href="mailto:info@thanetwork.org">info@thanetwork.org</a>
                                    </p>
                                </div>
                            </div>
<!--                            <a class="nav-icons" href="#"><i class="fal fa-headset"></i></a>-->
                        </li>
                        <li>
<!--                            <Link :href="$route('chatIndex')" replace><i class="fal fa-comments"></i> <span>5</span></Link>-->
                            <Link :href="$route('chatIndex')" class="nav-icons"><i class="fal fa-comment-lines"></i></Link>
                        </li>
<!--                        <li>-->
<!--                            <a class="nav-icons" href="#"><i class="fal fa-bell"></i></a>-->
<!--                        </li>-->
                        <li>
                            <ChatMessagesCounterButton/>
                        </li>
                        <li>
                            <Link class="nav-icons" :href="$route('profile')"><i class="fal fa-user"></i></Link>
                        </li>
                        <li>
                            <HeaderProfileMenu/>
                        </li>
                    </ul>
                </div>
                <form class="searchBar">
                    <input type="search" placeholder="Search" name="search" v-model="search" @keyup.prevent="initateSearch()" autocomplete="off">
                    <!-- <button type="submit"><i class="fal fa-search"></i></button> -->
                    <div class="expandSearch" ref="expand_search_visibility">
                        <p v-if="loading" class="text-secondary px-3">Please wait...</p>
                        <Link v-for="user in peoples" @click.prevent="goToProfile()" :href="$route('userProfile', user.id)"><h4>{{user.profile? user.profile?.first_name + ' ' + user.profile?.last_name : ''}}</h4><p>{{user.username ? '@' + user.username : ''}}</p></Link>
<!--                        <Link v-for="user in peoples" @click.prevent="goToProfile()" :href="$route('userProfile', user.id)"><p>{{user.username ? user.username : ''}}</p></Link>-->
                    </div>
                </form>
            </nav>
        </div>
    </header>
</template>

<script>
import {Link, usePage} from '@inertiajs/inertia-vue3'
import HeaderProfileMenu from "./HeaderProfileMenu";
import Chat from "../Pages/Chat";
import utils from "../mixins/utils";
import ChatMessagesCounterButton from "./ChatMessagesCounterButton";

export default {
    name: "Header",
    mixins: [utils],
    components: {
        Link,
        HeaderProfileMenu,
        Chat,
        ChatMessagesCounterButton
    },
    data() {
        return {
            search: '',
            peoples: [],
            loading: false,
            debounce: null
        }
    },
    mounted() {
        this.$nextTick(() => {
            $(".searchBar input").focus(function () {
                $('.expandSearch').slideDown('slow');
                //return false;
            });
            $('.searchBar input').blur(function () {
                if (!$(this).val()) {
                    $('.expandSearch').slideUp('slow');
                }
            });
        })
    },
    methods: {
        initateSearch() {if (this.loading) return;
            clearTimeout(this.debounce);
            this.loading = true
            this.peoples = []
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: ['peoples'],
                    params: {
                        search: this.search
                    }
                }).then(res => {
                    this.peoples = res?.peoples?.data.filter(element => element.has_blocked == false) ?? []
                    this.peoples = this.exactSearchSanitizer(this.peoples, this.search);
                }).finally(() => {
                    this.loading = false
                })
            }, 600);
        },
        goToProfile() {
            this.search = '';
            this.peoples = [];
            $('.expandSearch').slideUp('slow');
        },
        exactSearchSanitizer (list, query) {
            let final_list = [];
            for (var i = 0; i < list.length; i++) {
                if(list[i].username == query) {
                    final_list = [list[i]];
                    break;
                }

                final_list.push(list[i]);
            }
            return final_list;
        }
    }
}
</script>

<style scoped>

</style>
