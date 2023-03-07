<template>
    <div class="cardWrap">
        <h2>My Friends</h2>
        <div class="storySlider" ref="storyWrapper">
            <div class="storyWrap" v-if="peoples.length > 0" v-for="user in peoples" :key="user.id">
                <Link :href="$route('userProfile', user.id)" class="imgBox"><img :src="user.profile_img ? user.profile_img : asset('images/char-usr.png')" alt=""></Link>
                <h3>{{user.profile ? user.profile.first_name +' '+ user.profile.last_name : ''}}</h3>
            </div>
            <span class="text-center" v-else><strong>You have no friends.</strong></span>
        </div>
        <div class="text-center">
            <button v-if="peoples.length > 0" @click="seeFriendsList" class="themeBtn">See More</button>
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
                    // console.log('res?.friends?.data', res?.friends);
                    this.peoples = res?.friends?.filter(element => element.is_followed == true) ?? [];
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
        },
        seeFriendsList() {
            this.$store.commit('Misc/setMyFriendsFlag', true);
            this.$emitter.emit('my_friends_on');
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#ref_post_list_item0").offset().top - 400
            }, 1000);
        }
    }
}
</script>

<style scoped>

</style>
