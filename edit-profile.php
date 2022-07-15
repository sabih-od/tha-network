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
                                <div class="filSet">
                                    <p>Create Avatar</p>
                                    <input type="file">
                                </div>
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

    <section class="bg-grey editProfile pt-0">
        <div class="container">
            <div class="row jcc">
                <div class="col-md-10">
                    <h2>Personal Information</h2>
                </div>
                <div class="col-md-10">
                    <div class="edit-card">
                        <a href="#" class="editProBtn">Edit</a>
                        <div class="cardWrap">
                            <div class="form-group mb-0">
                                <label for="bio">bio</label>
                                <textarea name="bio" id="bio" cols="10" class="form-control" placeholder="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua." rows="3"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="edit-card">
                        <a href="#" class="editProBtn">Edit</a>
                        <div class="cardWrap">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">First Name</label>
                                        <input type="text" name="fname" class="form-control" placeholder="John">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" name="lname" class="form-control" placeholder="Smit">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Johnsmit23@gmail.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="+123-456-789">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="edit-card">
                        <a href="#" class="editProBtn">Edit</a>
                        <div class="cardWrap">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" class="form-control" placeholder="Lorem ipsum 23578">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="text" name="country" class="form-control" placeholder="United State">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" name="city" class="form-control" placeholder="New York">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Postel/Zip Code</label>
                                        <input type="text" name="phone" class="form-control" placeholder="564289">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="edit-card">
                        <a href="#" class="editProBtn">Edit</a>
                        <div class="cardWrap">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="newpass">Create New Password</label>
                                        <input type="password" name="newpass" class="form-control" placeholder="*********">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="verifyPass">Verify Password</label>
                                        <input type="password" name="verifyPass" class="form-control" placeholder="*********">
                                    </div>
                                </div>
                            </div>
                            <p class="m-0">The password should be at least 8 characters long with (1 upper case letter, 1 number, 1 special character (!@#$%^&*)</p>
                        </div>
                    </div>

                    <div class="edit-card">
                        <div class="df aic jcsb mb-2">
                            <h2>Payment Method</h2>
                            <div class="df aic gap1">
                                <img src="images/payment2.png" alt="">
                                <a href="#" class="editProBtn">Edit</a>
                            </div>
                        </div>
                        <div class="cardWrap">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cardName">Name on Card</label>
                                        <input type="text" name="cardName" class="form-control" placeholder="John Smith">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cardNo">Card Number</label>
                                        <input type="text" name="cardNo" class="form-control" placeholder="123 456 7890 789 1234">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zipcode">Billing Zipcode</label>
                                        <input type="text" name="zipcode" class="form-control" placeholder="007788">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="expiry">Expiration date</label>
                                        <input type="text" name="expiry" class="form-control" placeholder="123 456 7890 789 1234">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="cvv">CVV Code</label>
                                        <input type="password" name="cvv" class="form-control" placeholder="****">
                                    </div>
                                </div>
                            </div>
                            <div class="df aic jcsb">
                                <a href="#" class="payBtn">Add another payment card</a>
                                <a href="#" class="payBtn" data-toggle="modal" data-target="#attentionModal">stop recurring payments </a>
                            </div>
                        </div>

                        <div class="btn-group gap1">
                            <button type="submit" class="themeBtn">Save</button>
                            <button class="themeBtn discard">Discard Changes</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <footer class="footer-feed">
        <p>Copyright © 2022 ThaNetwork</p>
    </footer>


    <?php include 'include/footer.php' ?>