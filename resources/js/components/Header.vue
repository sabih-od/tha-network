<template>
    <header class="wow fadeInDown" data-wow-delay="0.5s">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg">
                <Link replace class="navbar-brand" :href="$route('home')">
                    <img :src="asset('images/logo.png')" alt="logo">
                </Link>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
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
<!--                            <Link :href="$route('chatIndex')" replace><i class="fal fa-comments"></i> <span>5</span></Link>-->
                            <Link :href="$route('chatIndex')" class="nav-icons"><i class="fal fa-comment-lines"></i></Link>
                        </li>
                        <li>
                            <a class="nav-icons" href="#"><i class="fal fa-bell"></i></a>
                        </li>
                        <li>
                            <a class="nav-icons" href="#"><i class="fal fa-user"></i></a>
                        </li>
                        <li>
                            <HeaderProfileMenu/>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
</template>

<script>
import {Link, usePage} from '@inertiajs/inertia-vue3'
import HeaderProfileMenu from "./HeaderProfileMenu";
import Chat from "../Pages/Chat";
import utils from "../mixins/utils";

export default {
    name: "Header",
    mixins: [utils],
    components: {
        Link,
        HeaderProfileMenu,
        Chat
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
    }
}
</script>

<style scoped>

</style>
