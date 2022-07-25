<template>
    <header class="wow fadeInDown" data-wow-delay="0.5s">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="tha-network.php">
                    <img src="images/logo.png" alt="logo">
                </a>
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
                            <a class="nav-icons" href="#"><i class="fal fa-comment-lines"></i></a>
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

export default {
    name: "Header",
    components: {
        Link,
        HeaderProfileMenu
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
