<?php 
    header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<!--[if IEMobile 7 ]> <html dir="ltr" lang="en-US" class="no-js ie iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html dir="ltr" lang="en-US" class="no-js ie ie6 oldie"> <![endif]-->
<!--[if IE 7 ]>    <html dir="ltr" lang="en-US" class="no-js ie ie7 oldie"> <![endif]-->
<!--[if IE 8 ]>    <html dir="ltr" lang="en-US" class="no-js ie ie8 oldie"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
    <head>

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=6; IE=EDGE" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title><?=!empty($tag['title']) ? $tag['title'] : '';?></title>
        <meta name="title" content="<?=!empty($tag['title']) ? $tag['title'] : ''; ?>"/>
        <meta name="description" content="<?=!empty($tag['desc']) ? $tag['desc'] : ''; ?>"/>
        <meta name="keywords" content="<?=!empty($tag['key']) ? $tag['key'] : ''; ?>"/>
        <meta name="google-signin-client_id" content="743962321274-un95khs0rom33v09eol9ocovljm3dvsd.apps.googleusercontent.com">

        <link rel="shortcut icon" href="<?=IMG?>common/favicon.png" type="image/x-icon" />
        <link rel="icon" href="" type="image/x-icon" />
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Mukta:wght@100;300;400;500;700&display=swap" />
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="<?=MINIFIED?>css/lib-style.min.css?v=<?=VERSION?>" />
        <link rel="stylesheet" href="<?=MINIFIED?>css/main-style.min.css?v=<?=VERSION?>" />
        <link href="<?=MINIFIED?>css/fontawesome.css?v=<?=VERSION?>" rel="stylesheet" />

        
        
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAv9L8hF363iz59UduNR-DvmwUWDByXdBQ&sensor=false"></script>  
        <script src="https://apis.google.com/js/platform.js"></script>   
        <script>
            var PROTOCOL                = "<?=PROTOCOL?>";
            var FLORAL_AJAX             = "<?=FLORAL_AJAX?>";
            var FLORAL_API_LINK         = "<?=FLORAL_API_LINK?>";
            var CASE                    = "<?=$case?>";
            var IMG                     = "<?=IMG?>";
            var COOKIE_DOMAIN           = "<?=COOKIE_DOMAIN?>";
            var IMAGE_URL               = "<?=IMAGE_URL?>";
            var CURRENCY_LOGO           = "<?=$CurrencyLogo?>";
            var COUNTRY_CODE            = "<?=$countryCode?>";
            var CITY_ID                 = "<?=$CityID?>";
            var CITY                    = "<?=$city?>";
            var DOMAIN                  = "<?=(isset($_SERVER['HTTP']) ? 'http' : 'https' ). '://' . $_SERVER['SERVER_NAME']?>";


            (function() { 
            if ("-ms-user-select" in document.documentElement.style && navigator.userAgent.match(/IEMobile\/10\.0/)) { 
                var msViewportStyle = document.createElement("style"); 
                msViewportStyle.appendChild( 
                    document.createTextNode("@-ms-viewport{width:auto!important}") 
                ); 
                document.getElementsByTagName("head")[0].appendChild(msViewportStyle); 
            } 
            })(); 

            window.fbAsyncInit = function () {
                FB.init({
                    appId               : '755411775199857',
                    autoLogAppEvents    : true,
                    cookie              : true,
                    xfbml               : true,
                    version             : 'v9.0'
                });

                FB.AppEvents.logPageView();
            };

            (function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) { return; }
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
    </head>

    <input type="hidden" id="case" value="<?php echo $case; ?>">
    <div id="amazon-root"></div>
<script type="text/javascript">

  window.onAmazonLoginReady = function() {
    amazon.Login.setClientId('amzn1.application-oa2-client.c53888163f6c4536b6790fcfd25b6c13');
  };
  (function(d) {
    var a = d.createElement('script'); a.type = 'text/javascript';
    a.async = true; a.id = 'amazon-login-sdk';
    a.src = 'https://assets.loginwithamazon.com/sdk/na/login1.js';
    d.getElementById('amazon-root').appendChild(a);
  })(document);

</script>
    <body class="<?=$case === 'checkout' ? 'checkout-page' : ''?>">

        <div class="full-loader-image"></div>
        <header class="headroom">
            <div class="d-none d-xl-block desktop-header">
                <div class="container custom-container top-menu-wrapper">
                    <?php if($case != 'checkout') { ?>
                        <div class="top-menu">
                            <ul class="top-menu-left">
                                <li class="has-add-drop set-country">
                                    <div class="menu-title">Select Country</div>
                                    <div class="menu-select">
                                        Send to : <span class="selected-option">
                                            <img src="<?=DOMAIN?><?=$countryFlag?>" class="country-flag" />
                                            <img class="select-arrow" src="<?=IMG?>common/arrow-down.png" />
                                        </span>
                                        <div class="custom-select add-drop drop-indicator">
                                            <div class="search-custom d-none">
                                                <input type="text" class="txt-custom-search" placeholder="Search Country" />
                                            </div>

                                            <ul data-simplebar data-simplebar-auto-hide="false" class="country-selector">
                                            <?php if($countryData->results) {
                                                foreach ($countryData->results as $key => $value)
                                                { 
                                            ?>
                                                <li data-name="<?=$value->CountryName?>">
                                                    <button data-code="<?=$value->CountryCode?>" data-value="<?=$value->CountryName?>" class="active">
                                                        <img class="country-list" src="<?=DOMAIN?><?=$value->CountryFlag?>" /><?=$value->CountryName?>
                                                    </button>
                                                </li>
                                                <?php } }?>
                                            </ul>
                                        </div>

                                        <div class="change-country-dialogue add-drop drop-indicator">
                                            <strong>We ship to multiple countries</strong><br />
                                            <span>Currently we are showing you items that ship to <strong>India.</strong>. To see items that ship to a different country, change your country above.</span>

                                            <div class="mt-3 text-right">
                                                <button class="c-btn c-btn-compact c-btn-secondary btn-close-country-dialogue">Don't Change</button>
                                                <button class="c-btn c-btn-compact c-btn-primary btn-country-dialogue">Change Country</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="city-click has-add-drop">
                                    <div class="menu-title">Select City</div>
                                    <div class="menu-select">
                                        <span class="selected-city">
                                            <?=$cityName?>
                                        </span>
                                        <span class="selected-option">
                                            <img class="select-arrow" src="<?=IMG?>common/arrow-down.png" />
                                        </span>
                                    </div>

                                    <div class="city-selector add-drop drop-indicator">
                                        <div class="city-selector-inner">
                                            <button class="close-drop"><i class="fas fa-times"></i></button>

                                            <div>
                                                <h1 class="common-heading primary-font no-shadow zero">Pick a City</h1>
                                                <p class="zero color-grey-dark2">To see deliverable item to your doorstep</p>
                                            </div>

                                            <div class="text-center mt-2">
                                                <div class="city-search-box">
                                                    <form autocomplete="off">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <input type="text" id="city" name="city" class="txt-city" placeholder="Enter your city name" autocomplete="off" />
                                                        <button class="btn-locate" type="button" title="Use My Current Location"><i class="fas fa-location-arrow"></i></button>
                                                    </form>
                                                    <ul data-simplebar data-simplebar-auto-hide="false" class="list-city">

                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="text-center mt-3">
                                                <div class="top-cities d-none">
                                                    <h2 class="commonn-sub-heading">Top Cities</h2>
                                                    <div class="mt-3">
                                                        <a class="search-tags"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="has-add-drop">
                                    <div class="menu-title">Currency</div>
                                    <div class="menu-select">
                                        <span><?=$currency?></span>
                                        <span class="selected-option">
                                            <img class="select-arrow" src="<?=IMG?>common/arrow-down.png" />
                                        </span>

                                        <div class="custom-select add-drop drop-indicator">
                                            <div class="search-custom d-none">
                                                <input type="text" class="currency-custom-search" placeholder="Search Country" />
                                            </div>

                                            <ul data-simplebar data-simplebar-auto-hide="false" class="currency-selector">
                                                <?php if($countryData->results) {
                                                    foreach ($countryData->results as $key => $value)
                                                    {
                                                ?>
                                                    <li data-code="<?=$value->CountryCode?>" data-name="<?=$value->Currency?>" class="currency_list">
                                                        <span data-value="<?=$value->Currency?>"><?=$value->CurrencyLogo?> <?=$value->Currency?></span>
                                                    </li>
                                                <?php } }?>
                                            </ul>
                                        </div>

                                    </div>
                                </li>

                                <li class="call-btn">
                                    <div class="menu-title">Call Us:</div>
                                    <div class="menu-select">
                                    <?php if(isset($siteData->results->MobileNo)) { ?>
                                        <span><?=$siteData->results->MobileNo?></span>
                                    <?php } ?>
                                    </div>
                                    <a class="d-none call-action" href="tel:+"></a>
                                </li>

                                <li title="Express Delivery" class="express">
                                    <a href="<?=DOMAIN?><?=$countryCode?>/all-listing/express-delivery">
                                        <img src="<?=IMG?>common/express-delivery.png" />
                                    </a>
                                </li>
                            </ul>

                            <ul class="top-menu-right">
                                <li>
                                    <a>
                                        <div class="search-box">
                                            <img class="btn-search" src="<?=IMG?>common/search.png" />
                                        </div>
                                    </a>
                                </li>

                                <?php if(!isset($_SESSION['uid'])) { ?>
                                    <li>
                                        <a data-toggle="modal" data-target="#loginModal">
                                            <div class="user-image"><img src="<?=IMG?>common/user.png" /></div>
                                            <div class="welcome-text">Login</div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </li>
                                <?php } else { ?>
                                    <li class="has-add-drop">
                                        <a>
                                            <div class="user-image"><img src="<?=IMG?>common/user.png" /></div>
                                            <div class="welcome-text">Hi
                                            <?php if(isset($userData->results->FirstName)) { ?>
                                            , <?=$userData->results->FirstName?>
                                            <?php } ?>
                                            <span></span></div>
                                            <div class="clearfix"></div>
                                        </a>

                                        <div class="add-drop">
                                            <div class="add-drop-options">
                                                <div class="add-option-items">
                                                    <a href="<?=DOMAIN?>my-profile">
                                                        <i class="fas fa-user"></i> My Profile
                                                    </a>
                                                </div>

                                            <div class="add-option-items d-none">
                                                <a href="#">
                                                    <i class="fas fa-wallet"></i> Floral Wallet (Rs. 245)
                                                </a>
                                            </div>

                                            <div class="add-option-items">
                                                <a href="<?=DOMAIN?>index.php?case=order-history&state=ongoing-orders">
                                                    <i class="fas fa-tags"></i> Order History
                                                </a>
                                            </div>

                                            <?php 
                                                if(isset($userData->results->UserType)) { 
                                                    if($userData->results->UserType == 'User') {
                                                        $btnClass = 'UserLogout';
                                                    } elseif($userData->results->UserType == 'fb-User') {
                                                        $btnClass = 'FBLogout';
                                                    } elseif($userData->results->UserType == 'gmail-User') {
                                                        $btnClass = 'GmailLogout';
                                                    } 
                                                }    
                                            ?>

                                            <div class="add-option-items">
                                                <a href="javascript:;" id="btn-logout" class="<?=$btnClass?> cls-logout">
                                                    <i class="fas fa-power-off"></i> Logout
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php } ?>

                                <li><a href="<?=DOMAIN?>track-order"><img src="<?=IMG?>common/track-order.png" class="w-more" /></a></li>
                                <li>
                                    <a href="<?=DOMAIN?>wish-list" title="whislist">
                                        <img src="<?=IMG?>common/wishlist.png" />
                                    </a>
                                </li>
                                <li class="has-add-drop">
                                    <a>
                                        <img src="<?=IMG?>common/shopping-bag.png" />
                                        <span id="cartItemCount" class="notification-balloon">
                                            <?php if(isset($clData->results) && !isset($clData->results->error)) { ?>
                                                <?=count($clData->results)?>
                                            <?php } else { ?>
                                                0
                                            <?php } ?>
                                        </span>
                                    </a>

                                    <div class="add-drop add-drop-right cart-preview">
                                        <div class="add-drop-options">
                                            <div class="add-drop-header">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <img src="<?=IMG?>common/shopping-bag.png" alt="bag" /><h1>My Bag 
                                                        <span>
                                                            <?php if(isset($clData->results) && !isset($clData->results->error)) { ?>
                                                                    (<?=count($clData->results)?> Item)
                                                            <?php }  ?>
                                                        </span>
                                                        </h1>
                                                    </div>

                                                    <div class="col-4 text-right">
                                                        <span class="item-count">
                                                            <a href="<?=DOMAIN?>cart">View Cart</a>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                        <div data-simplebar data-simplebar-auto-hide="false" class="add-drop-body">
                                        <?php
                                            $totalPrice = 0;
                                            $price = 0;
                                            $bagTotal = 0;
                                            $bagTotalAfterDiscount = 0;
                                            $mrp = 0;
                                            $NormalPrice = 0;
                                            $discount = 0;
                                            $bagDiscount = 0;
                                            $shippingCharge = 0;
                                            $packingCharge = 0;
                                            $totalPayable = 0;

                                            if(!isset($clData->results->error) && isset($clData->results)) {
                                            foreach($clData->results as $key => $val)
                                            {
                                                if(!empty($val->ProductID)) {

                                                $price = $val->Price * $val->ProductQty;

                                                if(!empty($val->ProductSizePrice)) {
                                                    $price += $val->ProductQty * $val->ProductSizePrice;
                                                }

                                                $totalPrice = $totalPrice + $price;

                                                if(!empty($val->PackingPrice)) {
                                                    $totalPrice += $val->PackingPrice;
                                                }

                                                if(!empty($val->TimeSlotCharges)) {
                                                    $totalPrice += $val->TimeSlotCharges;
                                                }

                                                $bagTotal += ($val->Mrp + $val->ProductSizePrice) * $val->ProductQty;
                                                $bagTotalAfterDiscount += ($val->Price + $val->ProductSizePrice) * $val->ProductQty;
                                                $mrp = ($val->Mrp + $val->ProductSizePrice) * $val->ProductQty;
                                                $NormalPrice = ($val->Price + $val->ProductSizePrice)  * $val->ProductQty;
                                                $discount = (($mrp - $NormalPrice) / $mrp) * 100;
                                                $bagDiscount += $mrp - $NormalPrice;
                                                $shippingCharge += $val->TimeSlotCharges;
                                                $packingCharge += $val->PackingPrice;
                                                $totalPayable = $bagTotalAfterDiscount + $shippingCharge + $packingCharge;

                                                $_SESSION['bagTotal'] = $bagTotal;
                                                $_SESSION['bagTotalAfterDiscount'] = $bagTotalAfterDiscount;
                                                $_SESSION['mrp'] = $mrp;
                                                $_SESSION['price'] = $price;
                                                $_SESSION['discount'] = $discount;
                                                $_SESSION['bagDiscount'] = $bagDiscount;
                                                $_SESSION['shippingCharge'] = $shippingCharge;
                                                $_SESSION['packingCharge'] = $packingCharge;
                                                $_SESSION['totalPayable'] = $totalPayable;
                                        ?>
                                        <div class="add-drop-items" cart-id="<?=$val->CartID?>" id="Cart-<?=$val->CartID?>">
                                            <div class="row">
                                                <div class="col-3">
                                                    <img class="cart-preview-img" src="<?=DOMAIN?><?=$val->ProductIamge?>" />
                                                </div>

                                                <div class="col-9">
                                                    <div class="cart-product-info">
                                                        <h2><?=$val->ProductName?></h2>
                                                        <p class="webruppee">
                                                            <span class="setDefaultCurrency"><?=$CurrencyLogo?></span>&nbsp;<span class="setCurrBasedPrice"><?=$price?></span>
                                                            <?php if(!empty($val->Size)) { ?>
                                                                <span class="dot"></span>
                                                                <?=strtolower(ucfirst($val->Size))?>
                                                            <?php } ?>
                                                        </p>

                                                        <p class="addon-info">
                                                            QTY : <?=$val->ProductQty?> 

                                                            <?php if(!empty($val->PackingPrice)) { ?>
                                                                (Per packing : <?=$CurrencyLogo. ' ' .$val->PackingPrice?>)
                                                            <?php } ?>

                                                            <br />
                                                            <span class="setDefaultCurrency"><?=$CurrencyLogo?></span>&nbsp;<span class="setCurrBasedPrice"><?=$val->TimeSlotCharges?></span>
                                                            <?=$val->DeliveryTimeText?>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="item-remove">
                                                    <button title="Remove" class="btn-cart-item-remove"><img src="<?=IMG?>common/trash-icon.png" /></button>
                                                </div>
                                            </div>

                                            <div class="delivery-notes">
                                                DELIVERY<br />
                                                <?php 
                                                    $input = $val->DeliveryDate;
                                                    $date = date('j F - l', strtotime($input));
                                                ?>
                                                <?=$date?> <?=$val->DeliveryTimeSlot?>
                                            </div>

                                            <!-- Addon products -->
                                            <?php
                                                if(isset($val->Addon)) 
                                                {
                                                foreach($val->Addon as $key => $value)
                                                {
                                                    if(!empty($value->ProductID)) {

                                                    $price = $value->Price * $value->ProductQty;

                                                    if(!empty($value->ProductSizePrice)) {
                                                        $price += $value->ProductQty * $value->ProductSizePrice;
                                                    }

                                                    $totalPrice = $totalPrice + $price;

                                                    if(!empty($value->PackingPrice)) {
                                                        $totalPrice += $value->PackingPrice;
                                                    }

                                                    if(!empty($value->TimeSlotCharges)) {
                                                        $totalPrice += $value->TimeSlotCharges;
                                                    }

                                                    $bagTotal += ($value->Mrp + $value->ProductSizePrice) * $value->ProductQty;
                                                    $bagTotalAfterDiscount += ($value->Price + $value->ProductSizePrice) * $value->ProductQty;
                                                    $mrp = ($value->Mrp + $value->ProductSizePrice) * $value->ProductQty;
                                                    $NormalPrice = ($value->Price + $value->ProductSizePrice)  * $value->ProductQty;
                                                    $discount = (($mrp - $NormalPrice) / $mrp) * 100;
                                                    $bagDiscount += $mrp - $NormalPrice;
                                                    $shippingCharge += $value->TimeSlotCharges;
                                                    $packingCharge += $value->PackingPrice;
                                                    $totalPayable = $bagTotalAfterDiscount + $shippingCharge + $packingCharge;

                                                    $_SESSION['bagTotal'] = $bagTotal;
                                                    $_SESSION['bagTotalAfterDiscount'] = $bagTotalAfterDiscount;
                                                    $_SESSION['mrp'] = $mrp;
                                                    $_SESSION['price'] = $price;
                                                    $_SESSION['discount'] = $discount;
                                                    $_SESSION['bagDiscount'] = $bagDiscount;
                                                    $_SESSION['shippingCharge'] = $shippingCharge;
                                                    $_SESSION['packingCharge'] = $packingCharge;
                                                    $_SESSION['totalPayable'] = $totalPayable;
                                            ?>
                                                <div class="add-drop-items addon" cart-id="<?=$value->CartID?>" id="Cart-<?=$value->CartID?>">
                                                    <div class="row">
                                                        <div class="addon-tag">Addon</div>
                                                        <div class="col-3">
                                                            <img class="cart-preview-img" src="<?=DOMAIN?><?=$value->ProductIamge?>" />
                                                        </div>

                                                        <div class="col-9">
                                                            <div class="cart-product-info">
                                                                <h2><?=$value->ProductName?></h2>
                                                                <p class="webruppee">
                                                                    <span class="setDefaultCurrency"><?=$CurrencyLogo?></span>&nbsp;<span class="setCurrBasedPrice"><?=$price?></span>
                                                                    <?php if(!empty($value->Size)) { ?>
                                                                        <span class="dot"></span>
                                                                        <?=strtolower(ucfirst($value->Size))?>
                                                                    <?php } ?>
                                                                </p>

                                                                <p class="addon-info">
                                                                    QTY : <?=$value->ProductQty?> 

                                                                    <?php if(!empty($value->PackingPrice)) { ?>
                                                                        (Per packing : <?=$CurrencyLogo. ' ' .$value->PackingPrice?>)
                                                                    <?php } ?>

                                                                    <br />

                                                                    <?php if(isset($value->DeliveryTimeText)) { ?>
                                                                        <span class="setDefaultCurrency"><?=$CurrencyLogo?></span>&nbsp;<span class="setCurrBasedPrice"><?=$value->TimeSlotCharges?></span>
                                                                        <?=$value->DeliveryTimeText?>
                                                                    <?php } ?>
                                                                </p>
                                                            </div>
                                                        </div>

                                                        <div class="item-remove">
                                                            <button title="Remove" class="btn-cart-item-remove"><img src="<?=IMG?>common/trash-icon.png" /></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }}} ?>
                                        </div>

                                    <?php }}} else { ?>
                                            <div class="noCartProducts">Your cart is empty</div>
                                    <?php } ?>
                                    </div>

                                        <?php if(!empty($totalPrice)) { ?>
                                            <div class="add-drop-footer">
                                                <div class="cart-total">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            Bag Total
                                                        </div>

                                                        <div class="col-6 text-right">
                                                        <span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span>
                                                            <?php if(!empty($totalPrice)) { ?>
                                                                <span class="setCurrBasedPrice"><?=$totalPrice?></span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="cart-checkout">
                                                    <?php 
                                                        if(!isset($_SESSION['uid'])) {
                                                            $UserSession = '';
                                                        } else {
                                                            $UserSession = 'Active';
                                                        }
                                                    ?>

                                                    <a class="btn-checkout" href="<?=DOMAIN?>index.php?case=checkout&UserSession=<?=$UserSession?>&MultiHash=<?=uniqid(rand (), true)?>">
                                                        Proceed To Checkout
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        </div>
                                    </div>
                                </li>
                            </ul>

                            <div class="clearfix"></div>
                        </div>
                    <?php } ?>

                    <div class="store-logo">
                        <a href="<?=rtrim(DOMAIN, '/');?>">
                        <?php if(isset($siteData->results->Logo)) { ?>
                            <img src="<?=DOMAIN?><?=$siteData->results->Logo?>" />
                        <?php } else { ?>
                            <img src="<?=IMG?>common/floral-logo.png" />
                        <?php } ?>
                        </a>
                    </div>
                </div>
                <div class="menu-saperator"><span class="pull-left"></span></div>
                <div class="menu-saperator"><span class="pull-right"></span></div>
                <div class="clearfix"></div>

                <?php if($case != 'checkout') { ?>
                    <div class="main-menu-wrapper">
                        <div class="container custom-container main-menu">
                            <div class="store-logo-sticky">
                                <a href="<?=rtrim(DOMAIN, '/');?>"><span class="shine">FLORAL INDIA</span></a>
                            </div>
                            <?php if(isset($mlData->results)) {
                                $i = 0;
                                if ($i <= 3) {
                            ?>
                                <ul class="left">
                            <?php }
                                if(isset($soData->results)) {
                                foreach ($soData->results as $key => $value) {
                                    if(isset($value->StartDate)) {
                                        $now = new DateTime();
                                        $startdate = new DateTime($value->StartDate);
                                        $enddate = new DateTime($value->EndDate);
                                        if($startdate <= $now && $now <= $enddate) {
                                            $name = $value->Name;
                                            $OccasionImage = $value->OccasionImage;
                                            $OccasionBanner = $value->OccasionBanner;
                                            $HashTagName = $value->HashTagName;
                                            $ID = $value->ID;
                                        }
                                    } elseif (isset($value->IsDefaultActive) && $value->IsDefaultActive == "1") {
                                        $name = $value->Name;
                                        $OccasionImage = $value->OccasionImage;
                                        $OccasionBanner = $value->OccasionBanner;
                                        $HashTagName = $value->HashTagName;
                                        $ID = $value->ID;
                                    }
                                }
                                if(isset($name)) {
                                    $anchorLink = str_replace(" ", "-", 'occasion/'.strtolower($name));
                            ?>
                                <li id="menuOccasionID-<?=$name?>">
                                    <a href="<?=DOMAIN?><?=$countryCode?>/listing/gifts/<?=$anchorLink?>/<?=$ID?>"><?=$name?></a>
                                </li>
                            <?php }} ?>
                            <?php
                                foreach ($mlData->results as $key => $value) {
                                    $classPos = ($i >= 3) ? "right" : "left";
                                    if ($i === 3) {
                                        echo '</ul><ul class="right">';
                                    }
                            ?>
                                <li class="<?=$classPos?>" id="menuID-<?=$value->MenuID?>">
                                    <a href="<?=$value->MenuURL?>" target="<?=$value->AnchorTarget?>"><?=$value->MenuName?></a>
                                    <?php if(isset($value->SubMenu)) { ?>
                                        <span class="airo"></span>
                                        <div class="sub-drop-container">
                                            <div class="row">
                                                <?php foreach ($value->SubMenu as $key => $subMenu) { ?>
                                                    <div class="col">
                                                    <ul class="list-box">
                                                        <li id="<?=$subMenu->SubMenuID?>">By <?=$subMenu->SubMenuName?></li>
                                                        <?php if(isset($subMenu->SubMenuOfMenu)) {
                                                            foreach ($subMenu->SubMenuOfMenu as $key => $subOfMenu) {

                                                                if($value->MenuName === 'need today') {
                                                                    $customParam = '/sameday-delivery';
                                                                } else {
                                                                    $customParam = '';
                                                                }

                                                                if($value->MenuName === 'combo' || $value->MenuName === 'gifts'
                                                                || $value->MenuName === 'need today' || $value->MenuName === 'e-gifts') {
                                                                    $customMenuName = '/'.str_replace(" ", "-", $value->MenuName);
                                                                }else {
                                                                    $customMenuName = '';
                                                                }

                                                            $anchorLink = ($value->MenuName === 'gift categories') ? str_replace(" ", "-", $subMenu->SubMenuName.'/'.$subOfMenu->Name) : str_replace(" ", "-", $subMenu->SubMenuName.'/'.$subOfMenu->Name);
                                                        ?>
                                                            <li id="<?=$subOfMenu->ID?>">
                                                                <a href="<?=DOMAIN?><?=$countryCode?>/listing<?=$customMenuName?><?=$customParam?>/<?=$anchorLink?>/<?=$subOfMenu->ID?>"><?=$subOfMenu->Name?></a>
                                                            </li>
                                                        <?php }} ?>
                                                    </ul>
                                                    
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </li>
                            <?php  $i++; } ?>
                            </ul>
                            <?php } ?>
                        </div>
                    </div>

                    <?php if($adData->results->AlertMessage) { ?>
                        <div class="alert-box">
                            <?=$adData->results->AlertMessage?>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>


            <div class="d-block d-xl-none mobile-header">
                <?php if($case != 'checkout') { ?>
                    <div class="alert-box">
                        <?=$adData->results->AlertMessage?>
                    </div>
                <?php } ?>
                
                <div class="store-logo">
                    <a href="<?=rtrim(DOMAIN, '/');?>">
                        <img src="<?=IMG?>common/floral-logo.png" />
                    </a>
                </div>

                <div class="nav-tab">
                    <div class="container">
                        <div class="row">
                            <div class="col-2">
                                <a class="navicon">
                                    <img src="<?=IMG?>common/navicon.png" />
                                </a>
                            </div>

                            <div class="col-2">
                                <a role="button" class="toggle-rapid-modal country">
                                    <img class="circle" src="<?=DOMAIN?><?=$flag?>" />
                                </a>
                            </div>

                            <div class="col-2">
                                <a role="button">
                                    <span class="text-image webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span>
                                </a>
                            </div>

                            <div class="col-2">
                                <a href="<?=DOMAIN?>track-order">
                                    <img src="<?=IMG?>common/track-order-mob.png" />
                                </a>
                            </div>

                            <div class="col-2">
                                <a class="btn-search">
                                    <img src="<?=IMG?>common/search-icon-mob.png" />
                                </a>
                            </div>

                            <div class="col-2 relative">
                                <a href="<?=DOMAIN?>cart">
                                    <img src="<?=IMG?>common/cart-icon-mob.png" />
                                </a>
                                <?php if(isset($clData->results) && !isset($clData->results->error)) { ?>
                                        <span class="mob-cart-balloon"><?=count($clData->results)?></span>
                                <?php }  ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="master-search">
                <div class="search">
                    <div class="txt-search-wrapper">
                        <div class="searchbox">
                            <button type="button" class="close-search"><img src="<?=DOMAIN?>Content/assets/images/common/angle-left-ios-style.png" alt="Close search"/></button>
                            <input type="text" class="txt-search" id="search_product" placeholder="Search Flower, Cake, Balloon etc">
                            <button type="button"><img src="<?=DOMAIN?>Content/assets/images/common/search-white.png" alt="Search Icon"/></button>
                        </div>
                    </div>
                </div>
                <div class="autocompleteSec">
                    <ul id="search_result" class="d-none">
                    </ul>
                </div>
            </div>
        </header>

        <!--mobile menu open-->
        <div class="mobile-menu-box">
            <div class="mobile-menu-header">
            <?php if(!isset($_SESSION['uid'])) { ?>
                <a class="mob-account">
                    <span class="user-icon"><i class="fas fa-user"></i></span> <span class="user-message">Welcome, Guest</span>
                </a>
            <?php } else { ?>
                <a>
                    <span class="user-icon"><i class="fas fa-user"></i></span> <span class="user-message">Hi, <?=$userData->results->FirstName?> <span class="UserLogout cls-logout" class="mob-logout">(Logout)</span></span>
                </a>
            <?php } ?>                
            </div>

            <ul class="mobile-nav">
                
            <div class="main-menu-wrapper">
                <div class="main-menu">
                    <?php if(isset($mlData->results)) {
                        $i = 0;
                        if ($i <= 3) {
                    ?>
                        <ul class="left">
                    <?php }
                        if(isset($soData->results)) {
                        foreach ($soData->results as $key => $value) {
                            if(isset($value->StartDate)) {
                                $now = new DateTime();
                                $startdate = new DateTime($value->StartDate);
                                $enddate = new DateTime($value->EndDate);
                                if($startdate <= $now && $now <= $enddate) {
                                    $name = $value->Name;
                                    $OccasionImage = $value->OccasionImage;
                                    $OccasionBanner = $value->OccasionBanner;
                                    $HashTagName = $value->HashTagName;
                                    $ID = $value->ID;
                                }
                            } elseif (isset($value->IsDefaultActive) && $value->IsDefaultActive == "1") {
                                $name = $value->Name;
                                $OccasionImage = $value->OccasionImage;
                                $OccasionBanner = $value->OccasionBanner;
                                $HashTagName = $value->HashTagName;
                                $ID = $value->ID;
                            }
                        }
                        if(isset($name)) {
                            $anchorLink = str_replace(" ", "-", 'SubMenuName=occasion&subOfMenu='.strtolower($name));
                    ?>
                        <li id="menuOccasionID-<?=$name?>">
                            <a href="<?=DOMAIN?>/<?=$countryCode?>/listing/gifts/<?=$anchorLink?>/<?=$ID?>"><?=$name?></a>
                        </li>
                    <?php }} ?>
                    <?php
                        foreach ($mlData->results as $key => $value) {
                            $classPos = ($i >= 3) ? "right" : "left";
                            if ($i === 3) {
                                echo '</ul><ul class="right">';
                            }
                    ?>
                        <li class="<?=$classPos?> mobile-main-menu" id="menuID-<?=$value->MenuID?>">
                            <a href="<?=$value->MenuURL?>" target="<?=$value->AnchorTarget?>" class="mobile-main-menu-anchor"><?=$value->MenuName?></a>
                            <?php if(isset($value->SubMenu)) { ?>
                                <div class="sub-drop-container d-none">
                                    <?php foreach ($value->SubMenu as $key => $subMenu) { ?>
                                        <ul class="list-box">
                                            <li class="by-button" id="<?=$subMenu->SubMenuID?>">By <?=$subMenu->SubMenuName?></li>
                                            <?php if(isset($subMenu->SubMenuOfMenu)) {
                                                foreach ($subMenu->SubMenuOfMenu as $key => $subOfMenu) {

                                                    if($value->MenuName === 'need today') {
                                                        $customParam = '/sameday-delivery';
                                                    } else {
                                                        $customParam = '';
                                                    }

                                                    if($value->MenuName === 'combo' || $value->MenuName === 'gifts'
                                                    || $value->MenuName === 'need today' || $value->MenuName === 'e-gifts') {
                                                        $customMenuName = '/'.str_replace(" ", "-", $value->MenuName);
                                                    }else {
                                                        $customMenuName = '';
                                                    }

                                                $anchorLink = ($value->MenuName === 'gift categories') ? str_replace(" ", "-", $subMenu->SubMenuName.'/'.$subOfMenu->Name) : str_replace(" ", "-", $subMenu->SubMenuName.'/'.$subOfMenu->Name);
                                            ?>
                                                <li id="<?=$subOfMenu->ID?>">
                                                    <a href="<?=DOMAIN?><?=$countryCode?>/listing<?=$customMenuName?><?=$customParam?>/<?=$anchorLink?>/<?=$subOfMenu->ID?>"><?=$subOfMenu->Name?></a>
                                                </li>
                                            <?php }} ?>
                                        </ul>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </li>
                    <?php  $i++; } ?>
                    </ul>
                    <?php } ?>
                </div>
            </div>
                
            </ul>
        </div>
        <!--mobile menu open-->

        <div class="sticky-nav d-block d-lg-none">
            <div class="navigation-wrapper">
                <div class="row">
                    <div class="col"><a href="<?=DOMAIN?>"><img src="<?=DOMAIN?>Content/assets/images/common/home.png" alt="home icon">Home</a></div>

                    <div class="col"><a href="<?=DOMAIN?>in/all-listing/sameday-delivery"><img src="<?=DOMAIN?>Content/assets/images/common/clock-dark.png" alt="home icon">Need Today</a></div>

                    <div class="col"><a href="https://api.whatsapp.com/send?phone=919910200043&amp;text=Hello, I have a question about"><img src="<?=DOMAIN?>Content/assets/images/common/chat.png" alt="home icon">Chat with Flory</a></div>

                    <div class="col">
                    <?php if(!isset($_SESSION['uid'])) { ?>
                        <a class="mob-account"><img src="<?=DOMAIN?>Content/assets/images/common/user-dark.png" alt="home icon">Account</a>
                    <?php } else { ?>
                        <a href="<?=DOMAIN?>my-profile"><img src="<?=DOMAIN?>Content/assets/images/common/user-dark.png" alt="home icon">Account</a>
                    <?php } ?>  
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="rapid-mobile-modal common-rapid-modal" style="display:none">
            <div class="rapid-country-selector common-selector" style="display:none">
                <div class="close-rapid-modal"></div>
                <div class="fixed-head text-center">Delivery Region</div>
                <div class="rapid-modal-content">
                    <ul class="rapid-modal-list country-selector">
                        <?php if($countryData->results) {
                            foreach ($countryData->results as $key => $value)
                            { 
                        ?>
                        <li data-name="<?=$value->CountryName?>">
                            <button data-code="<?=$value->CountryCode?>" data-value="<?=$value->CountryName?>" class="active">
                                <img class="country-list" src="<?=DOMAIN?><?=$value->CountryFlag?>" /><?=$value->CountryName?>
                            </button>
                        </li>
                        <?php } }?>
                    </ul>
                </div>
            </div>

            <div class="rapid-city-selector common-selector" style="display:none">
                <div class="close-rapid-modal"></div>
                <div class="fixed-head text-center">Select Delivery City</div>
                <div class="rapid-modal-content">
                    <div class="city-selector add-drop drop-indicator">
                        <div class="city-selector-inner">
                            <div>
                                <h1 class="common-heading primary-font no-shadow zero">Pick a City</h1>
                                <p class="zero color-grey-dark2">To see deliverable item to your doorstep</p>
                            </div>

                            <div class="text-center mt-2">
                                <div class="city-search-box">
                                    <form autocomplete="off">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <input type="text"  class="txt-city" placeholder="Enter your city name" autocomplete="off" />
                                        <button type="button" class="btn-locate" title="Use My Current Location"><i class="fas fa-location-arrow"></i></button>
                                    </form>
                                    <ul data-simplebar data-simplebar-auto-hide="false" class="list-city">

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="close-rapid-modal-btn">
                <button class="c-btn c-btn-primary c-btn-block c-btn-semi-smooth" type="button">Close</button>
            </div>
        </div>

        <section class="page-content">