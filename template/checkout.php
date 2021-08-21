<?php
    if(isset($data->results->error)) {
        header("Location:".DOMAIN);
    }

    //session_destroy();
   // print_r('<br/>');
    //print_r($_SESSION['uid']);

?>

<div class="container container-mob-no-pad child-page custom-container checkout-content">
    <div class="row">
    <input type="hidden" id="PID" value="<?=$_SESSION['uid']?>"/>

    <?php if(isset($data->results) && !isset($data->results->error)) {
        $bagTotal = 0;
        $bagTotalAfterDiscount = 0;
        $bagDiscount = 0;
        $shippingCharge = 0;
        $packingCharge = 0;
        $totalPayable = 0;
        $promoDiscount = (isset($_SESSION['PromoDiscount']) ? $_SESSION['PromoDiscount'] : 0);
    ?>
        <div class="col-12 col-xl-8 custom-grid">
            <ul id="progress">
                <li><a href="/cart">My Bag</a></li>
                <li class="active"><a href="#">Checkout</a></li>
                <li>Order Confirmation</li>
                <ul class="clearfix"></ul>
            </ul>

            <div class="prime-box">
                <div class="prime-box-inner">
                    <?php if(!isset($_SESSION['uid']) && !isset($_SESSION['GUEST_LOGIN'])) { ?>
                    <div>
                        <section class="box-title box-title-alt common-ele-padding">
                            <span class="spn-title spn-title-alt">
                                <span class="bold text-uppercase"><span class="step-number">1</span> My Account</span>
                            </span>
                        </section>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="common-ele-padding">
                                        <ul class="login-signup-tab">
                                            <li class="li-tab nav-login active" data-id="navLogin">Login</li>
                                            <li class="li-tab nav-signup" data-id="navSignUp">Sign Up</li>
                                            <li class="li-tab nav-guest" data-id="navGuest">Guest Checkout</li>
                                        </ul>

                                        <div class="li-content-wrapper">
                                            <div id="navLogin" class="li-div">
                                                <ul class="social-connect-button">
                                                    <li class="fb-li-login fbLoginButton"><i class="fab fa-facebook-f"></i></li>
                                                    <li class="g-li-login g-signin2" id="g-signin2" data-onsuccess="onSuccess"><i class="fab fa-google"></i></li>
                                                    <li class="c-btn-social amazon-login amz-login"><i class="fab fa-amazon"></i></li>
                                                </ul>

                                                <div class="or">
                                                    <span>OR</span>
                                                </div>

                                                <div>
                                                    <form action="" class="login-form formValidation" name="loginForm" id="loginForm" method="post" autocomplete="off" novalidate="novalidate" data-attr="Checkout">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input name="Email" id="loginEmail" class="form-control" placeholder="Email or login" type="email">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input name="Password" id="loginPassword" class="form-control" placeholder="******" type="password">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="row">
                                                                <div class="col-12 col-lg-6">
                                                                    <button type="submit" class="c-btn c-btn-secondary c-btn-inline-block c-btn-compact c-btn-semi-smooth text-uppercase btn-SignIn">Login</button>
                                                                </div>

                                                                <div class="col-12 col-lg-6 text-right">
                                                                    <a href="#" class="link link-primary forgot-link">Forgot password?</a>
                                                                </div>
                                                                <br/>
                                                                <div class="invalid-login-msg alert alert-danger d-none">
                                                                    Oops. We have not found any account with this data. Please try again.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div id="navSignUp" class="li-div d-none">
                                                <ul>
                                                    <li class="fb-li-login fbLoginButton"><i class="fab fa-facebook-f"></i></li>
                                                    <li class="g-li-login"><i class="fab fa-google"></i></li>
                                                </ul>

                                                <div class="or">
                                                    <span>OR</span>
                                                </div>

                                                <div>
                                                    <form class="reg-form formValidation" name="regForm" id="regForm" autocomplete="off" method="post" novalidate="novalidate">
                                                        <div class="row">
                                                            <div class="col-12 col-lg-6">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input name="FirstName" id="regFirstName" class="form-control" placeholder="First Name" type="text">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-lg-6">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <input name="LastName" id="regLastName" class="form-control" placeholder="Last Name" type="text">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input name="Email" id="regEmail" class="form-control" placeholder="Email" type="email">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input name="Password" id="regPassword" class="form-control" placeholder="******" type="password">
                                                            </div>
                                                        </div>
                                                        <br/>
                                                        <div class="invalid-signup-msg alert alert-danger d-none">
                                                            Oops! Seems like user with this account already exist. <a role="button" class="btn-show-signup link">Click here</a> to login
                                                        </div>

                                                        <div class="form-group">
                                                            <button type="submit" class="c-btn c-btn-secondary c-btn-inline-block c-btn-compact c-btn-semi-smooth text-uppercase btn-register">Sign Up</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div id="navGuest" class="li-div d-none guest-note">
                                                <form class="guest-form formValidation" name="guest" id="guestForm" autocomplete="off" novalidate="novalidate">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <input name="guestEmail" id="guestEmail" class="form-control" placeholder="Enter your email" type="email">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-12 col-lg-6">
                                                                <button type="submit" class="c-btn c-btn-secondary c-btn-inline-block c-btn-compact c-btn-semi-smooth text-uppercase">Continue as guest</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="common-ele-padding">
                                        <section class="login-note">Advantages of our secure login</section>

                                        <ul class="login-points">
                                            <li><i class="fas fa-truck"></i><span>Easily Track Orders, Hassle free Returns</span></li>
                                            <li><i class="fas fa-bell"></i><span>Get Relevant Alerts and Recommendation</span></li>
                                            <li><i class="fas fa-star"></i><span>Wishlist, Reviews, Ratings and more.</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                    <div>
                        <section class="box-title box-title-alt common-ele-padding">
                            <span class="spn-title spn-title-alt">
                                <span class="bold text-uppercase"><span class="step-number">1</span> My Account</span>
                            </span>
                        </section>

                        <div class="box-body">
                            <div class="cart-item-wrapper common-ele-padding">
                                <?php
                                    $logInName = (isset($_SESSION['uid']) && !isset($_SESSION['GUEST_LOGIN'])) ? $data2->results->FirstName.' '.$data2->results->LastName : 'Guest';

                                    $logEmail = (isset($_SESSION['uid']) && !isset($_SESSION['GUEST_LOGIN'])) ? $data2->results->Email : $_SESSION["GUEST_LOGIN"];
                                ?>
                                <div class="username">
                                    <strong>Logged in:</strong> <span><?=$logInName?></span> <a role="button" class="primary-dark pointer" data-toggle="modal" data-target="#loginModal">(Sign in as different user)</a>
                                </div> 
                                
                                <div class="EmailID">
                                    <strong>Email:</strong> <span><?=$logEmail?></span>
                                </div>

                                <!-- <p class="mt-4">login with different user</p>
                                <button data-toggle="modal" data-target="#loginModal" class="c-btn c-btn-semi-compact c-btn-light">CHANGE USER</button> -->
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="mt-4">
                        <section class="box-title box-title-alt common-ele-padding">
                            <span class="spn-title spn-title-alt">
                                <span class="bold text-uppercase"><span class="step-number">2</span> Order Shipping Details</span>
                            </span>
                        </section>

                        <?php if(isset($_SESSION['uid']) || isset($_SESSION['GUEST_LOGIN'])) { ?>
                        <div class="box-body">
                            <!--trigger loop from here-->
                            <?php
                                $i = 0;
                                if(isset($data->results) && !isset($data->results->error)) {
                                foreach($data->results as $key => $val) {
                                    $bagTotal += ($val->Mrp + $val->ProductSizePrice) * $val->ProductQty;
                                    $bagTotalAfterDiscount += ($val->Price + $val->ProductSizePrice) * $val->ProductQty;
                                    $mrp = ($val->Mrp + $val->ProductSizePrice) * $val->ProductQty;
                                    $price = ($val->Price + $val->ProductSizePrice)  * $val->ProductQty;
                                    $discount = (($mrp - $price) / $mrp) * 100;
                                    $bagDiscount += $mrp - $price;
                                    $shippingCharge += $val->TimeSlotCharges;
                                    $packingCharge += $val->PackingPrice;
                                    $totalPayable = $bagTotalAfterDiscount + $shippingCharge + $packingCharge;
                                    // $_SESSION['finalCheckoutPrice'] = $totalPayable;
                            ?>
                            <div class="cart-item-wrapper cart-products common-ele-padding">
                                <div class="cart-item" cart-id="<?=$val->CartID?>" id="Cart-<?=$val->CartID?>">
                                    <div class="row">
                                        <div class="col-12 col-xl-8">
                                            <img src="<?=DOMAIN?><?=$val->ProductIamge?>" alt="<?=$val->ProductName?>" class="img-product" />
                                            <h3 class="product-title"><?=$val->ProductName?></h3>
                                            <p class="small-description">
                                                <?php if($val->Type) { ?>
                                                    <?=$val->Type?>
                                                <?php } ?>

                                                <?php 
                                                    if(!empty($val->Feature) && !empty($val->Type)) {
                                                ?>
                                                    ,
                                                <?php } if($val->Feature) { ?>
                                                    <?=$val->Feature?>
                                                <?php } ?>

                                                <?php 
                                                    if(!empty($val->Feature) && !empty($val->Size)) {
                                                ?>
                                                    ,
                                                <?php } if($val->Size) { ?>
                                                    <?=$val->Size?>
                                                <?php } ?>
                                            </p>

                                            <p class="item-message">
                                                <?php if(strlen($val->SenderMessage) > 30) { ?>
                                                    <span data-message="<?=$val->SenderMessage?>"><?=substr($val->SenderMessage, 30)?></span>....<a role="button">Read more</a>
                                                <?php } elseif(strlen($val->SenderMessage) === 0) { ?>
                                                    <a role="button">Enter gift message</a>
                                                <?php } else { ?>
                                                    <span data-message="<?=$val->SenderMessage?>"><?=$val->SenderMessage?></span>....<a role="button">Read more</a>
                                                <?php } ?>

                                                <div class="message-box-wrapper">
                                                    <textarea class="txt-gift-message" placeholder="Enter your message"></textarea>
                                                    <button type="button" class="btn-gift-msg" data-index="<?=$i?>">Save Message</button>
                                                </div>
                                            </p>

                                            <div class="cart-count">
                                                <div class="cart-count-inner qty-box">
                                                    <?php
                                                        if($val->ProductQty === '5') {
                                                            $disabledPlus = 'disabled';
                                                        } else {
                                                            $disabledPlus = '';
                                                        }

                                                        if($val->ProductQty === '1') {
                                                            $disabledMinus = 'disabled';
                                                        } else {
                                                            $disabledMinus = '';
                                                        }
                                                    ?>

                                                    <button class="cmn-field minus" <?=$disabledMinus?>>
                                                        <i class="fas fa-minus"></i>
                                                    </button>

                                                    <input type="text" id="Quantity" class="cmn-field txt-qty" data-index="<?=$i?>" value="<?=$val->ProductQty?>" />

                                                    <button class="cmn-field plus" <?=$disabledPlus?>>
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>

                                                <div class="cart-item-delete" data-index="<?=$i?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-xl-4">
                                            <div class="cart-amt-time">
                                                <span class="price-and-discount">
                                                    <span class="common-price strike-price">
                                                        <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="setCurrBasedPrice"><?=$mrp?></span>
                                                    </span>
                                                    <span class="common-price sales-price">
                                                        <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="setCurrBasedPrice"><?=$price?></span>
                                                    </span>
                                                    <span class="discount color-secondary3">(<?=number_format($discount)?>%)</span>
                                                </span>

                                                <span class="shipping-charge color-secondary3">Shipping charges <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="shippingCharge setCurrBasedPrice"><?=$val->TimeSlotCharges?></span></span>

                                                <?php if($val->PackingPrice) { ?>
                                                    <span class="shipping-charge color-secondary3">
                                                        Packing charges <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="packingCharge setCurrBasedPrice"><?=$val->PackingPrice?></span>
                                                    </span>
                                                <?php } ?>

                                                <?php 
                                                    $input = $val->DeliveryDate;
                                                    $date = date('jS F Y', strtotime($input));
                                                ?>

                                                <span class="delivery-time"><?=$date?> at <?=$val->DeliveryTimeSlot?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 custom-saperator"></div>


                            <!--addon products-->
                            <?php
                                if(isset($val->Addon)) {
                                foreach($val->Addon as $key => $value) {
                                    $bagTotal += ($value->Mrp + $value->ProductSizePrice) * $value->ProductQty;
                                    $bagTotalAfterDiscount += ($value->Price + $value->ProductSizePrice) * $value->ProductQty;
                                    $mrp = ($value->Mrp + $value->ProductSizePrice) * $value->ProductQty;
                                    $price = ($value->Price + $value->ProductSizePrice)  * $value->ProductQty;
                                    $discount = (($mrp - $price) / $mrp) * 100;
                                    $bagDiscount += $mrp - $price;
                                    $shippingCharge += $value->TimeSlotCharges;
                                    $packingCharge += $value->PackingPrice;
                                    $totalPayable = $bagTotalAfterDiscount + $shippingCharge + $packingCharge;
                                    // $_SESSION['finalCheckoutPrice'] = $totalPayable;
                            ?>
                                <div class="cart-item mt-4" cart-id="<?=$value->CartID?>" id="Cart-<?=$value->CartID?>">
                                    <div class="row">
                                        <div class="addon-tag">Addon</div>

                                        <div class="col-12 col-xl-8">
                                            <img src="<?=DOMAIN?><?=$value->ProductIamge?>" alt="<?=$value->ProductName?>" class="img-product" />
                                            <h3 class="product-title"><?=$value->ProductName?></h3>
                                            <p class="small-description">
                                                <?php if($value->Type) { ?>
                                                    <?=$value->Type?>
                                                <?php } ?>

                                                <?php 
                                                    if(!empty($value->Feature) && !empty($value->Type)) {
                                                ?>
                                                    ,
                                                <?php } if($value->Feature) { ?>
                                                    <?=$value->Feature?>
                                                <?php } ?>

                                                <?php 
                                                    if(!empty($value->Feature) && !empty($value->Size)) {
                                                ?>
                                                    ,
                                                <?php } if($value->Size) { ?>
                                                    <?=$value->Size?>
                                                <?php } ?>
                                            </p>

                                            <p class="item-message">
                                                <?php if(strlen($value->SenderMessage) > 30) { ?>
                                                    <span data-message="<?=$value->SenderMessage?>"><?=substr($value->SenderMessage, 30)?></span>....<a role="button">Read more</a>
                                                <?php } elseif(strlen($value->SenderMessage) === 0) { ?>
                                                    <a role="button">Enter gift message</a>
                                                <?php } else { ?>
                                                    <span data-message="<?=$value->SenderMessage?>"><?=$value->SenderMessage?></span>....<a role="button">Read more</a>
                                                <?php } ?>

                                                <div class="message-box-wrapper">
                                                    <textarea class="txt-gift-message" placeholder="Enter your message"></textarea>
                                                    <button type="button" class="btn-gift-msg" data-index="<?=$i?>">Save Message</button>
                                                </div>
                                            </p>

                                            <div class="cart-count">
                                                <div class="cart-count-inner qty-box">
                                                    <?php
                                                        if($value->ProductQty === '5') {
                                                            $disabledPlus = 'disabled';
                                                        } else {
                                                            $disabledPlus = '';
                                                        }

                                                        if($value->ProductQty === '1') {
                                                            $disabledMinus = 'disabled';
                                                        } else {
                                                            $disabledMinus = '';
                                                        }
                                                    ?>

                                                    <button class="cmn-field minus" <?=$disabledMinus?>>
                                                        <i class="fas fa-minus"></i>
                                                    </button>

                                                    <input type="text" id="Quantity" class="cmn-field txt-qty" data-index="<?=$i?>" value="<?=$value->ProductQty?>" />

                                                    <button class="cmn-field plus" <?=$disabledPlus?>>
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>

                                                <div class="cart-item-delete" data-index="<?=$i?>">
                                                    <i class="fas fa-trash-alt"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 col-xl-4">
                                            <div class="cart-amt-time">
                                                <span class="price-and-discount">
                                                    <span class="common-price strike-price">
                                                        <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="setCurrBasedPrice"><?=$mrp?></span>
                                                    </span>
                                                    <span class="common-price sales-price">
                                                        <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="setCurrBasedPrice"><?=$price?></span>
                                                    </span>
                                                    <span class="discount color-secondary3">(<?=number_format($discount)?>%)</span>
                                                </span>

                                                <span class="shipping-charge color-secondary3">Shipping charges <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="shippingCharge setCurrBasedPrice"><?=$value->TimeSlotCharges?></span></span>

                                                <?php if($value->PackingPrice) { ?>
                                                    <span class="shipping-charge color-secondary3">
                                                        Packing charges <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="packingCharge setCurrBasedPrice"><?=$value->PackingPrice?></span>
                                                    </span>
                                                <?php } ?>

                                                <?php 
                                                    $input = $value->DeliveryDate;
                                                    $date = date('jS F Y', strtotime($input));
                                                ?>

                                                <span class="delivery-time"><?=$date?> at <?=$val->DeliveryTimeSlot?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 custom-saperator"></div>
                                </div>

                            <?php }} ?>





                                </div>

                                <div class="address-wrapper" data-ProductID="<?=$val->ProductID?>">
                                    <div class="address-box-wrapper">
                                        <p class="title">SELECT ADDRESS </p>
                                        <div class="address-lists">
                                            <div class="row mt-3 lister">
                                                <?php
                                                 if(!isset($data1->results->error) && isset($data1->results)) {
                                                    foreach($data1->results as $key => $add)
                                                    {
                                                ?>
                                                    <div class="col-12 col-lg-4 mb-3 address-grid">
                                                        <div id="addBox<?=$add->ID?>" class="address-box" address-id="<?=$add->ID?>">
                                                            <button type="button" data-addressSNO="<?=$add->ID?>" class="btn-remove-address address-<?=$add->ID?>">&times;</button>
                                                            <button type="button" data-addressSNO="<?=$add->ID?>" class="btn-edit">Edit</button>
                                                            <p><span class="delivery-title"><?=$add->Title?></span>. <span class="delivery-fname"><?=$add->FirstName?> </span> <span class="delivery-lname"><?=$add->LastName?></span></p>
                                                            <p class="address">
                                                                <span class="blockNo"><?=$add->BuildingName?></span>
                                                                <span class="streetName"><?=$add->StreetName?></span>
                                                                <span class="areaName"><?=$add->AreaName?></span>
                                                                <span class="landmark"><?=$add->Landmark?> </span>
                                                            </p>
                                                            <p><span class="address-city"><?=$add->City?> - </span><span class="address-postcode"><?=$add->Postcode?></span> <span class="address-state d-none"><?=$add->ID?>State</span><span class="address-country d-none">India</span></p>
                                                            <p>
                                                                Mobile -
                                                                <span class="address-contact"><?=$add->MobileNumber?></span>
                                                                <span class="address-alt-contact d-none"><?=$add->AlternateNumber?></span>
                                                            </p>

                                                            <?php
                                                                // if(!empty($val->AddressSelectedID)) {
                                                                //     $selected = 'selected';
                                                                // } else {
                                                                //     $selected = '';
                                                                // }
                                                            ?>

                                                            <p class="action-button">
                                                                <button type="button" class="mb-2 btn-deliver" data-index="<?=$add->ID?>" data-addressSNO="<?=$add->ID?>AddressSNO">Deliver here</button>
                                                            </p>
                                                        </div>
                                                    </div>
                                                <?php }} ?>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <button class="btn-add-new"> + Add New Address</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="address-form d-none">
                                        <p class="title">Enter Address </p>
                                        <form name="shippingForm" class="formValidation" autocomplete="off" id="updateAddress" action="" method="post" role="form">
                                            <div class="row">
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-caption">Title</label><span class="mandate">*</span>
                                                        <select class="form-control form-title h_112" name="Title" >
                                                            <option value=""></option>
                                                            <option value="Mr">Mr</option>
                                                            <option value="Miss">Miss</option>
                                                            <option value="Mrs">Mrs</option>
                                                            <option value="Ms">Ms</option>
                                                            <option value="Dr">Dr</option>
                                                            <option value="Professor">Professor</option>
                                                            <option value="Reverend">Reverend</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-caption">First Name</label><span class="mandate">*</span>
                                                        <input type="text" data-val="firstname" name="FirstName" class="form-control form-firstname h_113" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-caption">Last Name</label><span class="mandate">*</span>
                                                        <input type="text" data-val="lastName" name="LastName" class="form-control form-lastname h_114" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-caption">Block No / Building Name</label><span class="mandate">*</span>
                                                        <input type="text" data-val="address" name="BuildingName" class="form-control form-block-number h_115" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-caption">Street Name</label><span class="mandate">*</span>
                                                        <input type="text" data-val="address" name="StreetName" class="form-control form-street-name h_116" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-caption">Additional address information</label>
                                                        <input type="text" data-val="address" name="AreaName" class="form-control form-area-name h_117" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-caption">Landmark</label>
                                                        <input type="text" data-val="address" name="Landmark" class="form-control form-landmark h_118" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">City</label><span class="mandate">*</span>
                                                        <select data-val="address" name="City" class="form-control form-city h_119">
                                                            <option></option>
                                                            <?php
                                                                foreach($val->Cities as $key => $cit) {
                                                            ?>
                                                                <option class="city" value="<?=$cit->CityName?>" data-cityID="<?=$cit->CityID?>"><?=$cit->CityName?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">State</label><span class="mandate">*</span>
                                                        <input type="text" data-val="address" name="State" readonly class="form-control form-state h_1110" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">Postcode</label><span class="mandate">*</span>
                                                        <input type="text" data-val="postcode" data-pattern="^[0-9]{6}$" name="Postcode" class="form-control form-postcode h_1111" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">Country</label><span class="mandate">*</span>
                                                        <input type="text" data-val="address" name="Country" readonly class="form-control form-country h_1112" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">Mobile Number</label><span class="mandate">*</span>
                                                        <input type="text" data-val="mobile" name="MobileNumber" inputmode="numeric" class="form-control form-mobile h_1113" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">Alternate Number</label>
                                                        <input type="text" data-val="mobile" name="AlternateNumber" inputmode="numeric" class="form-control form-alt-mobile h_1114" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-caption">Special Instruction</label>
                                                        <input type="text" inputmode="numeric" name="SpecialInstruction" class="form-control h_1115" />
                                                    </div>
                                                </div>

                                                <div class="col-12 text-right">
                                                    <a class="link-primary btn-back-address">Cancel</a>

                                                    <button type="submit" class="c-btn c-btn-primary c-btn-semi-compact c-btn-semi-smooth btn-shipping-address text-uppercase">Save and deliver here</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <?php $i++; }} ?>
                        </div>
                        <?php } ?>
                            <!--trigger loop till here-->
                    </div>

                    <div class="mt-4">
                        <section class="box-title box-title-alt common-ele-padding">
                            <span class="spn-title spn-title-alt">
                                <span class="bold text-uppercase"><span class="step-number">3</span> Billing Address</span>
                            </span>
                        </section>

                        <?php 
                            if(isset($_SESSION['uid'])) {
                                $logEmail = isset($data2->results->Email) ? $data2->results->Email : $_SESSION["GUEST_LOGIN"];
                        ?>
                        <div class="box-body">
                            <div class="cart-item-wrapper common-ele-padding">
                                <div class="billing-form">
                                    <div class="address-form">
                                        <form name="shippingFormSubmit" autocomplete="off" class="formValidation" autocomplete="off" id="shippingFormSubmit">
                                            <input type="hidden" name="useremail" value="<?=$logEmail?>">
                                            <div class="row">
                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-caption">Title</label><span class="mandate">*</span>
                                                        <select class="form-control form-title" name="type">
                                                        <option value=""></option>
                                                            <option value="Mr">Mr</option>
                                                            <option value="Miss">Miss</option>
                                                            <option value="Mrs">Mrs</option>
                                                            <option value="Ms">Ms</option>
                                                            <option value="Dr">Dr</option>
                                                            <option value="Professor">Professor</option>
                                                            <option value="Reverend">Reverend</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-caption">First Name</label><span class="mandate">*</span>
                                                        <input type="text" data-val="firstname" name="fName" class="form-control form-firstname" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-caption">Last Name</label><span class="mandate">*</span>
                                                        <input type="text" data-val="lastName" name="lName" class="form-control form-lastname" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-caption">Block No / Building Name</label><span class="mandate">*</span>
                                                        <input type="text" data-val="address" name="blockNo" class="form-control form-block-number" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-caption">Street Name</label><span class="mandate">*</span>
                                                        <input type="text" data-val="address" name="streetName" class="form-control form-street-name" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-caption">Additional address information</label>
                                                        <input type="text" data-val="address" name="areaName" class="form-control form-area-name" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-caption">Phone Number</label><span class="mandate">*</span>
                                                        <input type="text" data-val="MobileNumber" name="MobileNumberBilling" class="form-control form-phone-no" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">City</label><span class="mandate">*</span>
                                                        <input type="text" data-val="city" name="city" class="form-control form-city h_119" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">State</label><span class="mandate">*</span>
                                                        <input type="text" data-val="state" name="state" class="form-control form-state h_1110" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">Postcode</label><span class="mandate">*</span>
                                                        <input type="text" data-val="postcode" data-pattern="^[0-9]{6}$" name="postCodeBilling" class="form-control form-postcode" />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-3">
                                                    <div class="form-group">
                                                        <label class="form-caption">Country</label><span class="mandate">*</span>
                                                        <select data-val="country" name="country" class="form-control form-city h_1112">
                                                            <option></option>
                                                            <?php
                                                                foreach($data3->results as $key => $country) {
                                                            ?>
                                                                <option class="country" value="<?=$country->CountryName?>" data-cityID="<?=$country->CountryID?>"><?=$country->CountryName?></option>
                                                            <?php } ?>
                                                        </select>


                                                        <input type="hidden" name="countryCode" readonly class="d-none h_11121" />
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>

                    <div class="mt-4">
                        <section class="box-title box-title-alt common-ele-padding">
                            <span class="spn-title spn-title-alt">
                                <span class="bold text-uppercase"><span class="step-number">4</span> Payment Method</span>
                            </span>
                        </section>

                        <?php 
                            if(isset($_SESSION['uid'])) {
                                $logEmail = isset($data2->results->Email) ? $data2->results->Email : $_SESSION["GUEST_LOGIN"];

                                $logMobileNo = isset($data2->results->MobileNo) ? $data2->results->MobileNo : '';
                        ?>
                        <div class="box-body">
                            <div class="payment-method-wrapper common-ele-padding">
                                <div class="select-head">
                                    <img src="<?=DOMAIN?>Content/assets/images/common/payment-method-select.png" alt="payments" class="img-fluid" />
                                </div>

                                <div class="select-head">
                                    <div class="custom-radio">
                                        <input id="p1" class="common-chk-controls payments1110" name="chkPayment" type="radio">
                                        <label for="p1">
                                            <img src="<?=DOMAIN?>Content/assets/images/common/payu.png" class="img-paymethod" alt="payu" />
                                            <span class="spn-paymethod">PayU Money - All Credit Card / Debit Card / Net Banking / Wallet Payment</span>
                                        </label>

                                        <form action="<?=DOMAIN.'wallet-placeholder'?>" method="post" id="wltu12PP">
                                        </form>

                                        <form action="<?=DOMAIN.'payu-placeholder'?>" method="post" id="pyu12PP">
                                            <input type="hidden" name="checkout_data" value="<?=base64_encode(json_encode($data->results))?>">
                                            <input type="hidden" name="dscp" id="pc2121" value="<?=$promoDiscount?>">
                                            <input type="hidden" class="ad2" name="fName">
                                            <input type="hidden" class="ad3" name="lName">
                                            <input type="hidden" class="ad4" name="blockNo">
                                            <input type="hidden" class="ad5" name="streetName">
                                            <input type="hidden" class="ad6" name="areaName">
                                            <input type="hidden" class="ad7" name="landmark">
                                            <input type="hidden" class="ad8" name="city">
                                            <input type="hidden" class="ad9" name="state">
                                            <input type="hidden" class="ad10" name="postCode">
                                            <input type="hidden" class="wlltAm" name="tgwllt" value="">
                                            <input type="hidden" class="ad11" name="country">
                                            <input type="hidden" class="ad12" name="userEmail" value="<?=$logEmail?>">
                                            <input type="hidden" class="ad14" name="mobileNo" value="<?=$logMobileNo?>">
                                            <input type="hidden" class="ad15" name="UserID" value="<?=$_SESSION['uid']?>">

                                            <?php
                                                $resultar[] = '';
                                                foreach($data->results as $key => $value) {
                                                    if(!empty($value->CartID) && isset($value->CartID)) {
                                                        $resultar[] += $value->CartID;
                                                        if(isset($value->Addon)) {
                                                            foreach($value->Addon as $key => $val) {
                                                                if(!empty($val->CartID) && isset($val->CartID)) {
                                                                    $resultar[] += $val->CartID;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }

                                                $payuCartIds = implode(',', array_filter($resultar));
                                            ?>

                                            <input type="hidden" class="ad16" name="CartID" value="<?=$payuCartIds?>">
                                            <input type="hidden" class="ad17 uwallet" name="UWallet" value="">
                                        </form>
                                    </div>
                                </div>

                                <div class="select-head">
                                    <div class="custom-radio">
                                        <input id="p2" class="common-chk-controls payments1110" name="chkPayment" type="radio">
                                        <label for="p2">
                                            <img src="<?=DOMAIN?>Content/assets/images/common/paypal.png" class="img-paymethod" alt="paypal" />
                                            <span class="spn-paymethod">Paypal - All Credit Card / Debit Card / Net Banking</span>
                                        </label>

                                        <form action="<?=DOMAIN.'paypal-redirect'?>" method="post" id="py12PP">
                                            <input type="hidden" name="checkout_data" value="<?=base64_encode(json_encode($data->results))?>">
                                            <input type="hidden" name="dscp" id="pc2121" value="<?=$promoDiscount?>">
                                            <input type="hidden" class="ad1" name="type">
                                            <input type="hidden" class="ad2" name="fName">
                                            <input type="hidden" class="ad3" name="lName">
                                            <input type="hidden" class="ad4" name="blockNo">
                                            <input type="hidden" class="ad5" name="streetName">
                                            <input type="hidden" class="ad6" name="areaName">
                                            <input type="hidden" class="ad7" name="landmark">
                                            <input type="hidden" class="ad8" name="city">
                                            <input type="hidden" class="wlltAm" name="tgwllt" value="">
                                            <input type="hidden" class="ad9" name="state">
                                            <input type="hidden" class="ad10" name="postCode">
                                            <input type="hidden" class="ad11" name="country">
                                            <input type="hidden" class="ad12" name="userEmail" value="<?=$logEmail?>">
                                            <input type="hidden" class="ad13" name="countryCode">
                                            <input type="hidden" class="ad14" name="mobileNo" value="<?=$logMobileNo?>">

                                            <?php
                                                $resultarr[] = '';
                                                foreach($data->results as $key => $value) {
                                                    if(!empty($value->CartID) && isset($value->CartID)) {
                                                        $resultarr[] += $value->CartID;
                                                        if(isset($value->Addon)) {
                                                            foreach($value->Addon as $key => $val) {
                                                                if(!empty($val->CartID) && isset($val->CartID)) {
                                                                    $resultarr[] += $val->CartID;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                $paypalCartIds = implode(',', array_filter($resultarr));
                                            ?>

                                            <input type="hidden" class="ad15 custom-tags" name="custom" value="custom=<?=$_SESSION['uid']?> crtid=<?=$paypalCartIds?>">
                                            <input type="hidden" class="ad17 uwallet" name="UWallet" value="">

                                            <?php
                                                // $str = 'custom=1 crtid=76,81 mobileNumber=9664258769';
                                                // preg_match_all('#crtid=([^\s]+)#', $str, $matchKey);
                                                //print_r($matchKey);
                                                // $cartids = explode(",", $matchKey[1][0]);
                                                // print_r($cartids);
                                            ?>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4 custom-grid">
            <div class="prime-box cart-sidebox">
                <div class="prime-box-inner">
                    <?php if(isset($_SESSION['uid']) || isset($_SESSION['GUEST_LOGIN'])) { ?>
                        <section class="box-title common-ele-padding">
                            <i><span class="far fa-money-bill-alt"></span></i>
                            <span class="spn-title">
                                <span class="color-grey-dark bold">Payment Details</span>
                            </span>
                        </section>
                        <div class="custom-saperator"></div>

                        <div class="payment-details-wrapper common-ele-padding">
                            <table class="tbl-payment">

                                <tr>
                                    <td>Bag total</td>
                                    <td><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="totalbag setCurrBasedPrice"><?=$bagTotal?></span></td>
                                </tr>

                                <tr id="Bad-Discount">
                                    <td class="color-secondary3">Bag Discount</td>
                                    <td class="color-secondary3"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="discountbag setCurrBasedPrice"><?=$bagDiscount?></span></td>
                                </tr>

                                <tr id="subtotal">
                                    <td>Sub total</td>
                                    <td><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="subtotalbag setCurrBasedPrice"> <?=$bagTotalAfterDiscount?></span></td>
                                </tr>

                                <tr>
                                    <td>Shipping charges</td>
                                    <td><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="shippingbag setCurrBasedPrice"> <?=$shippingCharge?></span></td>
                                </tr>

                                <?php 
                                    if(!empty($packingCharge) && $packingCharge >= 1) {
                                ?>
                                    <tr>
                                        <td>Packing charges</td>
                                        <td><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="shippingbag setCurrBasedPrice"> <?=$packingCharge?></span></td>
                                    </tr>
                                <?php } ?>

                                <?php 
                                    if(!empty($_SESSION['PromoDiscount']) && isset($_SESSION['PromoDiscount'])) {
                                ?>
                                    <tr id="promoCode">
                                        <td class="color-secondary3">Promo code discount</td>
                                        <td class="color-secondary3"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="promoCodebag setCurrBasedPrice"><?=$_SESSION['PromoDiscount']?></span> <span class="removePromo" title="Remove promo code"></span></td>
                                    </tr>
                                <?php } ?>

                                <tr id="promoCode"></tr>

                                <?php if(isset($_SESSION['uid']) && isset($data4->results)) { ?>
                                    <tr class="enableWallet d-none">
                                        <td class="color-secondary3">Floral Wallet Balance</td>
                                        <td class="color-secondary3"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="walletbag setCurrBasedPrice"> <?=$data4->results->CreditValue?></span></td>
                                    </tr>
                                <?php } ?>

                                <?php 
                                    if(isset($_SESSION['PromoDiscount']) && !empty($_SESSION['PromoDiscount'])) {
                                        $totalPayableAftrDiscount = $totalPayable - $_SESSION['PromoDiscount'];
                                    } else {
                                        $totalPayableAftrDiscount = $totalPayable;
                                    }
                                ?>

                                <tr>
                                    <td>Final Payble Amount</td>
                                    <td><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="finalbag setCurrBasedPrice"><?=$totalPayableAftrDiscount?></span></td>
                                </tr>
                            </table>
                            <input type="hidden" id="finalPay" class="d-none" value="<?=$totalPayable?>"/>
                        </div>

                        <div class="custom-saperator"></div>
                        <?php if(isset($_SESSION['uid']) || isset($_SESSION['GUEST_LOGIN'])) { ?>
                            <?php if(!isset($data4->results->error)) { ?>
                                <div class="use-wallet-wrapper common-ele-padding">
                                    <input type="hidden" value="<?=$data4->results->CreditValue?>" id="hdnWalletBalance" />
                                    <div class="custom-checkbox">
                                        <input id="enableWallet" class="common-chk-controls" type="checkbox">
                                        <label for="enableWallet">
                                            <span>Floral Wallet Balance</span><br />
                                            <span class="available-balance">Available Balance Rs.<span class="walletBalance"><?=$data4->results->CreditValue?></span></span>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="promo-code-wrapper common-ele-padding mt-3">
                                <div class="enablePromoCode">
                                    <button type="button" class="c-btn c-btn-block c-btn-semi-compact c-btn-light" id="enablePromoCode">Have a Promocode?</button>
                                    <div class="apply-promocode-wrapper d-none">
                                        <input type="text" id="txtDiscountCode" placeholder="Enter your Promocode" />
                                        <button type="button" id="applyDiscount">Apply</button>
                                        <span class="erro_msg"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="common-ele-padding">
                                <button type="submit" class="c-btn c-btn-block c-btn-semi-compact c-btn-primary" id="po22212">PLACE ORDER</button>
                                <p class="place-note">By clicking on place order. I agree to the <a target="_blank" href="<?=DOMAIN?>terms-and-condition" class="link-primary">Terms &amp; Conditions</a></p>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="check_desc">
                            <i class="fa fa-check" aria-hidden="true"></i>
                            <span class="s3KDM9">Safe and Secure Payments. 100% Authentic products.</span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } else {?>
        <?php  header("Location:".DOMAIN); ?>
    <?php } ?>
    </div>
</div>