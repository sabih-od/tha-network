<template>
    <div>
        <HowItWorksMenu/>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="secHeading">
                            <h2>About Us</h2>
                            <h4>What makes Tha Network different from other Social Media Sites</h4>
                        </div>
                        <ul>
                            <li>
                                Tha Network is a social media site created to help people help each other while having
                                fun and building a large network of friends.
                            </li>
                            <li>
                                Our members earn cash just by making referrals to other people and getting them to join
                                the network.
                            </li>
                            <li>
                                Our members earn a referral fee for each person that joins and earns monthly payments
                                for the life of that persons membership.
                            </li>
                            <li>
                                If you invited 100 people to join your network and earned $10 per person, you would make
                                a $1,000 if all 100 people joined. If each person keeps their membership you will
                                continue to earn $1,000 per month for the life of their membership. Now let’s use real
                                numbers, multiple the number of (friends and followers) you have on all your social
                                media sites by $10 per person!! This is how much you could earn per month if all your
                                friends and followers joined your network.
                            </li>
                            <li>
                                Members can invite as many people as they like.
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-5">
                        <figure>
                            <img src="" alt="">
                            <div class="overlay">
                                <h2></h2>
                                <h6></h6>
                            </div>
                        </figure>
                    </div>
                </div>
            </div>
        </section>
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
                            <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
                            <li><a href="#" target="_blank"><i class="fab fa-youtube"></i></a></li>
                            <li><a href="#" target="_blank"><img :src="asset('images/simple-tiktok.png')" alt=""></a>
                            </li>
                            <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
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
    mounted() {
        // alert(this.$route('about'));
    }
}
</script>

<style scoped>

</style>
