<template>
    <div class="col-md-3">
        <div class="cardWrap">
            <h2>About Bio</h2>
            <p v-if="userProfile?.bio" style="max-height: 200px;overflow: auto;">{{ userProfile?.bio ?? "" }}</p>
            <Link v-if="!$store.state.Profile.is_another" replace :href="$route('editProfileForm')" class="themeBtn">EDIT ABOUT BIO</Link>
        </div>

        <div class="cardWrap">
            <h2>Personal Links</h2>
            <ul v-if="personalLinks.length > 0" class="personalLinks">
                <li v-for="(item, ind) in personalLinks" :key="ind">
                    <a :title="item" target="_blank" :href="item">{{ item }}</a>
                </li>
            </ul>
<!--            <a href="#" class="themeBtn">see more personal links</a>-->
        </div>

        <div class="cardWrap">
            <h2>Basic info Details</h2>
            <ul class="infoList">
                <li v-if="userProfile?.city"><i class="fas fa-home"></i> {{ countryStateText }}</li>

                <li v-if="userProfile?.marital_status"><i class="fas fa-heart"></i> {{
                        userProfile?.marital_status ?? ''
                    }}
                </li>

                <!--                <li><i class="fas fa-clock"></i> Joined April 2016</li>-->

                <!--                <li><i class="fas fa-user-friends"></i> Followed by 2,838 people</li>-->
            </ul>
            <Link v-if="!$store.state.Profile.is_another" replace :href="$route('editProfileForm')" class="themeBtn">Edit Info Details</Link>
        </div>
        <a href="#" class="themeBtn subsBtn">subscription store</a>
        <div class="cardWrap">
            <h2>Friends</h2>
            <ul class="friendList">
                <li><a href="#"><img :src="$store.getters['Utils/public_asset']('images/friend1.jpg')" alt=""></a></li>
                <li><a href="#"><img :src="$store.getters['Utils/public_asset']('images/friend2.png')" alt=""></a></li>
                <li><a href="#"><img :src="$store.getters['Utils/public_asset']('images/friend3.png')" alt=""></a></li>
                <li><a href="#"><img :src="$store.getters['Utils/public_asset']('images/friend4.png')" alt=""></a></li>
                <li><a href="#"><img :src="$store.getters['Utils/public_asset']('images/friend5.png')" alt=""></a></li>
            </ul>
            <a href="#" class="themeBtn">View All Friends</a>
        </div>
    </div>
</template>

<script>
import {useForm, usePage, Link} from "@inertiajs/inertia-vue3";

export default {
    name: "ProfileLeftSide",
    components: {Link},
    computed: {
        userProfile() {
            return this.$store.state.Profile?.data
        },
        countryStateText() {
            if (this.userProfile?.city && this.userProfile?.country_of_residence)
                return [this.userProfile?.city, this.userProfile?.country_of_residence].join(', ')
            return ''
        },
        personalLinks() {
            let data = []
            if (this.userProfile?.personal_links) {
                let chunks = this.userProfile.personal_links.split('\n')
                for (const chunk of chunks) {
                    if (chunk)
                        data.push(chunk)
                }
            }
            return data
        }
    }
}
</script>

<style scoped>
.personalLinks li a {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
