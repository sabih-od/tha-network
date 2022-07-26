<template>
    <div class="cardWrap">
        <div class="df aic jcsb mb-3">
            <h2 class="m-0">Messages</h2>
            <a href="#" class="editBtn"><i class="fal fa-edit"></i></a>
        </div>
        <form action="">
            <div class="searchlist">
                <i class="fal fa-search"></i>
                <input type="search" placeholder="Search messages" name="search">
                <button><i class="fal fa-sliders-h"></i></button>
            </div>
        </form>
        <div class="msgList">
            <ul class="nav msg-tabs" id="myTab" role="tablist">
                <li>
                    <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one-pane" role="tab"
                       aria-controls="one-pane" aria-selected="true">New</a>
                </li>
                <li>
                    <a class="nav-link" id="two-tab" data-toggle="tab" href="#two-pane" role="tab"
                       aria-controls="two-pane" aria-selected="false">Read</a>
                </li>
                <li>
                    <a class="nav-link" id="three-tab" data-toggle="tab" href="#three-pane" role="tab"
                       aria-controls="three-pane" aria-selected="false">Friend Requests (2)</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="one-pane" role="tabpanel" aria-labelledby="one-tab">
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user1.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user2.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user3.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user4.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user5.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user6.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user7.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user7.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="two-pane" role="tabpanel" aria-labelledby="two-tab">
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user1.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user2.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user3.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user4.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user5.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user6.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user7.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user7.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="three-pane" role="tabpanel" aria-labelledby="three-tab">
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user1.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user2.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user3.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user4.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user5.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user6.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user7.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                    <div class="userList">
                        <div class="userInfo">
                            <a href="profile.php"><img :src="asset('images/user7.jpg')" class="rounded-circle"
                                                       alt=""></a>
                            <h3>Arman Rokni <a href="#">Active 30m ago</a></h3>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import utils from "../../mixins/utils";

export default {
    name: "Messages",
    mixins: [utils]
}
</script>

<style scoped>

</style>
