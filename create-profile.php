<?php include 'include/header.php' ?>


<body>
    <section class="loginSection create-profile">
        <div class="loginWrap">
            <div class="row mx-0 no-gutters">
                <div class="col-md-7">
                    <figure>
                        <img src="images/loginImg.png" class="loginImg" alt="">
                        <img src="images/user-logo.png" class="login-logo" alt="">
                    </figure>
                </div>

                <div class="col-md-5">
                    <div class="contentWrap">
                        <a href="#"><img src="images/logo.png" alt="logo"></a>
                        <h2>Create Profile</h2>
                        <form action="tha-network.php">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">First Name</label>
                                        <input type="text" name="fname" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" name="lname" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-1">
                                        <label for="username">UserName</label>
                                        <input type="text" name="username" placeholder="" class="form-control">
                                    </div>
                                    <p class="color-danger">The username is already taken try to use a different one*</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <p class="color-black">The password should be at least 8 characters long with (1 upper case letter, 1 number, 1 special character (!@#$%^&*)</p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" name="password" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" name="cpassword" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-2">
                                        <label for="securityNo">Social Security Number</label>
                                        <input type="text" name="securityNo" placeholder="" class="form-control">
                                    </div>
                                    <p class="color-danger">All United State citizens/residents are required to enter their social security numbers for Tax purposes. Your information will never be shared or used for any other purposes. If a social is
                                        not provided for US citizens/residents payments will not be distributed until your social is provided."</p>
                                </div>
                            </div>
                            <button type="submit" class="themeBtn" onclick="window.location.href='tha-network.php'">NEXT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php include 'include/footer.php' ?>