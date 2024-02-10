<template>
    <div>
        <HowItWorksMenu/>
        <section class="planSec">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2 class="secHeading">Frequently Asked Questions</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ol>
                            <li v-for="faq in data.faqs">
                                <b>{{faq.question}}</b>
                                <br>
                                {{faq.answer}}
                                <br>

                                <br />
                            </li>
                        </ol>
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
                            <li>
                                <Link :href="$route('work')">How It Works</Link>
                            </li>
                            <li>
                                <Link :href="$route('benefits')">Membership Benefits</Link>
                            </li>
                            <li>
                                <Link :href="$route('terms')">Terms & Conditions</Link>
                            </li>
                            <li>
                                <Link :href="$route('privacy')">Privacy Policy</Link>
                            </li>
                            <!--                            <li><Link :href="$route('contact')">Contact Us</Link></li>-->
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

export default {
    name: "Faqs",
    mixins: [utils],
    components: {
        HowItWorksMenu
    },
    props: {
        data: Object
    },
    mounted() {
        // alert(this.$route('about'));
    }
}
</script>

<style scoped>

</style>
