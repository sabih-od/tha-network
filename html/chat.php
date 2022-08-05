<?php include 'include/header.php' ?>

<body>
<?php include 'components/navbar.php' ?>

<div class="mainDashboard">
    <div class="slctlft descriptnbg">
        <div class="">
            <div class="row messangerSec">
                <div class="col-md-3 col-sm-3 border-right p-0">
                    <div class="chatSearch">
                        <div class="search-box">
                            <div class="input-wrapper">
                                <input type="text" placeholder="Search Contact">
                            </div>
                        </div>
                        <a href="#">
                            <div class="friend-drawer friend-drawer--onhover">

                                <img class="profile-image"
                                     src="./images/user1.jpg"
                                     alt="" width="35" height="35">
                                <div class="text">
                                    <h6>Test user</h6>
                                    <p>Online</p>
                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="friend-drawer friend-drawer--onhover">

                                <img class="profile-image"
                                     src="./images/user1.jpg"
                                     alt=""
                                     width="35" height="35">
                                <div class="text">
                                    <h6>andrewsmith</h6>

                                </div>
                            </div>
                        </a>
                        <a href="#">
                            <div class="friend-drawer friend-drawer--onhover">

                                <img class="profile-image"
                                     src="./images/user1.jpg"
                                     alt=""
                                     width="35" height="35">
                                <div class="text">
                                    <h6>andrewsmith</h6>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-md-9 col-sm-9 p-0 dse" id="chatbox">
                    <style>
                        .chatbox-media {
                            width: 250px;
                            height: 250px;
                        }
                    </style>
                    <div class="settings-tray">
                        <div class="row jhnmsngr">
                            <div class="col-sm-10">
                                <div class="chatHead">
                                    <h6>Test user</h6>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <span class="settings-tray--right"> </span>
                            </div>
                        </div>
                    </div>
                    <div class="chat-panel">
                        <div class="chatSec">
                            <div class="row">
                                <div class="tme-cht">
                                    <div class="chat-bubble chat-bubble--left">
                                        <img
                                            src="./images/user1.jpg"
                                            class="img-fluid user-image" alt="img">
                                        <div class="mesgHfs">
                                            <h5>Test user</h5>
                                            <div class="mesg-bx">
                                                <p>Hey</p>
                                            </div>
                                            <span>8 months ago</span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="tme-cht">
                                    <div class="chat-bubble chat-bubble--left">
                                        <img
                                            src="./images/user1.jpg"
                                            class="img-fluid user-image" alt="img">
                                        <div class="mesgHfs">
                                            <h5>Test user</h5>
                                            <div class="mesg-bx">
                                                <p>Hii</p>
                                            </div>
                                            <span>8 months ago</span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="row justify-content-end">
                                <div class="tme-cht bluebg">
                                    <div class="chat-bubble chat-bubble--left">

                                        <div class="mesgHfs">
                                            <h5>Jean Kruger</h5>
                                            <div class="mesg-bx">
                                                <img class="chatbox-media" src="./images/user1.jpg"/>
                                                <p>lorem ipsum</p>
                                            </div>
                                            <span>10 months ago</span>
                                        </div>
                                        <img src="./images/user1.jpg"
                                            class="img-fluid user-image" alt="img">
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-end">
                                <div class="tme-cht bluebg">
                                    <div class="chat-bubble chat-bubble--left">

                                        <div class="mesgHfs">
                                            <h5>Jean Kruger</h5>
                                            <div class="mesg-bx">
                                                <p>hi</p>
                                            </div>
                                            <span>10 months ago</span>
                                        </div>
                                        <img src="./images/user1.jpg"
                                            class="img-fluid user-image" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="insideSearch">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="searchBtn" type="button"><i class="fa fa-search"></i></button>
                                </div>
                                <input type="text" placeholder="Search here...">
                                <div class="input-group-prepend">
                                    <button class="closeSearchBtn" type="button"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="imagePreview">
                        <!-- <div class="input-images d-none"></div> -->
                        <!-- <div id="up_images">
                            <div class="d-flex flex-wrap cst_imgs">
                                <label for="imgUpload"></label>
                            </div>
                        </div>
                        <input type='file' id="imgUpload" name='Image' class='image-uploader' accept="image/jpg,image/png,image/jpeg" style='display: block !important;
                                                 width: 100% !important;
                                                 height: 100% !important;
                                                 opacity: 0 !important;
                                                 overflow: hidden !important;
                                                 z-index: -1;
                                                 margin-top: -20px;
                                                 top: -20px;
                                                 position: absolute;' /> -->
                    </div>
                    <div class="chat-box-tray">

                        <input type="text" placeholder="Type your message here..." class="demo6" id="message-text"
                               data-to="11">
                        <div class="papr-clp" id="post-image">
                            <i class="far fa-paperclip"></i>
                            <input type="file" name="file" id="msgfile" accept="image/*, video/*, audio/*">
                        </div>


                        <button class="btn btn-default text-white" id="send_message">
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include 'include/footer.php' ?>
