<template>
    <div class="cardWrap">
        <h2>My Friends</h2>
        <div class="storySlider" ref="storyWrapper">
            <div class="storyWrap" v-for="n in 10" :key="n">
                <a href="#" class="imgBox"><img :src="asset('images/story1.jpg')" alt=""></a>
                <h3>armanrokni</h3>
            </div>
        </div>
    </div>
</template>

<script>
import utils from "../../mixins/utils";

export default {
    name: "FriendStorySlider",
    mixins: [utils],
    mounted() {
        this.$nextTick(() => {
            $(this.$refs.storyWrapper).slick({
                autoplay: true,
                autoplaySpeed: 5000,
                dots: false,
                arrows: true,
                infinite: true,
                slidesToShow: 7,
                slidesToScroll: 1,
                prevArrow: '<button type="button" class="slick-prev"><i class="fal fa-chevron-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fal fa-chevron-right"></i></button>',
                responsive: [
                    {
                        breakpoint: 1199,
                        settings: {
                            slidesToShow: 3,
                        },
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            arrows: false,
                        },
                    },
                    {
                        breakpoint: 575,
                        settings: {
                            slidesToShow: 1,
                            arrows: false,
                        },
                    },
                ],
            });
        })
    }
}
</script>

<style scoped>

</style>
