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
        <section class="termsSec">
            <div class="container">
                <div class="row">
                    <div class="col-12" v-html="data.privacy_content">

                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import utils from "../mixins/utils";
import HowItWorksMenu from "../components/HowItWorksMenu";

export default {
    name: "Privacy",
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
