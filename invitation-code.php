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
                    <div class="contentWrap invitationInner">
                        <a href="#"><img src="images/logo.png" alt="logo"></a>
                        <h2>Get Invitation Code</h2>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="one-pane" role="tabpanel" aria-labelledby="one-tab">
                                <form action="index.php#two-pane">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="password" name="pass" placeholder="" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="text">Phone Number</label>
                                        <input type="text" name="pass" placeholder="" class="form-control">
                                    </div>
                                    <div class="form-group form-check mb-3">
                                        <h3>Where do you want to receive the code</h3>
                                        <input type="radio" class="form-check-input1" id="email" name="codeRadio">
                                        <label class="form-check-label1" for="email">Email</label>
                                        <input type="radio" class="form-check-input2" id="text" name="codeRadio">
                                        <label class="form-check-label2" for="text">Text</label>
                                    </div>
                                    <button type="submit" class="themeBtn">GET CODE</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'include/footer.php' ?>