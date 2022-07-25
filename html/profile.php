<?php include 'include/header.php' ?>

<body>
    <?php include 'components/navbar.php' ?>

    <!-- Begin: Cover Section -->
    <section class="bg-grey cover-photo pt-0 pb-5">
        <img src="images/cover-photo.jpg" class="w-100" alt="">
        <div class="container-fluid">
            <div class="topWrap">
                <div class="row aic">
                    <div class="col-md-6">
                        <div class="userInfo">
                            <div class="profileImg">
                                <img src="images/char-usr.png" alt="">
                                <!-- <div class="filSet">
                                    <i class="fas fa-camera"></i><input type="file">
                                </div> -->
                            </div>
                            <h2>John Smith <span>@johnsmith22</span></h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <a href="#" class="awardWrap profilePage">
                            <img src="images/cup.png" alt="">
                        </a>
                        <div class="btn-group">
                            <a href="#" class="themeBtn">Message</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END: Cover Section -->

    <section class="bg-grey p-0">
        <div class="container-fluid">
            <div class="row">
                <!-- Left Section -->
                <div class="col-md-3">
                    <div class="cardWrap">
                        <h2>About</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam</p>
                        <a href="#" class="btnDesign">See more</a>
                    </div>
                    <div class="cardWrap">
                        <div class="df aic jcsb mb-3">
                            <h2 class="m-0">People In My Network</h2>
                            <a href="#" class="viewBtn">See all</a>
                        </div>
                        <?php include 'components/search-list.php' ?>
                        <?php include 'components/userlist.php' ?>
                    </div>

                    <div class="cardWrap">
                        <h2>Basic info Details</h2>
                        <ul class="infoList">
                            <li><i class="fas fa-home"></i> Lives in New York, USA.</li>
                            <li><i class="fas fa-heart"></i> Single</li>
                            <li><i class="fas fa-clock"></i> Joined April 2016</li>
                            <li><img src="images/followers.png" alt=""> Followed by 2,838 people</li>
                            <li>
                                <p class="ml-4">See More Details...</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Left Section -->

                <!-- Center Section -->
                <div class="col-md-6">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img src="images/ranking.png" alt="">
                                <h3>10 <sup>th</sup></h3>
                                <p>Rank</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img src="images/friends.png" alt="">
                                <h3>250</h3>
                                <p>Friends</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="profileAwards">
                                <img src="images/connections.png" alt="">
                                <h3>1000</h3>
                                <p>Connections</p>
                            </div>
                        </div>
                    </div>

                    <?php include 'components/feed-card.php' ?>
                    <?php include 'components/feed-card.php' ?>
                    <?php include 'components/feed-card.php' ?>

                </div>
                <!-- Center Section -->

                <!-- Right Section -->
                <div class="col-md-3">
                    <div class="cardWrap">
                        <h2>This Weeks New Members to Tha Network</h2>
                        <?php include 'components/search-list.php' ?>
                        <?php include 'components/memberlist.php' ?>
                    </div>
                </div>
                <!-- Right Section -->
            </div>
        </div>
    </section>

    <footer class="footer-feed">
        <p>Copyright © 2022 ThaNetwork</p>
    </footer>
    <?php include 'include/footer.php' ?>