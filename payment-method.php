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
                        <div class="df jcsb mt-3">
                            <h2 class="m-0">Payment Method</h2>
                            <img src="images/payment.png" alt="">
                        </div>
                        <form action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cardname">Name on Card</label>
                                        <input type="text" name="cardname" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cardNo">Card Number</label>
                                        <input type="text" name="cardNo" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="expireDate">Expiration Date</label>
                                        <input type="text" name="expireDate" placeholder="MM / YY" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cvv">CVV Code</label>
                                        <input type="text" name="cvv" placeholder="" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="zipCode">Billing Zipcode</label>
                                        <input type="text" name="zipCode" placeholder="" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="themeBtn" onclick="window.location.href='index.php'">CONFIRM PAYMENT</button>
                            <p class="color-grey mt-3">This payment information will be used for recurring payments every month. If you would like to cancel recurring payments go to your edit profile page to stop recurring payments.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'include/footer.php' ?>