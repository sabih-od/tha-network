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
                        <li><a href="mailto:support@thannetwork.org"><i class="fas fa-envelope"></i><span>Support@thannetwork.org</span></a>
                        </li>
                        <li class="ml-auto"><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">How It Works</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Membership Benefits</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :href="this.$route('about')">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Terms & Conditions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Privacy Policy</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";
import utils from "../mixins/utils";

export default {
    name: "HowItWorksMenu",
    mixins: [utils],
    components: {
        Link
    }
}
</script>

<style scoped>

</style>
