<?php include 'include/header.php' ?>


<body>

    <section class="loginSection">
        <div class="loginWrap">
            <div class="row mx-0 no-gutters">
                <div class="col-md-7">
                    <figure>
                        <img src="images/loginImg.png" class="w-100" alt="">
                        <img src="images/user-logo.png" class="login-logo" alt="">
                    </figure>
                </div>

                <div class="col-md-5">
                    <div class="contentWrap">
                        <a href="#"><img src="images/logo.png" alt="logo"></a>
                        <ul class="nav login-tabs" id="myTab" role="tablist">
                            <li>
                                <a class="nav-link active" id="one-tab" data-toggle="tab" href="#one-pane" role="tab" aria-controls="one-pane" aria-selected="true">Login</a>
                            </li>
                            <li>
                                <a class="nav-link" id="two-tab" data-toggle="tab" href="#two-pane" role="tab" aria-controls="two-pane" aria-selected="false">Invitation Code</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="one-pane" role="tabpanel" aria-labelledby="one-tab">
                                <form action="tha-network.php">
                                    <div class="form-group">
                                        <label for="email">Username or Email Address</label>
                                        <input type="email" name="email" placeholder="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="pass">Password</label>
                                        <a href="forgot-password.php">Forget Password?</a>
                                        <input type="password" name="pass" placeholder="" class="form-control">
                                    </div>
                                    <div class="form-group form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">Rememer Me</label>
                                    </div>
                                    <button type="submit" class="themeBtn" onclick="window.location.href='tha-network.php'">LOGIN</button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="two-pane" role="tabpanel" aria-labelledby="two-tab">
                                <form action="tha-network.php">
                                    <div class="form-group">
                                        <label for="code" id="code">Code</label>
                                        <input type="text" name="code" id="code" placeholder="" class="form-control">
                                    </div>
                                    <button type="submit" class="themeBtn" onclick="window.location.href='tha-network.php'">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'include/footer.php' ?>