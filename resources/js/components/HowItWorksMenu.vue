<template>
    <header class="wow fadeInDown" data-wow-delay="0.5s">
        <div class="container">
            <nav class="navbar navbar-expand-lg">

                <Link v-if="visitedByLink || visitedByCode" class="navbar-brand" :href="$route('loginForm')" @click.prevent.capture>
                    <img :src="asset('images/logo.png')" alt="">
                </Link>
                <Link v-else class="navbar-brand" :href="$route('loginForm')" replace>
                    <img :src="asset('images/logo.png')" alt="">
                </Link>

                <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="fa fa-bars"></span>
                </button>

                <div class="collapse navbar-collapse flex-column" id="navbarSupportedContent">
                    <ul class="list-unstyled socialIo">
                        <li><a href="mailto:info@thanetwork.org"><i class="fas fa-envelope"></i><span>info@thanetwork.org</span></a>
                        </li>
                        <li class="ml-auto"><a href="https://www.facebook.com/Tha-Network-150057600527324/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="https://twitter.com/ThaNetwork4" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="https://www.instagram.com/_thanetwork_/" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="https://www.tiktok.com/@_thanetwork_?lang=en" target="_blank"><img style="filter: contrast(0)" :src="asset('images/simple-tiktok.png')" alt=""></a></li>
                        <li><a href="https://www.youtube.com/channel/UCBf0MeQqY_T1Oqtw2qOK7Fg" target="_blank"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li :class="page === 'HowItWorks' ? 'nav-item active' : 'nav-item'">
                            <Link class="nav-link" :href="$route('work')">How It Works</Link>
                        </li>
                        <li :class="page === 'Benifits'? 'nav-item active' : 'nav-item'">
                            <Link class="nav-link" :href="$route('benefits')">Membership Benefits</Link>
                        </li>
                        <li :class="page === 'About'? 'nav-item active' : 'nav-item'">
                            <Link class="nav-link" :href="$route('about')">About Us</Link>
                        </li>
                        <li :class="page === 'Terms'? 'nav-item active' : 'nav-item'">
                            <Link class="nav-link" :href="$route('terms')">Terms & Conditions</Link>
                        </li>
                        <li :class="page === 'Privacy'? 'nav-item active' : 'nav-item'">
                            <Link class="nav-link" :href="$route('privacy')">Privacy Policy</Link>
                        </li>

                        <li :class="page === 'Privacy'? 'nav-item active' : 'nav-item'">
                            <Link class="nav-link" :href="$route('faqs')">FAQs</Link>
                        </li>
<!--                        <li  :class="page === 'Contact'? 'nav-item active' : 'nav-item'">-->
<!--                            <Link class="nav-link" :href="$route('contact')">Contact Us</Link>-->
<!--                        </li>-->
                        <li class="nav-item" v-if="!visitedByLink && !visitedByCode">
                            <Link class="nav-link" :href="$route('login')">{{ this.user ? 'Go Back To Profile' : 'Login' }}</Link>
                        </li>
<!--                        <li :class="$Link === 'about'? 'nav-item active' : 'nav-item'">-->
<!--                            <a class="nav-link" :href="$route('about')">Contact Us</a>-->
<!--                        </li>-->
<!--                        <li :class="url === 'login'? 'nav-item active' : 'nav-item'">-->
<!--                            <a class="nav-link" :href="$route('login')">Login</a>-->
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
    props: {
        visitedByCode: Boolean,
    },
    computed: {
        user() {
            return usePage().props.value?.auth ?? null
        },
        visitedByLink() {
            return window.location.toString().includes('join');
        }
    },
    data() {
        return {
            url : window.location.href.split('/')[-1],
            page: usePage().component.value
        }
    },
    mounted() {
        // alert(window.location);
    }
}
</script>

<style scoped>

</style>
