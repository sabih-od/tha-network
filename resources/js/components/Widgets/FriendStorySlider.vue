<template>
    <div class="cardWrap">
        <h2>My Friends</h2>
        <div class="storySlider" ref="storyWrapper">
            <div class="storyWrap" v-for="user in peoples" :key="user.id">
                <Link :href="$route('userProfile', user.id)" class="imgBox"><img :src="asset('images/story1.jpg')" alt=""></Link>
                <h3>{{user.profile.first_name +' '+ user.profile.last_name}}</h3>
            </div>
        </div>
    </div>
</template>

<script>
import utils from "../../mixins/utils";
import {Link} from '@inertiajs/inertia-vue3'

export default {
    name: "FriendStorySlider",
    mixins: [utils],
    components: {
        Link
    },
    data() {
        return {
            search: '',
            peoples: [],
            debounce: null
        }
    },
    mounted() {
        this.initateSearch();
    },
    methods: {
        initateSearch() {
            clearTimeout(this.debounce);
            this.peoples = []
            this.debounce = setTimeout(() => {
                this.$store.dispatch('HttpUtils/getReq', {
                    url: this.$store.getters['Utils/baseUrl'],
                    only: ['friends'],
                    params: {
                        search: this.search
                    }
                }).then(res => {
                    this.peoples = res?.friends?.data.filter(element => element.is_followed == true) ?? [];
                    this.initSlick(this.peoples.length);
                }).finally(() => {
                    // this.loading = false
                })
            }, 600);
        },
        initSlick(slidesToShow) {
            this.$nextTick(() => {
                $(this.$refs.storyWrapper).slick({
                    autoplay: true,
                    autoplaySpeed: 5000,
                    dots: false,
                    arrows: true,
                    infinite: true,
                    slidesToShow: slidesToShow <= 1 ? 1 : slidesToShow - 1,
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
}
</script>

<style scoped>

</style>
