<template>
    <div class="cardWrap">
        <div class="df aic jcsb mb-3">
            <h2 class="m-0">People In My Network</h2>
            <a href="#" class="viewBtn">See all</a>
        </div>
        <form action="">
            <div class="searchlist">
                <i class="fal fa-search"></i>
                <input type="search" placeholder="Search messages" name="search">
                <button><i class="fal fa-sliders-h"></i></button>
            </div>
        </form>
        <div class="userList">
            <div class="userInfo">
                <a href="profile.php"><img :src="asset('images/user1.jpg')" class="rounded-circle" alt=""></a>
                <h3>Arman Rokni <a href="#">Connect</a></h3>
            </div>
            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>
        </div>
        <div class="userList">
            <div class="userInfo">
                <a href="profile.php"><img :src="asset('images/user2.jpg')" class="rounded-circle" alt=""></a>
                <h3>Arman Rokni <a href="#">Connect</a></h3>
            </div>
            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>
        </div>
        <div class="userList">
            <div class="userInfo">
                <a href="profile.php"><img :src="asset('images/user3.jpg')" class="rounded-circle" alt=""></a>
                <h3>Arman Rokni <a href="#">Connect</a></h3>
            </div>
            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>
        </div>
        <div class="userList">
            <div class="userInfo">
                <a href="profile.php"><img :src="asset('images/user4.jpg')" class="rounded-circle" alt=""></a>
                <h3>Arman Rokni <a href="#">Connect</a></h3>
            </div>
            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>
        </div>
        <div class="userList">
            <div class="userInfo">
                <a href="profile.php"><img :src="asset('images/user5.jpg')" class="rounded-circle" alt=""></a>
                <h3>Arman Rokni <a href="#">Connect</a></h3>
            </div>
            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>
        </div>
        <div class="userList">
            <div class="userInfo">
                <a href="profile.php"><img :src="asset('images/user6.jpg')" class="rounded-circle" alt=""></a>
                <h3>Arman Rokni <a href="#">Connect</a></h3>
            </div>
            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>
        </div>
        <div class="userList">
            <div class="userInfo">
                <a href="profile.php"><img :src="asset('images/user7.jpg')" class="rounded-circle" alt=""></a>
                <h3>Arman Rokni <a href="#">Connect</a></h3>
            </div>
            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>
        </div>
        <div class="userList">
            <div class="userInfo">
                <a href="profile.php"><img :src="asset('images/user7.jpg')" class="rounded-circle" alt=""></a>
                <h3>Arman Rokni <a href="#">Connect</a></h3>
            </div>
            <a href="#" class="nav-icons"><i class="fal fa-comments"></i></a>
        </div>
    </div>
</template>

<script>
import utils from "../../mixins/utils";

export default {
    name: "PeopleList",
    mixins: [utils]
}
</script>

<style scoped>

</style>
