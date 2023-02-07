<template>
    <div>
        <HowItWorksMenu/>
        <div class="main-slider">
            <img class="img-fluid w-100" :src="asset('images/banner.jpg')" alt="First slide">
            <div class="carousel-caption">
                <div class="container">
                    <div class="row aic justify-content-center">
                        <div class="col-md-6">
                            <div class="content">
                                <h3>Membership Benifits</h3>
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
                            <img class="img-fluid w-100" :src="asset('images/benefit1.png')" alt="">
                        </figure>
                    </div>
                    <div class="col-md-6">
                        <div class="secHeading">
                            <h2>Membership Benefits</h2>
                            <p>
                                Once you become a member, the Sky is the Limit to your earning potential. We have
                                developed this site to help it’s members earn extra cash without over working yourself
                                like a social media Influencer. Enjoy a full network of people to chat with, share
                                information with, send daily post, and to brag about the number of members in your
                                Network!!! Earn Financial Freedom!! Membership has never been this great!!!!
                            </p>
                            <p>
                                Have fun, keep the invites coming, build your network!!!
                            </p>
                            <p>
                                Become a member today!!!!
                            </p>
                        </div>
<!--                        <a href="#" class="themeBtn" v-if="!user">JOIN US</a>-->
                    </div>
                </div>
                <div class="row aic">
                    <div class="col-md-6">
                        <div class="secHeading">
                            <h2>Let Me Break it Down for you by doing the math!</h2>
                            <p>
                                If you invited 100 people to join your network (100 x $10 = $1,000) you could earn
                                $1,000 if each person joined the network. If each of the 100 people continue their
                                membership with Tha Network, you will continue to earn $1,000 per month for the life of
                                their membership. Now let’s use real numbers, multiple the number of (friends and
                                followers) you have on all your social media sites by $10 per person!! This is how much
                                you could earn per month if all your friends and followers joined your network. What are
                                you waiting for JOIN TODAY!!!
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <figure><img :src="asset('images/benefit2.jpg')" alt="" class="img-fluid w-100"></figure>
                    </div>
                </div>
                <div class="row aic">
                    <div class="col-md-6">
                        <figure>
                            <img :src="asset('images/benefit3.jpg')" alt="" class="img-fluid w-100">
                        </figure>
                    </div>
                    <div class="col-md-6">
                        <div class="secHeading">
                            <h2>Membership Pays</h2>
                            <p>
                                Join Today for $29.99 per month!!! This will be the best investment you have ever
                                made!!!!! You Should be Excited because WE ARE!!!
                            </p>
                            <p>
                                Join Now and lock in your membership payment because membership dues will be increasing
                                as the site’s popularity grows!!!! Be one of the first Members to help make Tha Network
                                an EPIC social media experience!
                            </p>
                            <p><strong>
                                HOW MANY PEOPLE ARE IN YOUR NETWORK!!!!
                            </strong></p>
                            <img :src="asset('images/character.png')" alt="">
                            <p><strong>
                                $$$ REMEMBER, MEMBERSHIP PAYS $$$
                            </strong></p>
                        </div>
                        <ul>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>
This site was created to allow its members to earn cash by making referrals to the people you know or meet, and you will enjoy the benefits that come with a social media experience.
                                </span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>
You will earn Monthly Income by just convincing your contacts to join your network.  Once they join all you need to do is watch the cash add up in your account!
                                </span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>
The site does all the work for you automatically by sending payments directly to your bank account when your referrals join Tha Network.
                                </span>
                            </li>
                        </ul>
<!--                        <a href="" class="themeBtn" v-if="!user">JOIN US</a>-->
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
import {usePage} from "@inertiajs/inertia-vue3";

export default {
    name: "About",
    mixins: [utils],
    components: {
        HowItWorksMenu
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
