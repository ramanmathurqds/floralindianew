<?php

    if(isset($data->results->error)) {
        header("Location:".DOMAIN);
    }

    $_SESSION['ProductIamge'] = $data->results->ProductImage;
    $_SESSION['ProductName'] = $data->results->ProductName;
    $_SESSION['Price'] = $data->results->Price;
    $_SESSION['Mrp'] = $data->results->Mrp;

    $datetime = new DateTime("now", new DateTimeZone("Asia/Kolkata"));
?>

<link rel="stylesheet" href="<?=DOMAIN?>Content/assets/css/lib/xzoom.css" type="text/css" />

<div class="product-page child-page">
    <div class="offer-banner d-none">
        <img src="<?=DOMAIN?>Content/assets/images/banners/offer.jpg" class="img-100" />
    </div>

<?php if(isset($data->results)) {?>
<div class="container custom-container">
    <div class="breadcrumbs">
        <ul>
            <li><a href="<?=rtrim(DOMAIN, '/');?>">Home</a></li>
            <?php 
                $CategoryName = $data->results->CategoryName;
            ?>
            <li><a href="<?=DOMAIN?><?=$countryCode?>/<?=$CategoryName?>/<?=$ProductCategoryID?>"><?=$CategoryName?></a></li>
            <li><a><?=$data->results->ProductName?></a></li>
        </ul>
    </div>

    <div class="product-stage">

        <?php
            $uid = isset($_SESSION["uid"]) ? $_SESSION["uid"] : '0';
        ?>

        <input type="hidden" id="ProductID" value="<?=$data->results->ProductID?>" />
        <input type="hidden" id="ProductCategoryID" value="<?=$data->results->ProductCategoryID?>" />
        <input type="hidden" id="ChildProductCategoryID" value="<?=$ProductCategoryID?>" />
        <input type="hidden" id="CakeType" value="<?=$data->results->ProductType?>" />
        <input type="hidden" id="uID" value="<?=$uid?>" />
        <input type="hidden" id="selectedWatermark" value="<?=DOMAIN?><?=$data->results->WaterMarkImage?>">
        <input type="hidden" id="currentTime" value="<?= $datetime->format( 'H.i' )?>" />

        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="product-image-section">
                    <div class="main-image">
                        <?php if($data->results->IsVideo === '1'){ ?>
                            <video id="productVideo" controls autoplay="true" style="width:100%">
                                <source src="<?=$data->results->ProductImage?>" type="video/mp4">
                                Your browser does not support HTML video.
                            </video>
                        <?php } else { ?>
                            <img src="<?=DOMAIN?><?=$data->results->ProductImage?>" xoriginal="<?=DOMAIN?><?=$data->results->ProductImage?>" class="img-100 img_awesome xzoom4" alt="<?=$data->results->ProductName?>" />
                        <?php } ?>
                    </div>

                    <div class="img-review-share">
                        <div class="row">
                            <div class="col-6">
                                <div class="rating-box">
                                    <?php
                                        $ratingCount = ($data->results->ProductRatingCount > 0) ? $data->results->ProductRatingCount : 0;
                                        $productRating = isset($data->results->ProductRating) ? $data->results->ProductRating : 0;
                                        $ratingStars = ($ratingCount > 0) ? $productRating : 0;
                                    ?>
                                    <span>(<?=$ratingCount?> reviews)</span>
                                    <span data-avg-rating="<?=round($ratingStars)?>" class="d-none d-lg-inline-block avg-rating">
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                        <i class="far fa-star"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="sharebox text-right" data-toggle="popover" data-trigger="click" data-html="true" data-content="<div class='popover-content pop-social-platform'><p><span class='share-caption'>share</span><a title='facebook' href='https://www.facebook.com/sharer.php?u=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fab fa-facebook'></i></a> <a title='whatsapp' href='https://api.whatsapp.com/send?text=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fab fa-whatsapp'></i></a> <a title='pinterest' href='http://pinterest.com/pin/create/button/?url=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fab fa-pinterest'></i></a> <a title='email' class='email-share' href='mailto:<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>&subject=Floral India - <?=$data->results->ProductName?>&body=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fa fa-envelope'></i></a></p></div>">
                                    Share with your dear ones <span class="share-trigger"><i data-toggle="tooltip" data-html="true" title="<h3>Title placeholder</h3>" class="fas fa-share-alt"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="other-image xzoom-thumbs">
                        <?php

                            $cc = 0;
                            if(!isset($moreImages->results->error)) {
                            foreach($moreImages->results as $key => $val) {
                        ?>
                            <?php
                                if ($cc === 0){              
                            ?>
                                 <?php if($val->IsVideo === '1'){ ?>
                                    <a href="<?=DOMAIN?><?=$data->results->ProductImage?>">
                                        <img id="thumbImgDefault" class="xzoom-gallery4" src="<?=DOMAIN?>Content/assets/images/common/play-button.png" style="object-fit:cover" />
                                    </a>
                                <?php } else{ ?>
                                    <a href="<?=DOMAIN?><?=$data->results->ProductImage?>">
                                        <img id="thumbImgDefault" class="xzoom-gallery4" src="<?=DOMAIN?><?=$data->results->ProductImage?>" xpreview="<?=DOMAIN?><?=$data->results->ProductImage?>" />
                                    </a>
                                <?php } ?>
                            <?php } ?>

                            <?php if($val->IsVideo === '1'){ ?>
                                <a href="<?=$val->ImageUrl?>" class="load-video">
                                    <img id="thumbImgDefault-<?=$cc?>" class="xzoom-gallery4" src="<?=DOMAIN?>Content/assets/images/common/play-button.png" xpreview="<?=$val->ImageUrl?>" style="object-fit:cover" />
                                </a>
                            <?php } else{ ?>
                                <a href="<?=$val->ImageUrl?>" class="load-image">
                                    <img id="thumbImgDefault-<?=$cc?>" class="xzoom-gallery4" src="<?=$val->ImageUrl?>" xpreview="<?=$val->ImageUrl?>" />
                                </a>
                            <?php } ?>
                        <?php $cc++; }} ?>

                            <!-- <a href="../Content/assets/mov_bbb.mp4">
                                <video width="400" controls>
                                    <source src="../Content/assets/mov_bbb.mp4" type="video/mp4">
                                    Your browser does not support HTML video.
                                </video>
                            </a> -->
                    </div>
                </div>
            </div>

        <div class="col-12 col-lg-6">
            <input type="hidden" id="staticFinalPrice" value="<?=$data->results->Price?>" />
            <form autocomplete="off">
                <div class="product-info-section">
                    <input type="hidden" id="_flash_1454_11"/>
                    <input type="hidden" id="_flash_1454_12"/>
                    <input type="hidden" id="_flash_1454_13"/>

                    <span class="min-delivery-day d-none"><?=$data->results->MinDeliveryDay?></span>
                    <h1 class="product-title"><?=$data->results->ProductName?></h1>
                    <div class="product-code">PRODUCT CODE - <?=$data->results->ProductCode?></div>
                    <p class="more-desc"><?=$data->results->ProductShortDescription?>...<a href="#tab2" href="javascript:void(0)" class="primary-dark pointer">Read more</a></p>

                    <div class="action-panel">
                    <div class="row">
                            <div class="col-6 custom-grid">
                                <section class="pricing">
                                    <h2>
                                        <input type="hidden" id="_flash_m_final" value="<?=$data->results->Mrp?>"/>
                                        <input type="hidden" id="_flash_p_final" value="<?=$data->results->Price?>"/>
                                        <span class="selling-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <span class="final-price setCurrBasedPrice"><?=$data->results->Price?></span></span> &nbsp; <span class="mrp-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <span class="final-mrp-price setCurrBasedPrice"><?=$data->results->Mrp?></span></span></h2>
                                </section>
                            </div>

                            <div class="col-6 custom-grid text-right delivery-city">
                                <i class="fas fa-map-marker-alt"></i>&nbsp;<span class="d-none d-lg-inline">This product delivering to</span> <span class="selected-city"><?=$cityName?></span>
                            </div>
                        </div>

                        <div class="date-box mt-common">
                            <div class="row mb-15">
                                <div class="col-12 col-lg-6">
                                    <div class="super-title">Date and Time of delivery</div>
                                </div>

                                <div class="col-12 col-lg-6 text-right">
                                    <div class="today-timer d-none d-lg-block"><span class="countdown-timer"></span></div>
                                </div>
                            </div>

                            <div class="date-tile">
                                <div class="row">
                                    <?php 
                                        $oneDayDelivery = ($data->results->IsOneDayDelivery == 1) ? "true" : "false";
                                    ?>
                                    <input type="hidden" id="isTodayDelivery" value="<?=$oneDayDelivery?>" />
                                    <input type="hidden" id="selectedDeliveryDate" />
                                    <input type="hidden" id="selectedDeliveryMaxTime" />
                                    <input type="hidden" id="selectedDeliveryTime" />

                                    <div class="col-3 custom-grid auto-date">
                                        <input type="radio" class="d-none date-select" id="dt1" data-date="0" name="date-select" />
                                        <label for="dt1" class="item-tile">
                                            <div class="item-tile-inner">
                                                <span class="d-day"></span><br />
                                                <span class="d-month"></span> <span class="d-date"></span>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="col-3 custom-grid auto-date">
                                        <input type="radio" class="d-none date-select" id="dt2" data-date="0" name="date-select" />
                                        <label for="dt2" class="item-tile">
                                            <div class="item-tile-inner">
                                                <span class="d-day"></span><br />
                                                <span class="d-month"></span> <span class="d-date"></span>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="col-3 custom-grid auto-date">
                                        <input type="radio" class="d-none date-select" id="dt3" data-date="0" name="date-select" />
                                        <label for="dt3" class="item-tile">
                                            <div class="item-tile-inner">
                                                <span class="d-day"></span><br />
                                                <span class="d-month"></span> <span class="d-date"></span>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="col-3 custom-grid">
                                        <label for="jsDatePicker" class="item-tile has-datepicker">
                                            <div class="item-tile-inner">
                                                <div class="selected d-none">
                                                    <img src="<?=DOMAIN?>Content/assets/images/common/calendar-26.png" />
                                                    <span class="d-day"></span><br />
                                                    <span class="d-month"></span> <span class="d-date"></span>
                                                </div>

                                                <div class="unselected">
                                                    <img src="<?=DOMAIN?>Content/assets/images/common/calendar-26.png" />
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="col-12 custom-grid datepicker-input text-right">
                                        <input type="text" class="js-picker opacity-zero" id="jsDatePicker" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row time-slot mt-15 d-none">
                            <div class="col-12 custom-grid">
                                <div>
                                    <section class="info-input selected-prefilled2 prefilled-bg2">
                                        <div class="selected-prefilled prefilled-bg">
                                            <img src="<?=DOMAIN?>Content/assets/images/common/clock.svg" class="sml-icons" alt="delivery time" />
                                        </div>

                                        <?php 
                                            if(isset($timeSlot->results)) {
                                        ?>
                                        <div class="input-box">
                                            <select class="jq-drop drop-time-slot">
                                                <option>Select delivery time</option>
                                                <?php 
                                                    foreach($timeSlot->results as $key => $val) {
                                                ?>
                                                    <option data-charges="<?=$val->Charges?>" value="<?=$val->DeliveryID?>"><?=$val->DeliveryName?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <?php } ?>

                                        <div class="clearfix"></div>
                                    </section>
                                </div>
                            </div>
                        </div>

                        <?php if($data->results->ProductCategoryID === '91') { ?>

                            <?php if (isset($data->results->Filters) && !empty($data->results->Filters->SizeAvailability)) { ?>
                                <div class="size-options mt-common">
                                    <div class="row mb-15">
                                        <div class="col-12">
                                            <div class="super-title">Size Availability</div>
                                        </div>
                                    </div>
                                    <div class="size-tile">
                                        <div class="row">
                                        <input type="hidden" id="selectedSize" />
                                        <?php
                                        $c = 0;
                                        $d = 0;
                                        foreach ($data->results->Filters->SizeAvailability as $key => $val) {
                                            if($val->FilterName === 'Size Availability') {
                                                $d++;
                                                $c++;
                                                if($c === 1) {
                                                    $checked = 'checked';
                                                } else {
                                                    $checked = '';
                                                }
                                                if($c > 3) {
                                                    break;
                                                }
                                        ?>
                                        <div class="col-4 custom-grid size-box" data-active="<?=$val->Active?>">
                                            <input type="radio" class="d-none size-select" id="size<?=$d?>" data-value="<?=$val->FilterValue?>" catFilterID="<?=$val->CategoryFilterID?>" name="size-select" />

                                            <label for="size<?=$d?>" class="item-tile">
                                                <?php if($val->XFactor !== null){ ?>
                                                    <span class="x-factor"><?=$val->XFactor?></span>
                                                <?php } ?>
                                                <img src="<?=DOMAIN?>Content/assets/images/common/sizes/<?=$val->FilterValue?>-1.png" data-selected="<?=DOMAIN?>Content/assets/images/common/sizes/<?=$val->FilterValue?>-2.png" data-unselected="<?=DOMAIN?>Content/assets/images/common/sizes/<?=$val->FilterValue?>-1.png" alt="product-size-image" />
                                            </label>
                                            <div class="size-info">
                                                <span class="size-name"><?=$val->FilterValue?></span>
                                                <span class="c-info" data-toggle="tooltip" data-placement="bottom" title="<?=$val->SizeInfo?>">i</span>
                                                <br /><span class="webCurrency">
                                                    <span class="setDefaultCurrency"><?=$CurrencyLogo?></span>
                                                </span>&nbsp;
                                                </span>&nbsp;+<span class="size-price setCurrBasedPrice"><?=$val->FilterPrice?></span>
                                                </span>&nbsp;<span class="size-price-actual d-none"><?=$val->FilterPrice?></span>
                                            </div>
                                        </div>
                                        <?php }} ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="row">
                                <?php if (isset($data->results->Filters) && !empty($data->results->Filters->Packing)) { ?>
                                    <div class="col-12 custom-grid">
                                        <section class="info-input mt-common">
                                            <div class="selected-prefilled prefilled-bg">
                                                Packing
                                            </div>

                                            <div class="input-box">
                                                <select class="jq-drop drop-packing">
                                                    <option value="">Select Packing</option>

                                                    <?php
                                                        foreach ($data->results->Filters->Packing as $key => $val) {
                                                        if($val->FilterName === 'Packing') {
                                                    ?>
                                                        <option value="<?=$val->FilterValue?>" catFilterID="<?=$val->CategoryFilterID?>">
                                                            <?=$val->FilterValue?> (<span class="webCurrency">
                                                                <span class="setDefaultCurrency"><?=$CurrencyLogo?></span>
                                                            </span>&nbsp;<span class="setCurrBasedPrice"><?=$val->FilterPrice?></span>)
                                                        </option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                            <div class="clearfix"></div>
                                        </section>
                                    </div>
                                <?php } ?>
                                </div>

                                <div class="row">
                                <?php if (isset($data->results->Filters) && !empty($data->results->Filters->Colour)) { ?>
                                <div class="col-12 col-xl-9 custom-grid">
                                    <section class="color-selector-wrapper">
                                        <div class="super-title">Choose Colour</div>
                                        <div class="color-plots">

                                        <?php
                                        $c = 0;
                                        foreach ($data->results->Filters->Colour as $key => $val) {
                                            $c++;
                                            if($val->FilterName === 'Colour') {
                                            if($c === 1) {
                                                $checked = 'checked';
                                            } else {
                                                $checked = '';
                                            }
                                        ?>
                                            <div class="plots">
                                                <input id="cl-<?=$val->FilterValue?>" value="<?=$val->FilterValue?>" type="radio" class="color-selector" name="colorSelection" <?=$checked?> />
                                                <label class="<?=strtolower($val->FilterValue)?>" for="cl-<?=$val->FilterValue?>"></label>
                                            </div>
                                        <?php }} ?>
                                        </div>
                                    </section>
                                </div>
                                <?php } ?>

                                <div class="col-12 col-xl-2 custom-grid">
                                    <section>
                                        <div class="row mb-15">
                                            <div class="col-12"><div class="super-title text-uppercase">QTY</div></div>
                                        </div>
                                        <div class="qty-box">
                                            <!--<div class="q-field"><button class="minus"><i class="fas fa-minus"></i></button></div>
                                            <div class="q-field">
                                                <input type="text" name="QTY" class="txt-qty" value="1" />
                                            </div>
                                            <div class="q-field"><button class="plus"><i class="fas fa-plus"></i></button></div>-->
                                            <select class="txt-qty form-control" name="QTY">
                                                <option value="1">1</option>
                                                <option value="2">2</option>  
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($data->results->ProductCategoryID === '92') { ?>

                            <?php if (isset($data->results->Filters) && !empty($data->results->Filters->SizeAvailability)) { ?>

                            <div class="size-options mt-common">
                                <div class="row mb-15">
                                    <div class="col-12">
                                        <div class="super-title">Size Availability</div>
                                    </div>
                                </div>
                                <div class="size-tile">
                                    <div class="row">
                                    <input type="hidden" id="selectedSize" />

                                    <?php
                                    $i = 0;
                                    $c = 0;
                                    foreach ($data->results->Filters->SizeAvailability as $key => $val) {
                                        if($val->FilterName === 'Size Availability') {
                                        $i++;
                                            $c++;
                                            if($c === 1) {
                                                $checked = 'checked';
                                            } else {
                                                $checked = '';
                                            }
                                    ?>
                                        <div class="col-4 custom-grid size-box">

                                            <input type="radio" class="d-none size-select" id="size<?=$c?>" data-value="<?=$val->FilterValue?>" catFilterID="<?=$val->CategoryFilterID?>" name="size-select" />

                                            <label for="size<?=$c?>" class="item-tile">
                                                <img src="<?=DOMAIN?>/Content/assets/images/common/sizes/cake<?=$c?>-silver.png" data-selected="<?=DOMAIN?>Content/assets/images/common/sizes/cake<?=$c?>-pink.png" data-unselected="<?=DOMAIN?>Content/assets/images/common/sizes/cake<?=$c?>-silver.png" />
                                            </label>
                                            <div class="size-info">
                                                <span class="size-name"><?=$val->FilterValue?></span>
                                                <span class="c-info" data-toggle="tooltip" data-placement="bottom" title="<?=$val->SizeInfo?>">i</span>
                                                <br /><span class="webCurrency">
                                                    <span class="setDefaultCurrency"><?=$CurrencyLogo?></span>
                                                </span>&nbsp;+<span class="size-price setCurrBasedPrice"><?=$val->FilterPrice?></span>
                                                </span>&nbsp;<span class="size-price-actual d-none"><?=$val->FilterPrice?></span>
                                            </div>
                                        </div>
                                    <?php }} ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="row">

                            <?php if (isset($data->results->Filters) && !empty($data->results->Filters->Baking)) {?>
                                <div class="col-12 custom-grid">
                                    <div class="row mb-15">
                                        <div class="col-12">
                                            <div class="super-title">Baking</div>
                                        </div>
                                    </div>
                                    <div class="custom-checkbox">
                                        <ul class="baking-list row" data-eggless-src="<img src='<?=DOMAIN?>Content/assets/images/common/eggless.png' style='width: 20px;position: relative;left: 5px;top: -3px;'>">
                                            <?php 
                                                $a = 0;
                                                foreach ($data->results->Filters->Baking as $key => $val) {
                                                    if($val->FilterName === 'Baking') {
                                                        $a++;
                                                        if($a === 1) {
                                                            $checked = 'checked';
                                                        } else {
                                                            $checked = '';
                                                        }
                                            ?>
                                                <li class="col-3">
                                                    <input id="chk-<?=$val->FilterValue?>" <?=$checked?> type="checkbox" value="<?=$val->FilterValue?>">
                                                    <label for="chk-<?=$val->FilterValue?>"><?=$val->FilterValue?></label>
                                                </li>
                                            <?php }} ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>


                            <?php if (isset($data->results->Filters) && !empty($data->results->Filters->Flavour)) { ?>
                                <div class="col-12 custom-grid">
                                    <section class="info-input mt-3">
                                        <div class="selected-prefilled prefilled-bg">
                                            Flavour
                                        </div>

                                        <div class="input-box">
                                            <select class="jq-drop">
                                                <option value="">Select Flavour</option>

                                                <?php
                                                    foreach ($data->results->Filters->Flavour as $key => $val) {
                                                        if($val->FilterName === 'Flavour') {
                                                ?>
                                                    <option value="<?=$val->FilterValue?>"><?=$val->FilterValue?></option>
                                                <?php }} ?>

                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                    </section>
                                </div>
                            <?php } ?>

                            <div class="col-12 col-xl-10 custom-grid mt-3">
                                <div class="row mb-15">
                                    <div class="col-12">
                                        <div class="super-title">Enter message for cake</div>
                                    </div>
                                </div>

                                <section class="info-input selected-prefilled prefilled-bg">
                                    <div class="selected-prefilled prefilled-bg">
                                        <i class="fas fa-envelope"></i>
                                    </div>

                                    <div class="input-box">
                                        <input type="text" class="txt-location" placeholder="Caption on cake (upto 25 characters)" />
                                    </div>
                                    <div class="clearfix"></div>
                                </section>
                            </div>

                            <div class="col-12 col-xl-2 custom-grid mt-3">
                                <div class="row mb-15">
                                    <div class="col-12">
                                        <div class="super-title">QTY</div>
                                    </div>
                                </div>
                                <div class="qty-box">
                                    <!--<div class="q-field"><button class="minus"><i class="fas fa-minus"></i></button></div>
                                    <div class="q-field">
                                        <input type="text" name="QTY" class="txt-qty" value="1" />
                                    </div>
                                    <div class="q-field"><button class="plus"><i class="fas fa-plus"></i></button></div>-->
                                    <select class="txt-qty form-control" name="QTY">
                                        <option value="1">1</option>
                                        <option value="2">2</option>  
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>
                            </div>

                            <?php if($data->results->ProductType === "Digit Cake") { ?>
                                <div class="col-12 col-lg-4 custom-grid" id="digit-cake">
                                    <section class="info-input mt-3" style="border:none">
                                        <div class="input-box dropper" style="width:100%;">
                                            <select class="jq-drop">
                                                <option value="">Select Digit</option>
                                                <?php for($i=0; $i<=100; $i++) { ?>
                                                    <option value="0"><?=$i?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </section>
                                </div>
                            <?php } ?>

                            <?php if($data->results->ProductType === "Photo Cake") { ?>
                                <div class="col-12 col-lg-4 custom-grid" id="photo-cake">
                                    <section class="info-input mt-3" style="border:none">
                                        <div class="input-box dropper" style="width:100%;">
                                            <label for="CakePhotoFile" class="select-btn text-center">
                                                <i class="fas fa-cloud-upload-alt"></i> Choose Image
                                            </label>
                                            <div class="small-text">Max 5MB size Ext (png, jpg)</div>
                                            <input type="file" id="CakePhotoFile" name="CakePhotoFile" class="d-none" />
                                            <div class="photoImg" style="display:none;"><img src="" alt="photo Image" /></div>
                                            <input type="hidden" id="CakePhotoFileInput" />
                                        </div>
                                    </section>
                                </div>
                            <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if($data->results->ProductCategoryID === '94') { ?>
                            <?php if (isset($data->results->Filters) && !empty($data->results->Filters->SizeOfBoquet)) { ?>

                            <div class="size-options mt-common">
                                <div class="super-title">Size Availability</div>
                                <div class="size-tile">
                                    <div class="row">
                                    <input type="hidden" id="selectedSize" />
                                    <?php
                                    $c = 0;
                                    $d = 0;
                                    foreach ($data->results->Filters->SizeOfBoquet as $key => $val) {
                                        if($val->FilterName === 'Size Of Boquet') {
                                            $d++;
                                            $c++;
                                            if($c === 1) {
                                                $checked = 'checked';
                                            } else {
                                                $checked = '';
                                            }
                                            if($c > 3) {
                                                break;
                                            }
                                    ?>
                                    <div class="col-4 custom-grid size-box" data-active="<?=$val->Active?>">
                                        <input type="radio" class="d-none size-select" id="size<?=$d?>" data-value="<?=$val->FilterValue?>" catFilterID="<?=$val->CategoryFilterID?>" name="size-select" />

                                        <label for="size<?=$d?>" class="item-tile">
                                            <?php if($val->XFactor !== null){ ?>
                                                <span class="x-factor"><?=$val->XFactor?></span>
                                            <?php } ?>
                                            <img src="<?=DOMAIN?>/Content/assets/images/common/sizes/<?=$val->FilterValue?>-ballon-1.png" data-selected="<?=DOMAIN?>Content/assets/images/common/sizes/<?=$val->FilterValue?>-ballon-2.png" data-unselected="<?=DOMAIN?>Content/assets/images/common/sizes/<?=$val->FilterValue?>-ballon-1.png" alt="product-size-image"/>
                                        </label>
                                        <div class="size-info">
                                            <span class="size-name"><?=$val->FilterValue?></span>
                                            <span class="c-info" data-toggle="tooltip" data-placement="bottom" title="<?=$val->SizeInfo?>">i</span>
                                            <br /><span class="webCurrency">
                                                <span class="setDefaultCurrency"><?=$CurrencyLogo?></span>
                                            </span>&nbsp;+<span class="size-price setCurrBasedPrice"><?=$val->FilterPrice?></span>
                                            </span>&nbsp;<span class="size-price-actual d-none"><?=$val->FilterPrice?></span>
                                        </div>
                                    </div>
                                    <?php }} ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="row">
                                <?php if (isset($data->results->Filters) && !empty($data->results->Filters->Colour)) { ?>
                                    <div class="col-12 col-xl-9 custom-grid">
                                        <section class="color-selector-wrapper">
                                            <div class="super-title">Choose Colour</div>
                                            <div class="color-plots">
                                                <?php
                                                    foreach ($data->results->Filters->Colour as $key => $val) {
                                                        if($val->FilterName === 'Colour') {
                                                ?>
                                                    <div class="plots">
                                                        <input id="cl-<?=$val->FilterValue?>" value="<?=$val->FilterValue?>" type="radio" class="color-selector" name="colorSelection" />
                                                        <label class="<?=strtolower($val->FilterValue)?>" for="cl-<?=$val->FilterValue?>"></label>
                                                    </div>
                                                <?php }} ?>
                                            </div>
                                        </section>
                                    </div>
                                <?php } ?>

                                <div class="col-12 col-xl-2 custom-grid">
                                    <section>
                                        <div class="super-title text-uppercase">QTY</div>
                                        <div class="qty-box">
                                            <!--<div class="q-field"><button class="minus"><i class="fas fa-minus"></i></button></div>
                                            <div class="q-field">
                                                <input type="text" name="QTY" class="txt-qty" value="1" />
                                            </div>
                                            <div class="q-field"><button class="plus"><i class="fas fa-plus"></i></button></div>-->
                                            <select class="txt-qty form-control" name="QTY">
                                                <option value="1">1</option>
                                                <option value="2">2</option>  
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                        </div>
                                    </section>
                                </div>
                            </div>

                            <div class="row">                        
                                <div class="col-12 col-lg-8 custom-grid mt-3">
                                    <div class="super-title text-uppercase">Enter Your Message</div>
                                    <section class="info-input selected-prefilled prefilled-bg">
                                        <div class="selected-prefilled prefilled-bg">
                                            <i class="fas fa-envelope"></i>
                                        </div>

                                        <div class="input-box">
                                            <select class="jq-drop">
                                                <option value="">Select</option>
                                                <option value="1">Happy Birthday</option>
                                                <option value="2">1</option>
                                                <option value="3">2</option>
                                                <option value="4">3</option>
                                                <option value="5">4</option>
                                                <option value="6">5</option>
                                                <option value="7">6</option>
                                                <option value="8">7</option>
                                                <option value="9">8</option>
                                                <option value="10">9</option>
                                            </select>
                                        </div>
                                        <div class="clearfix"></div>
                                    </section>
                                </div>

                                <?php if (isset($data->results->Filters) && !empty($data->results->Filters->BalloonType)) { ?>
                                    <div class="col-12 col-lg-4 custom-grid mt-3">
                                        <div class="super-title text-uppercase">Select Digit</div>
                                        <section class="info-input" style="border:none">
                                            <div class="input-box dropper" style="width:100%;">
                                                <select class="jq-drop">
                                                    <option value="">Select Digit</option>
                                                    <?php for($i=0; $i<=100; $i++) { ?>
                                                        <option value="0"><?=$i?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </section>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if($data->results->ProductCategoryID === '97') { ?>
                            <?php if (isset($data->results->Filters) && !empty($data->results->Filters->SizeAvailability)) { ?>
                                <div class="size-options mt-common">
                                    <div class="row mb-15">
                                        <div class="col-12">
                                            <div class="super-title">Size Availability</div>
                                        </div>
                                    </div>
                                    <div class="size-tile">
                                        <div class="row">
                                        <input type="hidden" id="selectedSize" />
                                        <?php
                                        $c = 0;
                                        $d = 0;
                                        foreach ($data->results->Filters->SizeAvailability as $key => $val) {
                                            if($val->FilterName === 'Size Availability') {
                                                $d++;
                                                $c++;
                                                if($c === 1) {
                                                    $checked = 'checked';
                                                } else {
                                                    $checked = '';
                                                }
                                                if($c > 3) {
                                                    break;
                                                }
                                        ?>
                                        <div class="col-4 custom-grid size-box" data-active="<?=$val->Active?>">
                                            <input type="radio" class="d-none size-select" id="size<?=$d?>" data-value="<?=$val->FilterValue?>" catFilterID="<?=$val->CategoryFilterID?>" name="size-select" />

                                            <label for="size<?=$d?>" class="item-tile">
                                                <?php if($val->XFactor !== null){ ?>
                                                    <span class="x-factor"><?=$val->XFactor?></span>
                                                <?php } ?>
                                                <img src="<?=DOMAIN?>Content/assets/images/common/sizes/<?=$val->FilterValue?>-1.png" data-selected="<?=DOMAIN?>Content/assets/images/common/sizes/<?=$val->FilterValue?>-2.png" data-unselected="<?=DOMAIN?>Content/assets/images/common/sizes/<?=$val->FilterValue?>-1.png" alt="product-size-image" />
                                            </label>
                                            <div class="size-info">
                                                <span class="size-name"><?=$val->FilterValue?></span>
                                                <span class="c-info" data-toggle="tooltip" data-placement="bottom" title="<?=$val->SizeInfo?>">i</span>
                                                <br /><span class="webCurrency">
                                                    <span class="setDefaultCurrency"><?=$CurrencyLogo?></span>
                                                </span>&nbsp;
                                                </span>&nbsp;+<span class="size-price setCurrBasedPrice"><?=$val->FilterPrice?></span>
                                                </span>&nbsp;<span class="size-price-actual d-none"><?=$val->FilterPrice?></span>
                                            </div>
                                        </div>
                                        <?php }} ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <div class="row">
                                <div class="col-12 col-lg-8 col-xl-9 custom-grid mt-3">
                                    <div class="super-title text-uppercase">Set your own Price <span class="c-info" data-toggle="tooltip" data-placement="top" title="Custom price text">i</span></div>

                                    <div class="custom-handle-slider">
                                        <span data-min="300" class="min-value"></span>
                                        <div id="cSlider">
                                            <div id="custom-handle" class="ui-slider-handle"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-2 custom-grid mt-3">
                                    <div class="super-title text-uppercase">QTY</div>
                                    <div class="qty-box">
                                        <!--<div class="q-field"><button class="minus"><i class="fas fa-minus"></i></button></div>
                                        <div class="q-field">
                                            <input type="text" name="QTY" class="txt-qty" value="1" />
                                        </div>
                                        <div class="q-field"><button class="plus"><i class="fas fa-plus"></i></button></div>-->
                                        <select class="txt-qty form-control" name="QTY">
                                            <option value="1">1</option>
                                            <option value="2">2</option>  
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row common-size-box" style="display:none">
                            <div class="col-12 col-lg-2 custom-grid mt-3">
                                <div class="row mb-15">
                                    <div class="col-12">
                                        <div class="super-title text-uppercase">QTY</div>
                                    </div>
                                </div>
                                <div class="qty-box">
                                    <select class="txt-qty form-control" name="QTY">
                                        <option value="1">1</option>
                                        <option value="2">2</option>  
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 custom-grid mb-3">
                                <a data-toggle="modal" data-target="#giftMessage" class="btn-gift-message" data-title="Click here to add Gift message"><span>Click here to add Gift message</span> <i class="fa fa-angle-right"></i></a>
                            </div>

                            <div class="col-12 col-lg-6 custom-grid cart-button">
                                <button type="button" class="c-btn c-btn-block c-btn-primary text-uppercase btn-add-cart">Add to cart</button>
                            </div>

                            <div class="col-12 col-lg-6 custom-grid cart-button">
                                <a href="<?=DOMAIN?>checkout" class="c-btn c-btn-block c-btn-secondary text-uppercase btn-express-checkout">Express Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

            <div class="col-12 clearfix">
                <div class="tabbed-content">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab1">Similar Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab2">Description</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab3">Inclusion</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab4">Substitution</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab5">Delivery</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab6">Reviews</a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div style="border:1px solid #ccc; border-top:none" class="tab-content">
                        <div id="tab1" class="tab-pane active">
                            <br>
                            <?php if(isset($data->results->SimilarProducts)) { ?>
                            <h3 class="text-uppercase">You May Also Like This</h3>
                            <hr />
                            <div class="similar">
                                <div class="row">
                                <?php
                                    foreach ($data->results->SimilarProducts as $key => $val) {
                                        if($val->ActiveWishList == '1') {
                                            $active = 'active';
                                            $title = 'Remove from wishlist';
                                            $heart = 'fas';
                                        } else{
                                            $active = 'inactive';
                                            $title = 'Add to wishlist';
                                            $heart = 'far';
                                        }
                                ?>
                                    <div class="col-6 col-lg-4 col-xl-3 custom-grid-device mb-4">
                                        <div class="product-block <?=$active?>" id="product-<?=$val->ProductID?>">
                                            <a href="<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$val->ProductName))?>/<?=$ProductCategoryID?>/<?=$val->ProductID?>">
                                                <img src="<?=DOMAIN?><?=$val->ProductImage?>" class="product-img" alt="cake">
                                                <div class="product-info text-center">
                                                    <div class="product-title">
                                                        <p><?=$val->ProductName?></p>
                                                        <span><?=$val->ProductShortDescription?></span>
                                                    </div>

                                                    <div class="product-price">
                                                        <p>
                                                            <span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <span class="setCurrBasedPrice"><?=$val->Price?></span> &nbsp;&nbsp;
                                                            <span class="cancelled-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <span class="setCurrBasedPrice"><?=$val->Mrp?></span></span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </a>

                                            <div class="product-action">
                                                <table class="extras">
                                                    <tr>
                                                        <td>
                                                        <?php 
                                                            $ratingCount = ($val->ProductRatingCount > 0) ? $val->ProductRatingCount : 0;
                                                            $productRating = isset($val->ProductRating) ? $val->ProductRating : 0;
                                                            $ratingStars = ($ratingCount > 0) ? $productRating / $ratingCount : 0;
                                                        ?>
                                                            <span class="nowrap">(<?=$ratingCount?> reviews)</span>
                                                            <span data-avg-rating="<?=round($ratingStars)?>" class="d-none d-lg-inline-block avg-rating">
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                            </span>
                                                        </td>

                                                        <td data-toggle="popover" data-trigger="click" data-html="true" data-content="<div class='popover-content pop-social-platform'><p><span class='share-caption'>share</span><a title='facebook' href='https://www.facebook.com/sharer.php?u=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fab fa-facebook'></i></a> <a title='whatsapp' href='https://api.whatsapp.com/send?text=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fab fa-whatsapp'></i></a> <a title='pinterest' href='http://pinterest.com/pin/create/button/?url=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fab fa-pinterest'></i></a> <a title='email' class='email-share' href='mailto:<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>&subject=Floral India - <?=$data->results->ProductName?>&body=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fa fa-envelope'></i></a></p></div>">
                                                            <a>
                                                                <i class="fas fa-share-alt"></i>
                                                            </a>
                                                        </td>

                                                        <td class="d-none d-lg-table-cell">
                                                            <a title="View this product" href="<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$ProductCategoryID?>/<?=$value->ProductID?>"><i class="fas fa-eye"></i></a>
                                                        </td>

                                                        <td>
                                                            <a class="btn-fav whishlist" title="<?=$title?>">
                                                                <i class="<?=$heart?> fa-heart"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                            <?php } else { ?>
                                <h3 class="text-uppercase">No similar products found..!!</h3>
                            <?php } ?>
                        </div>

                        <div id="tab2" class="tab-pane fade">
                            <br>
                            <h3>Description</h3>
                            <hr />
                            <div class="mt-4">
                                <?=$data->results->ProductDescription?>
                            </div>
                        </div>

                        <div id="tab3" class="tab-pane fade">
                            <br>
                            <h3>Inclusion</h3>
                            <hr />
                            <div class="mt-4">
                                <?=$data->results->Inclusion?>
                            </div>
                        </div>

                        <div id="tab4" class="tab-pane fade">
                            <br>
                            <h3>Substitution</h3>
                            <hr />
                            <div class="mt-4">
                                <?=$data->results->Substitution?>
                            </div>
                        </div>

                        <div id="tab5" class="tab-pane fade">
                            <br>
                            <h3>Delivery</h3>
                            <hr />
                            <div class="mt-4">
                                <?=$data->results->DeliveryDescription?>
                            </div>
                        </div>

                        <div id="tab6" class="tab-pane fade">
                            <br>
                            <div class="review-form common-form">
                                <?php if(isset($_SESSION['uid'])) { ?>
                                    <h1 class="form-heading">Reviews</h1>

                                    <form action="" class="form formValidation" name="reviewForm" id="reviewForm" autocomplete="off" method="post" onsubmit="return false">
                                        <input type="hidden" name="ProductID" value="<?=$data->results->ProductID?>"/>

                                        <div class="c-form-control mt-4">
                                            <label class="lbl-field" for="Rating">Add your rating<span class="mandate">*</span></label>
                                            <!-- Rating Stars Box -->
                                            <div class='rating-stars'>
                                                <ul id='stars'>
                                                    <li class='star' title='Poor' data-value='1'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                    </li>
                                                    <li class='star' title='Fair' data-value='2'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                    </li>
                                                    <li class='star' title='Good' data-value='3'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                    </li>
                                                    <li class='star' title='Excellent' data-value='4'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                    </li>
                                                    <li class='star' title='WOW!!!' data-value='5'>
                                                        <i class='fa fa-star fa-fw'></i>
                                                    </li>

                                                    <input type="hidden" name="Rating" id="productRating" />
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="c-form-control mt-4">
                                            <label class="lbl-field" for="ReviewSubject">Subject<span class="mandate">*</span></label>
                                            <input type="text" id="ReviewSubject" name="ReviewSubject" class="custom-txt" />
                                        </div>

                                        <div class="c-form-control mt-4">
                                            <label class="lbl-field" for="ReviewMessage">Comment<span class="mandate">*</span></label>
                                            <textarea type="text" id="ReviewMessage" name="ReviewMessage" class="custom-txt"></textarea>
                                        </div>

                                        <div class="mt-4">
                                            <button type="button" class="c-btn c-btn-semi-compact c-btn-secondary text-uppercase btn-review">Submit</button>
                                        </div>
                                    </form>
                                <?php } else { ?>
                                    <h3> Login to your account to add your review and rating</h3>
                                    <button data-toggle="modal" data-target="#loginModal" class="c-btn c-btn-semi-compact c-btn-secondary text-uppercase btn-review">SIGN IN</button>
                                <?php } ?>
                            </div>

                            <?php if(isset($reviews->results) && !isset($reviews->results->error)) { ?>
                                <div class="comment-reviews mt-4">
                                    <h3>Comments</h3>
                                    <?php
                                        foreach ($reviews->results as $key => $val) {
                                    ?>
                                    <div class="card card-white post">
                                        <div class="post-heading">
                                            <div class="float-left image">
                                            <?php if(isset($userDetails->results->ProfileImage)) { ?>
                                                <img src="<?=DOMAIN?>Content/assets/images/userProfilePics/<?=$userDetails->results->ProfileImage?>" class="img-circle avatar" alt="user profile image">
                                            <?php } else { ?>
                                                <img src="<?=DOMAIN?>Content/assets/images/common/user_1.jpg" class="img-circle avatar" alt="user profile image">
                                            <?php } ?>
                                            </div>
                                            <div class="float-left meta">
                                                <div class="title h5">
                                                    <a href="javascript:void(0)"><strong><?=$val->UserName?></strong></a>
                                                </div>
                                                <h6 class="text-muted time"><?=$val->CreatedDate?></h6>
                                            </div>
                                        </div> 
                                        <div class="post-description">
                                            <p><?=$val->ReviewSubject?></p>
                                            <p><?=$val->ReviewMessage?></p>
                                        </div>
                                    <?php } ?>    
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 clearfix">
            <br/><br/>
                <?php if(isset($recentProducts->results)) { ?>
                    <h3 class="text-uppercase">Recently viewed products</h3>
                    <hr />
                    <div class="similar">
                        <div class="row">
                        <?php
                            $a = 0;
                            foreach ($recentProducts->results as $key => $val) {
                                $a++;
                                if($val->ActiveWishList == '1') {
                                    $active = 'active';
                                    $title = 'Remove from wishlist';
                                    $heart = 'fas';
                                } else{
                                    $active = 'inactive';
                                    $title = 'Add to wishlist';
                                    $heart = 'far';
                                }
                                if($a == 5) {
                                    break;
                                }
                        ?>
                            <div class="col-6 col-lg-4 col-xl-3 mb-4 custom-grid-device">
                                <div class="product-block <?=$active?>" id="product-<?=$val->ProductID?>">
                                    <a href="<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$val->ProductName))?>/<?=$val->ProductID?>/<?=$ProductCategoryID?>">
                                        <img src="<?=DOMAIN?><?=$val->ProductImage?>" class="product-img" alt="cake">
                                        <div class="product-info text-center">
                                            <div class="product-title">
                                                <p><?=$val->ProductName?></p>
                                                <span><?=$val->ProductShortDescription?></span>
                                            </div>

                                            <div class="product-price">
                                                <p>
                                                    <span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <span class="setCurrBasedPrice"><?=$val->Price?></span> &nbsp;&nbsp;
                                                    <span class="cancelled-price"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <span class="setCurrBasedPrice"><?=$val->Mrp?></span></span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>

                                    <div class="product-action">
                                        <table class="extras">
                                            <tr>
                                                <td>
                                                <?php 
                                                    $ratingCount = ($val->ProductRatingCount > 0) ? $val->ProductRatingCount : 0;
                                                    $productRating = isset($val->ProductRating) ? $val->ProductRating : 0;
                                                    $ratingStars = ($ratingCount > 0) ? $productRating / $ratingCount : 0;
                                                ?>
                                                    <span class="nowrap">(<?=$ratingCount?> reviews)</span>
                                                    <span data-avg-rating="<?=round($ratingStars)?>" class="d-none d-lg-inline-block avg-rating">
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </span>
                                                </td>

                                                <td data-toggle="popover" data-trigger="click" data-html="true" data-content="<div class='popover-content pop-social-platform'><p><span class='share-caption'>share</span><a title='facebook' href='https://www.facebook.com/sharer.php?u=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fab fa-facebook'></i></a> <a title='whatsapp' href='https://api.whatsapp.com/send?text=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fab fa-whatsapp'></i></a> <a title='pinterest' href='http://pinterest.com/pin/create/button/?url=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fab fa-pinterest'></i></a> <a title='email' class='email-share' href='mailto:<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>&subject=Floral India - <?=$data->results->ProductName?>&body=<?=DOMAIN_URL?>/<?=$countryCode?>/<?=$data->results->ProductName?>/<?=$data->results->ProductID?>/<?=$ProductCategoryID?>'><i class='fa fa-envelope'></i></a></p></div>">
                                                    <a>
                                                        <i class="fas fa-share-alt"></i>
                                                    </a>
                                                </td>

                                                <td class="d-none d-lg-table-cell">
                                                    <a title="View this product" href="<?=DOMAIN?><?=$countryCode?>/product/<?=strtolower(str_replace(" ","-",$value->ProductName))?>/<?=$ProductCategoryID?>/<?=$value->ProductID?>"><i class="fas fa-eye"></i></a>
                                                </td>

                                                <td>
                                                    <a class="btn-fav whishlist" title="<?=$title?>">
                                                        <i class="<?=$heart?> fa-heart"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <h3 class="text-uppercase">No similar products found..!!</h3>
                <?php } ?>
            </div>

            </div>
        </div>
    </div>
    <?php } ?>
</div>


<style>
.ui-widget-content .ui-state-active{
    background-color:transparent;
    color: #ee398a;
    border-color:transparent
}
</style>