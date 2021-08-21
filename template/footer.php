<?php if($case != 'checkout') { ?>
    <section class="full-width mt-5 order-stats-wrapper">
        <h1 class="common-heading text-uppercase text-center no-shadow color-grey-dark2">Order Placed</h1>

        <div class="container custom-container mt-4">
            <div class="row">
                <div class="col">
                    <div class="order-stats text-center">
                        <div class="count">1819</div>
                        <div class="order-stats-title text-uppercase">Completed Orders</div>
                    </div>
                </div>

                <div class="col">
                    <div class="order-stats text-center">
                        <div class="count">45</div>
                        <div class="order-stats-title text-uppercase">States</div>
                    </div>
                </div>

                <div class="col">
                    <div class="order-stats text-center">
                        <div class="count">15000+</div>
                        <div class="order-stats-title text-uppercase">Vendors</div>
                    </div>
                </div>

                <div class="col">
                    <div class="order-stats text-center">
                        <div class="count">200+</div>
                        <div class="order-stats-title text-uppercase">On Going Orders</div>
                    </div>
                </div>

                <div class="col">
                    <div class="order-stats text-center">
                        <div class="count">50+</div>
                        <div class="order-stats-title text-uppercase">Corporate Brands</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="full-width mt-5">
        <div class="client-wrapper">
            <div class="brands-section-title text-center text-uppercase">
                Brands who trust us
            </div>

            <div class="container custom-container mt-3">
                <div id="clientCarosuel" class="owl-carousel owl-theme clientCarosuel">
                    <?php 
                        if($cdData->results) { 
                            foreach ($cdData->results as $key => $value) {
                    ?>
                        <div><img src="<?=DOMAIN?><?=$value->ClientLOGO?>" alt="<?=$value->ClientName?>" loading="lazy" /></div>
                    <?php }} ?>
                </div>
            </div>
        </div>
    </section>
    
    <section class="mt-5 mb-5">
        <div class="container custom-container container-md">
            <?php if($keyData->results) { ?>
                <div class="key-feature">
                    <div class="row">
                        <?php foreach ($keyData->results as $key => $value) { ?> 
                            <div class="col-6 col-lg-3">
                                <img src="<?=DOMAIN?><?=$value->KeyFeaturesImage?>" alt="<?=$value->KeyFeaturesName?>" />
                                <span><?=$value->KeyFeaturesDescription?></span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if($adData->results->AlertDescription) { ?>
                <div class="key-line">
                    <?=$adData->results->AlertDescription?>
                </div>
            <?php } ?>

            <div class="contact-number-section">
                <div class="row">
                    <?php 
                        if($gnData->results) { 
                            foreach ($gnData->results as $key => $value) {
                    ?>
                        <div class="col-12 col-lg-4">
                        <?=$value->CountryCode?> - <a href="tel:+<?=$value->PhoneCode?> <?=$value->Number?>">
                                +<?=$value->PhoneCode?> <?=$value->Number?>
                            </a>
                        </div>
                    <?php }} ?>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container custom-container">
            <div class="footer-links">
                <div class="row">
                    <?php 
                        if($fdData->results) { 
                            foreach ($fdData->results as $key => $value) {
                    ?>
                        <div class="col-6 col-lg-3 d-none d-lg-block">
                            <div class="footer-nav-title"><?=$value->FooterHeader?></div>
                            <ul class="footer-nav">
                            <?php foreach ($value->SubLinks as $key => $val) { ?>
                                <li><a href="<?=DOMAIN?><?=$val->ItemURL?>"><?=$val->ItemName?></a></li>
                            <?php } ?>
                            </ul>
                        </div>
                    <?php }} ?>

                    <div class="col-12 col-lg-3">
                        <div class="signup-box">
                            <form>
                                <label>Get update on offers and discount coupons</label>
                                <div class="signup-box-container">
                                    <input type="email" id="txtSubscribeEmail" placeholder="Enter your email address" />
                                    <button type="button" class="btn-subscribe">Subscribe</button>
                                </div>
                            </form>
                        </div>


                        <ul class="social-links">
                            <li><a href="https://www.facebook.com/FloralIndiaOfficial" target="_blank"><img src="<?=IMG?>common/fb.png" /></a></li>
                            <li><a href="https://instagram.com/floralindiaofficial?igshid=dicsp7ik9gzx" target="_blank"><img src="<?=IMG?>common/insta.png" /></a></li>
                            <!-- <li><a href="#" target="_blank"><img src="<?=IMG?>common/linkedin.png" /></a></li> -->
                            <li><a href="https://pin.it/2IuVwoL" target="_blank"><img src="<?=IMG?>common/pintrest.png" /></a></li>
                        </ul>

                        <div class="footer-nav-title mt-4">Connect with us</div>
                        <ul class="footer-nav mt-0">
                        <?php if(isset($siteData->results->MobileNo)) { ?>
                            <li><a href="tel:<?=$siteData->results->MobileNo?>">M : <?=$siteData->results->MobileNo?></a></li>
                        <?php } ?>
                            <li><a href="mailto:">E : <?=$gdData->results->Email?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php } ?>

    <div class="footer-end">
        <div class="container custom-container">
            <div class="row">
                <div class="col-12 col-lg-7">
                    Copyright &copy; floralindia.com <?= date("Y") ?>. All rights reserved
                </div>

                <div class="col-12 col-lg-5 text-right">
                    <img src="<?=IMG?>common/payment-method.png" class="img-100" alt="payment options" />
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Login/signup overlay -->
<div class="modal fade" id="loginModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content login-reg-modal">
            <div class="modal-body zero">
                <div class="row frm-login">
                    <div class="col-12 col-lg-8">
                        <div class="login-form common-form">
                            <h1 class="form-heading">Login</h1>
                            <form action="" class="form formValidation" name="loginForm" id="loginForm" method="post" autocomplete="off" data-attr="NormalLogin">
                                <div class="c-form-control">
                                    <label class="lbl-field" for="loginEmail">Email</label>
                                    <input type="text" id="loginEmail" name="Email" class="custom-txt txtUsername" />
                                </div>

                                <div class="c-form-control">
                                    <label class="lbl-field" for="loginPassword">Password</label>
                                    <input type="password" id="loginPassword" name="Password" class="custom-txt txtPassword" />
                                </div>

                                <div class="c-form-control">
                                    <a class="link-primary" role="button">Forgot Password?</a>
                                </div>

                                <div class="invalid-login-msg alert alert-danger d-none">
                                    Oops. We have not found any account with this data. Please try again.
                                </div>

                                <div class="mt-4">
                                    <button class="c-btn c-btn-smooth c-btn-semi-compact c-btn-block c-btn-secondary text-uppercase btn-SignIn">Sign In</button>
                                </div>

                                <div class="c-form-control">
                                    <div class="or">
                                        <span>OR</span>
                                    </div>
                                </div>

                                <div class="connect-social">
                                    Sign in With
                                </div>

                                <div class="mt-4">
                                    <div class="social-connect-button">
                                        <button class="c-btn-social fb-login fbLoginButton">
                                            <img src="<?=IMG?>common/fb-f.png" alt="Facebook Login" />
                                        </button>
                                        <button data-onsuccess="onSuccess" type="button" class="g-signin2 c-btn-social google-login" >
                                            <img src="<?=IMG?>common/google-g.png" alt="Google Login" />
                                        </button>
                                        <button id="LoginWithAmazon" type="button" class="c-btn-social amazon-login amz-login">
                                            <img src="<?=IMG?>common/amazon-logo.png" alt="Amazon Login" />
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="reg-form common-form d-none">
                            <h1 class="form-heading">Sign up</h1>

                            <form action="" class="form formValidation" name="regForm" id="regForm" autocomplete="off" method="post">
                                <div class="c-form-control">
                                    <label class="lbl-field" for="FirstName">First Name<span class="mandate">*</span></label>
                                    <input type="text" id="FirstName" name="FirstName" class="custom-txt" />
                                </div>

                                <div class="c-form-control">
                                    <label class="lbl-field" for="LastName">Last Name<span class="mandate">*</span></label>
                                    <input type="text" id="LastName" name="LastName" class="custom-txt" />
                                </div>

                                <div class="c-form-control">
                                    <label class="lbl-field" for="Email">Email<span class="mandate">*</span></label>
                                    <input type="text" id="Email" name="Email" class="custom-txt" />
                                </div>

                                <div class="c-form-control">
                                    <label class="lbl-field" for="Password">Password<span class="mandate">*</span></label>
                                    <input type="password" id="Password" name="Password" class="custom-txt" />
                                </div>

                                <div class="invalid-signup-msg alert alert-danger d-none">
                                    Oops! Seems like user with this account already exist. <a role="button" class="btn-show-signup link">Click here</a> to login
                                </div>

                                <div class="signup-valid-msg alert alert-success d-none">
                                    You have successfully registered. <a role="button" class="btn-show-signup link">Click here</a> to login to your account.
                                </div>

                                <div class="mt-4">
                                    <button class="c-btn c-btn-smooth c-btn-semi-compact c-btn-block c-btn-secondary text-uppercase btn-register">Sign Up</button>
                                </div>

                                <div class="c-form-control">
                                    <div class="or">
                                        <span>OR</span>
                                    </div>
                                </div>

                                <div class="connect-social">
                                    Connect With
                                </div>

                                <div class="mt-4">
                                    <div class="social-connect-button">
                                        <button class="c-btn-social fb-login fbLoginButton">
                                            <img src="<?=IMG?>common/fb-f.png" />
                                        </button>
                                        <button id="g-signin2" data-onsuccess="onSuccess" class="c-btn-social g-signin2">
                                            <img src="<?=IMG?>common/google-g.png" alt="google-img" />
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4 form-template">
                        <div class="common-form">
                            <div class="login-form">
                                <h1 class="form-heading">New here?</h1>
                                <p>Signup and discover our exciting offers and services</p>
                            </div>

                            <div class="reg-form d-none">
                                <h1 class="form-heading">Already have account?</h1>
                                <p>Login now to ease in your website experience and explore new offers</p>
                            </div>
                            <button class="btn-show-signup">Sign Up</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login/signup overlay -->

<!--Mobile login/signup-->
<div class="custom-modal mob-login-modal d-none">
    <div class="floral-logo">
        <img src="<?=DOMAIN?><?=$siteData->results->Logo?>" alt="" />
    </div>

    <div class="login-form login-reg">
        <h1 class="custom-modal-title">Log in</h1>

        <div class="custom-form">
            <div class="custom-field custom-field-alert d-none">
                <strong>Please try again...</strong>
                <div>
                    There isn’t an account associated with this email address. Please try another email.
                </div>
            </div>

            <form autocomplete="off" name="mobLoginForm" id="mobLoginForm" class="form formValidation">
                <div class="input-wrapper">
                    <div class="custom-field first-child">
                        <input type="text" id="MobileLoginEmail" name="Email" class="custom-form-control txtUsername" placeholder="Username or email" />
                    </div>

                    <div class="custom-field last-child">
                        <input type="password" id="MobileLoginPassword" name="Password" class="custom-form-control txtPassword" placeholder="Password" />
                    </div>
                </div>
                <button type="submit" class="custom-button mt-3 btn-SignIn">Continue</button>
            </form>

            <div class="mt-3">
                <a class="link link-primary d-inline-block" href="#">Forgotten password?</a>
                <div class="reverse-action">
                    Don't have an account? <a href="#" class="link link-primary d-inline-block login-switcher">Sign up</a>
                </div>
            </div>

            <div class="hr-saperator mt-3">
                <div class="saperator-text">or</div>
            </div>

            <div class="extras mt-3">
                <div class="row">
                    <!-- <div class="col-6">
                        <div class="custom-secondary-button" type="button">
                            <button class="c-btn-social fb-login fbLoginButton">
                                <img src="<?=DOMAIN?>Content/assets/images/common/fb-f.png" alt="google-img" />
                            </button>
                            <i class="fab fa-facebook fb"></i>
                            <div class="text-center d-inline-block">Facebook</div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="custom-secondary-button" type="button">
                            <i class="fab fa-google google"></i>
                            <div class="text-center d-inline-block">Google</div>
                            <button type="button" id="g-signin2" data-onsuccess="onSuccess" class="g-signin2 c-btn-social google-login">
                                <img src="<?=DOMAIN?>Content/assets/images/common/google-g.png" alt="google-img" />
                            </button>
                        </div>
                    </div> -->

                    <div class="connect-social text-center w-100 mb-3">
                        Sign in With
                    </div>

                    <div class="social-connect-button">
                        <button class="c-btn-social fb-login fbLoginButton">
                            <img src="<?=IMG?>common/fb-f.png" alt="Facebook Login" />
                        </button>
                        <button data-onsuccess="onSuccess" type="button" class="g-signin2 c-btn-social google-login" >
                            <img src="<?=IMG?>common/google-g.png" alt="Google Login" />
                        </button>
                        <button id="LoginWithAmazon" type="button" class="c-btn-social amazon-login amz-login">
                            <img src="<?=IMG?>common/amazon-logo.png" alt="Amazon Login" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="reg-form login-reg d-none">
        <h1 class="custom-modal-title">Sign up</h1>
        <div class="custom-form">
            <form autocomplete="off" name="mobRegForm" id="mobRegForm" class="form formValidation">
                <div class="input-wrapper">
                    <div class="custom-field first-child">
                        <input type="text" id="MobileFirstName" name="FirstName" class="custom-form-control txtFirstname" placeholder="First Name" />
                    </div>

                    <div class="custom-field last-child">
                        <input type="text" id="MobileLastName" name="LastName" class="custom-form-control txtLastname" placeholder="Last Name" />
                    </div>
                </div>

                <div class="input-wrapper mt-4">
                    <div class="custom-field">
                        <input type="email" id="MobileEmail" name="Email" class="custom-form-control txtEmail" placeholder="Email" />
                    </div>
                </div>

                <div class="input-wrapper mt-4">
                    <div class="custom-field">
                        <input type="password" id="MobilePassword" name="Password" class="custom-form-control txtPassword" placeholder="Password" />
                    </div>
                </div>
                <p>By selecting <strong>Agree and continue</strong> below, I agree to Floral India's <a href="#" target="_blank">Terms of services</a></p>
                <button type="submit" class="custom-button mt-3 btn-register">Agree and continue</button>
            </form>

            <div class="mt-3">
                <div class="reverse-action">
                    Already have an account? <a href="#" class="link link-primary d-inline-block login-switcher">Sign in</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Mobile login/signup-->

<?php if($case != 'checkout') { ?>
    <!--Delivery time slots-->
    <div class="modal fade" id="timeSlotModal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center w100">Select Time Slot</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body text-center">
                    <h6 class="text-center mb-3 text-uppercase"><span class="delivery-title"></span> - <span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <span class="delivery-cost"></span></h6>
                    <ul class="time-slot-nav">
                    </ul>

                    <div class="alert alert-danger no-slots d-none">
                        Sorry! No time slot available for today. Please select <strong>Fixed time delivery</strong> or <strong>Midnight delivery</strong> to check avaialble delivery time slots for today.
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!--Gift message modal-->
<div class="modal fade" id="giftMessage">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w100 text-uppercase">Send Gift Message Along</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="bold">To</label>
                            <input type="text" class="form-control" id="giftRecieverName" placeholder="Enter recipient name" />
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label class="bold">From <small>(Leave blank if you want to stay anonymous)</small></label>
                            <input type="text" class="form-control" id="giftSenderName" placeholder="Enter your name" />
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="bold">Your message <small class="text-danger">(Upto 150 characters)</small></label>
                            <textarea id="giftSendingMessage" rows="5" class="form-control" maxlength="150" placeholder="Enter your message here"></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="button" class="btn-message c-btn c-btn-primary c-btn-semi-smooth"><span>Add gift message</span> &nbsp;<i class="fa fa-angle-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--Addon modal-->
<div class="modal fade addon-modal" id="addon">
    <div class="addon-wrapper">
        <h1 class="super-title addon-overlay-title text-center primary-dark">Addon somothing to make it extra special</h1>
        <?php if(!isset($addonGroupOne->results->error)) { ?>
            <div class="addon-item-wrapper">
                <h2 class="owl-title">Flowers, Plants, Party Accessories, Balloons</h2>
                <div class="owl-carousel owl-theme addonCarosuel">
                    <?php
                        foreach ($addonGroupOne->results as $key => $val) {
                    ?>
                    <div class="addon-item">

                        <input type="hidden" value="<?=$val->ProductCategoryID?>" class="d-none" id="addonProductCategoryID" />
                        <input type="hidden" value="<?=$val->Filters[0]->FilterPrice?>" class="d-none" id="addonProductSizePrice" />
                        <input type="hidden" value="<?=$val->ProductID?>" class="d-none" id="addonProductID" />
                        <input type="hidden" value="<?=$val->Filters[0]->FilterValue?>" class="d-none" id="addonProductSize" />
                        <input type="hidden" value="<?=$val->ProductName?>" class="d-none" id="addonProductName" />
                        <input type="hidden" value="<?=$val->Mrp?>" class="d-none" id="addonProductMrp" />
                        <input type="hidden" value="<?=$val->Price?>" class="d-none" id="addonProductPrice" />

                        <a class="hover-overlay">
                            <img src="<?=DOMAIN?><?=$val->ProductImage?>" class="product-img" />
                            <div class="addon-info d-block">
                                <div>
                                    <h3 class="item-name"><?=$val->ProductName?></h3>
                                    <button title="Add to cart" class="button-addon">
                                        <img src="<?=DOMAIN?>Content/assets/images/common/plus-24.png" />
                                    </button>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="prices"><span class="cut-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span><span class="setCurrBasedPrice"><?=$val->Mrp?></span></span> <span class="actual-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span><span class="setCurrBasedPrice"><?=$val->Price?></span><span></span></span></div>
                                <p><?=$val->ProductShortDescription?></p>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php if(!isset($addonGroupTwo->results->error)) { ?>
            <div class="addon-item-wrapper">
                <h2 class="owl-title">Cake, Chocolates, Hampers, Indian Sweets</h2>
                <div class="owl-carousel owl-theme addonCarosuel">
                    <?php
                        foreach ($addonGroupTwo->results as $key => $val) {
                    ?>
                    <div class="addon-item">

                        <input type="hidden" value="<?=$val->ProductCategoryID?>" class="d-none" id="addonProductCategoryID" />
                        <input type="hidden" value="<?=$val->Filters[0]->FilterPrice?>" class="d-none" id="addonProductSizePrice" />
                        <input type="hidden" value="<?=$val->ProductID?>" class="d-none" id="addonProductID" />
                        <input type="hidden" value="<?=$val->Filters[0]->FilterValue?>" class="d-none" id="addonProductSize" />
                        <input type="hidden" value="<?=$val->ProductName?>" class="d-none" id="addonProductName" />
                        <input type="hidden" value="<?=$val->Mrp?>" class="d-none" id="addonProductMrp" />
                        <input type="hidden" value="<?=$val->Price?>" class="d-none" id="addonProductPrice" />

                        <a class="hover-overlay">
                            <img src="<?=DOMAIN?><?=$val->ProductImage?>" class="product-img" />
                            <div class="addon-info d-block">
                                <div>
                                    <h3 class="item-name"><?=$val->ProductName?></h3>
                                    <button title="Add to cart" class="button-addon">
                                        <img src="<?=DOMAIN?>Content/assets/images/common/plus-24.png" />
                                    </button>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="prices"><span class="cut-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span><span class="setCurrBasedPrice"><?=$val->Mrp?></span></span> <span class="actual-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span><span class="setCurrBasedPrice"><?=$val->Price?></span><span></span></span></div>
                                <p><?=$val->ProductShortDescription?></p>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php if(!isset($addonGroupThree->results->error)) { ?>
            <div class="addon-item-wrapper">
                <h2 class="owl-title">Perfumes, Artifical Jewelery, Soft Toys, Greeting Card</h2>
                <div class="owl-carousel owl-theme addonCarosuel">
                    <?php
                    foreach ($addonGroupThree->results as $key => $val) {
                    ?>
                    <div class="addon-item">

                        <input type="hidden" value="<?=$val->ProductCategoryID?>" class="d-none" id="addonProductCategoryID" />
                        <input type="hidden" value="<?=$val->Filters[0]->FilterPrice?>" class="d-none" id="addonProductSizePrice" />
                        <input type="hidden" value="<?=$val->ProductID?>" class="d-none" id="addonProductID" />
                        <input type="hidden" value="<?=$val->Filters[0]->FilterValue?>" class="d-none" id="addonProductSize" />
                        <input type="hidden" value="<?=$val->ProductName?>" class="d-none" id="addonProductName" />
                        <input type="hidden" value="<?=$val->Mrp?>" class="d-none" id="addonProductMrp" />
                        <input type="hidden" value="<?=$val->Price?>" class="d-none" id="addonProductPrice" />

                        <a class="hover-overlay">
                            <img src="<?=DOMAIN?><?=$val->ProductImage?>" class="product-img" />
                            <div class="addon-info d-block d-xl-none">
                                <div>
                                    <h3 class="item-name"><?=$val->ProductName?></h3>
                                    <button title="Add to cart" class="button-addon">
                                        <img src="<?=DOMAIN?>Content/assets/images/common/plus-24.png" />
                                    </button>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="prices"><span class="cut-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span><span class="setCurrBasedPrice"><?=$val->Mrp?></span></span> <span class="actual-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span><span class="setCurrBasedPrice"><?=$val->Price?></span><span></span></span></div>
                                <p><?=$val->ProductShortDescription?></p>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <div class="product-action-buttons text-center">
            <a href="<?=rtrim(DOMAIN, '/');?>" class="c-btn c-btn-inline-block c-btn-semi-compact c-btn-secondary">Continue Shopping</a>
            <a href="<?=DOMAIN?>checkout" class="c-btn c-btn-inline-block c-btn-semi-compact c-btn-primary">Place Order</a>
        </div>
    </div>
</div>






<div class="overlay-bar"></div>
<!--sorting drawer-->
<div class="drawer active">
    <div class="drawer-inner">
        <ul>
            <li class="heading-li">Sort By</li>
            <li data-value="priceASC" class="btn-li-sort">PRICE : LOW TO HIGH</li>
            <li data-value="priceDESC" class="btn-li-sort">PRICE : HIGH TO LOW</li>
            <li data-value="bestSeller" class="btn-li-sort">BEST SELLERS</li>
        </ul>

        <button type="button" class="c-btn c-btn-block c-btn-semi-smooth c-btn-light close-drawer">
            Close
        </button>
    </div>
</div>
<?php require_once(TEMPLATE.'whatsapp-chat.php'); ?>
    <script src="<?=MINIFIED?>js/lib-bundle.js?v=<?=VERSION?>"></script>
    <script src="<?=MINIFIED?>js/modules-bundle.js?v=<?=VERSION?>"></script>
</body>
</html>