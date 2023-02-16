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
                                <h4>{{ data.banner_circle_title }}</h4>
                                <h3>{{ data.banner_circle_title_2 }}</h3>
                                <p>
                                    {{ data.banner_circle_text }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--        <section class="contactIner">-->
<!--            <div class="container">-->
<!--                <h2 class="secHeading text-center mb-5">Contact Us</h2>-->
<!--                <div class="row cont-brdr">-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="contnt">-->
<!--                            <img src="assets/images/call.png" alt="">-->
<!--                            <a href="tel:1234567890">+123-456-789-0</a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="contnt">-->
<!--                            <img src="assets/images/mail.png" alt="">-->
<!--                            <a href="mailto:Info@youremail.com">Info@youremail.com</a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-4">-->
<!--                        <div class="contnt">-->
<!--                            <img src="assets/images/call.png" alt="">-->
<!--                            <p>17192 Shieldsville Blvd. <br>-->
<!--                                Faribault, MN 55021</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="row align-items-center mt-5 pt-5">-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="getForm">-->
<!--                            <h6>Contact Us</h6>-->
<!--                            <h2>Get In Touch</h2>-->
<!--                            <form action="">-->
<!--                                <div class="row">-->
<!--                                    <div class="col-md-6">-->
<!--                                        <input type="text" placeholder="* Name">-->
<!--                                    </div>-->
<!--                                    <div class="col-md-6">-->
<!--                                        <input type="text" placeholder="* Last Name">-->
<!--                                    </div>-->
<!--                                    <div class="col-md-6">-->
<!--                                        <input type="text" placeholder="* Phone">-->
<!--                                    </div>-->
<!--                                    <div class="col-md-6">-->
<!--                                        <input type="text" placeholder="* Email">-->
<!--                                    </div>-->
<!--                                    <div class="col-md-12">-->
<!--                                        <textarea rows="10" placeholder="* Message Here"></textarea>-->
<!--                                        <button class="themeBtn">Submit now</button>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </form>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-6">-->
<!--                        <figure>-->
<!--                            <img src="assets/images/contBg.png" class="img-fluid" alt="img"></figure>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </section>-->
        <!-- Begin: Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 wow fadeInLeft" data-wow-delay="1.2s">
                        <a href="index.php" class="d-block text-center"><img :src="asset('images/logo.png')" alt="logo"></a>
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

export default {
    name: "About",
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
