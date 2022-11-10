<template>
    <header class="wow fadeInDown" data-wow-delay="0.5s">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <Link class="navbar-brand" :href="$route('loginForm')" replace>
                    <img :src="asset('images/logo.png')" alt="">
                </Link>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-bars"></span>
                </button>

                <div class="collapse navbar-collapse flex-column" id="navbarSupportedContent">
                    <ul class="list-unstyled socialIo">
                        <li><a href="mailto:support@thanetwork.org"><i class="fas fa-envelope"></i><span>support@thanetwork.org</span></a>
                        </li>
                        <li class="ml-auto"><a href="https://www.facebook.com/Tha-Network-150057600527324/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="https://twitter.com/ThaNetwork4" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="https://www.instagram.com/_thanetwork_/" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="https://www.tiktok.com/@_thanetwork_?lang=en" target="_blank"><img style="filter: contrast(0)" :src="asset('images/simple-tiktok.png')" alt=""></a></li>
                        <li><a href="https://www.youtube.com/channel/UCBf0MeQqY_T1Oqtw2qOK7Fg" target="_blank"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li :class="url === 'work'? 'nav-item active' : 'nav-item'">
                            <a class="nav-link" :href="this.$route('work')">How It Works</a>
                        </li>
                        <li :class="url === 'benefits'? 'nav-item active' : 'nav-item'">
                            <a class="nav-link" :href="this.$route('benefits')">Membership Benefits</a>
                        </li>
                        <li :class="url === 'about'? 'nav-item active' : 'nav-item'">
                            <a class="nav-link" :href="this.$route('about')">About Us</a>
                        </li>
                        <li :class="url === 'terms'? 'nav-item active' : 'nav-item'">
                            <a class="nav-link" :href="this.$route('terms')">Terms & Conditions</a>
                        </li>
                        <li :class="url === 'privacy'? 'nav-item active' : 'nav-item'">
                            <a class="nav-link" :href="this.$route('privacy')">Privacy Policy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :href="this.$route('about')">Contact Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :href="this.$route('login')">{{ this.user ? 'Go Back To Profile' : 'Login' }}</a>
                        </li>
<!--                        <li :class="$Link === 'about'? 'nav-item active' : 'nav-item'">-->
<!--                            <a class="nav-link" :href="this.$route('about')">Contact Us</a>-->
<!--                        </li>-->
<!--                        <li :class="url === 'login'? 'nav-item active' : 'nav-item'">-->
<!--                            <a class="nav-link" :href="this.$route('login')">Login</a>-->
<!--                        </li>-->
                    </ul>
                </div>
            </nav>
        </div>
    </header>
</template>

<script>
import {Link, useForm, usePage} from "@inertiajs/inertia-vue3";
import utils from "../mixins/utils";
import {useToast} from "vue-toastification";

export default {
    name: "HowItWorksMenu",
    mixins: [utils],
    components: {
        Link
    },
    computed: {
        user() {
            return usePage().props.value?.auth ?? null
        }
    },
    data() {
        return {
            url : window.location.href.split('/')[-1],
            page: usePage().component.value
        }
    },
}
</script>

<style scoped>

</style>
