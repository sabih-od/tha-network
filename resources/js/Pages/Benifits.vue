<template>
    <div>
        <HowItWorksMenu/>
        <div class="main-slider">
            <img class="img-fluid w-100" :src="data.banner_image" alt="First slide">
            <div class="carousel-caption">
                <div class="container">
                    <div class="row aic justify-content-center">
                        <div class="col-md-6">
                            <div class="content">
                                <h3>{{ data.banner_circle_title }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="abtSec">
            <div class="container">
                <div class="row aic">
                    <div class="col-md-6">
                        <figure>
                            <img class="img-fluid w-100" :src="data.section_2_image" alt="">
                        </figure>
                    </div>
                    <div class="col-md-6">
                        <div class="secHeading">
                            <h2>{{ data.section_2_title }}</h2>
                            <p>{{data.section_2_description_line_1}}</p>
                            <p>{{data.section_2_description_line_2}}</p>
                            <p>{{data.section_2_description_line_3}}</p>
                        </div>
                        <!--                        <a href="#" class="themeBtn" v-if="!user">JOIN US</a>-->
                    </div>
                </div>
                <div class="row aic">
                    <div class="col-md-6">
                        <div class="secHeading">
                            <h2>{{ data.section_3_title }}</h2>
                            <p>{{data.section_3_description}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <figure><img :src="data.section_3_image" alt="" class="img-fluid w-100"></figure>
                    </div>
                </div>
                <div class="row aic">
                    <div class="col-md-6">
                        <figure>
                            <img :src="data.section_4_image" alt="" class="img-fluid w-100">
                        </figure>
                    </div>
                    <div class="col-md-6">
                        <div class="secHeading">
                            <h2>{{ data.section_4_title }}</h2>
                            <p>{{data.section_4_description_line_1}}</p>
                            <p>{{data.section_4_description_line_2}}</p>
                            <p><strong>{{data.section_4_title_2}}</strong></p>
                            <img :src="data.section_4_image_2" alt="">
                            <p><strong>{{data.section_4_title_3}}</strong></p>
                        </div>
                        <ul>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>{{data.section_4_description_2_line_1}}</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>{{data.section_4_description_2_line_2}}</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>{{data.section_4_description_2_line_3}}</span>
                            </li>
                        </ul>
                        <!--                        <a href="" class="themeBtn" v-if="!user">JOIN US</a>-->
                    </div>
                </div>
            </div>
        </section>
        <!-- Begin: Footer -->
        <footer>
            <div class="container-md">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 wow fadeInLeft" data-wow-delay="1.2s">
                        <a href="#" class="d-block text-center"><img :src="asset('images/logo.png')" alt="logo"></a>
                        <ul class="links">
                            <li><a href="#">How It Works</a></li>
                            <li><a href="#">Membership Benefits</a></li>
                            <li><a href="#">Terms & Conditions</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Contact Us</a></li>
                        </ul>
                        <ul class="list-unstyled footerSocial">
                            <li><a href="https://www.facebook.com/Tha-Network-150057600527324/" target="_blank"><i
                                class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://twitter.com/ThaNetwork4" target="_blank"><i class="fab fa-twitter"></i></a>
                            </li>
                            <li><a href="https://www.youtube.com/channel/UCBf0MeQqY_T1Oqtw2qOK7Fg" target="_blank"><i
                                class="fab fa-youtube"></i></a></li>
                            <li><a href="https://www.tiktok.com/@_thanetwork_?lang=en" target="_blank"><img
                                :src="asset('images/simple-tiktok.png')" alt=""></a></li>
                            <li><a href="https://www.instagram.com/_thanetwork_/" target="_blank"><i
                                class="fab fa-instagram"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="row copyRight">
                    <div class="col-lg-12 col-md-12 wow fadeInLeft" data-wow-delay="0.5s">
                        <p>Copyright © 2022 ThaNetwork</p>
                    </div>
                </div>
            </div>
        </footer>
        <!-- END: Footer -->
    </div>
</template>

<script>
import utils from "../mixins/utils";
import HowItWorksMenu from "../components/HowItWorksMenu";
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "Benifits",
    mixins: [utils],
    components: {
        HowItWorksMenu
    },
    props: {
        data: Object
    },
    computed: {
        user() {
            return usePage().props.value?.auth ?? null
        }
    },
    mounted() {
        // alert(this.$route('about'));
    }
}
</script>

<style scoped>

</style>
