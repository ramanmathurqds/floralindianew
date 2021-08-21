<?php 
if(isset($_SESSION['PromoDiscount']) && !empty($_SESSION['PromoDiscount'])) {
    unset($_SESSION['PromoDiscount']);}
?>
<div class="cart-page">
    <div class="container custom-container">
        <div class="breadcrumbs">
            <ul>
                <li><a href="<?=rtrim(DOMAIN, '/');?>">Home</a></li>
                <li><a href="javascript:;">Cart</a></li>
            </ul>
        </div>
        <?php if(isset($data->results) && !isset($data->results->error)) {
            $bagTotal = 0;
            $bagTotalAfterDiscount = 0;
            $bagDiscount = 0;
            $shippingCharge = 0;
            $packingCharge = 0;
        ?>
            <div class="cart-box">
                <div class="row">
                    <div class="col-12 col-xl-8 custom-grid">
                        <div class="prime-box">
                            <div class="prime-box-inner">
                                <section class="box-title common-ele-padding">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <?php
                                                if(count($data->results) === '1') {
                                                    $item = 'Item';
                                                } else {
                                                    $item = 'Items';
                                                }
                                            ?>

                                            <i><img src="<?=DOMAIN?>Content/assets/images/common/shopping-bag.png" alt="shopping-bag" /></i>
                                            <span class="spn-title">
                                                <span class="bold"><?=count($data->results)?> <?=$item?></span> <span class="color-grey-dark">in your bag</span>
                                            </span>
                                        </div>

                                        <div class="col-12 col-lg-6 text-lg-right">
                                            <span class="spn-title d-none">
                                                <span class="color-grey-dark">You will earn 
                                                    <span class="bold webCurrency primary-dark"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span>
                                                    <span class="bold floral-coin primary-dark">0</span> Floral cash. <a class="primary-dark" href="#">T&C</a></span>
                                            </span>
                                        </div>
                                    </div>
                                </section>
                                <div class="custom-saperator"></div>

                                <div class="cart-item-wrapper common-ele-padding">
                                    <?php
                                        $i = 0;
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
                                        <div class="cart-item" cart-id="<?=$val->CartID?>" id="Cart-<?=$val->CartID?>">
                                            <div class="row">
                                                <div class="col-12 col-xl-8">
                                                    <img src="<?=DOMAIN?><?=$val->ProductIamge?>" alt="<?=$val->ProductName?>" class="img-product" />
                                                    <h3 class="product-title"><?=$val->ProductName?></h3>
                                                    <p class="small-description">
                                                        <?php if($val->Type) {?>
                                                            <?=$val->Type?>
                                                        <?php } ?>
                                                        <?php if($val->Feature) {?>
                                                            <?php if($val->Feature || $val->Type ) { ?>
                                                            , 
                                                            <?php } ?>
                                                            <?=$val->Feature?>
                                                        <?php } ?>
                                                        <?php if($val->Size) {?>
                                                            <?php if($val->Feature || $val->Type ) { ?>
                                                                , 
                                                            <?php } ?>
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

                                                            <button class="cmn-field minus" <?=$disabledMinus?>><i class="fas fa-minus"></i></button>
                                                            <input type="text" id="Quantity" class="cmn-field txt-qty" data-index="<?=$i?>" value="<?=$val->ProductQty?>" />
                                                            <button class="cmn-field plus" <?=$disabledPlus?>><i class="fas fa-plus"></i></button>
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
                                                            <span class="discount color-secondary3"> (<?=number_format($discount)?>%)</span>
                                                        </span>
                                                        <span class="shipping-charge color-secondary3">
                                                            Shipping charges <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="shippingCharge setCurrBasedPrice"><?=$val->TimeSlotCharges?></span>
                                                        </span>

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
                                                <div class="cart-item" cart-id="<?=$value->CartID?>" id="Cart-<?=$value->CartID?>">
                                                    <div class="row">
                                                        <div class="addon-tag">Addon</div>

                                                        <div class="col-12 col-xl-8">
                                                            <img src="<?=DOMAIN?><?=$value->ProductIamge?>" alt="<?=$value->ProductName?>" class="img-product" />
                                                            <h3 class="product-title"><?=$value->ProductName?></h3>
                                                            <p class="small-description">
                                                                <?php if($value->Type) {?>
                                                                    <?=$value->Type?>
                                                                <?php } ?>
                                                                <?php if($value->Feature) {?>
                                                                    <?php if($value->Feature || $value->Type ) { ?>
                                                                    , 
                                                                    <?php } ?>
                                                                    <?=$value->Feature?>
                                                                <?php } ?>
                                                                <?php if($value->Size) {?>
                                                                    <?php if($value->Feature || $value->Type ) { ?>
                                                                        , 
                                                                    <?php } ?>
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

                                                                    <button class="cmn-field minus" <?=$disabledMinus?>><i class="fas fa-minus"></i></button>
                                                                    <input type="text" id="Quantity" class="cmn-field txt-qty" data-index="<?=$i?>" value="<?=$value->ProductQty?>" />
                                                                    <button class="cmn-field plus" <?=$disabledPlus?>><i class="fas fa-plus"></i></button>
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
                                                                    <span class="discount color-secondary3"> (<?=number_format($discount)?>%)</span>
                                                                </span>
                                                                <!-- <span class="shipping-charge color-secondary3">
                                                                    Shipping charges <span class="setDefaultCurrency"><?=$CurrencyLogo?></span><span class="shippingCharge setCurrBasedPrice"><?=$value->TimeSlotCharges?></span>
                                                                </span> -->

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
                                                </div>
                                            <?php }} ?>

                                            <div class="mt-2 custom-saperator"></div>
                                        </div>
                                    <?php $i++; } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4 custom-grid">
                        <div class="prime-box">

                            <div class="prime-box-inner">
                                <section class="box-title common-ele-padding">
                                    <i><span class="webCurrency payment-icon"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span></i> <span class="spn-title">
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
                                                <td><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="packingbag setCurrBasedPrice"> <?=$packingCharge?></span></td>
                                            </tr>
                                        <?php } ?>

                                        <tr id="promoCode" class="d-none">
                                            <td class="color-secondary3">Promo code discount</td>
                                            <td class="color-secondary3"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="promoCodebag"></span> <span class="removePromo" title="Remove promo code"></span></td>
                                        </tr>

                                        <?php if(isset($_SESSION['uid'])) { ?>
                                            <tr class="enableWallet d-none">
                                                <td class="color-secondary3">Floral Wallet Balance</td>
                                                <td class="color-secondary3"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="walletbag setCurrBasedPrice"> 200</span></td>
                                            </tr>
                                        <?php } ?>

                                        <tr>
                                            <td>Final Payble Amount</td>
                                            <td><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <span class="finalbag setCurrBasedPrice"><?=$totalPayable?></span></td>
                                        </tr>
                                    </table>
                                    <input type="hidden" id="finalPay" class="d-none" value="<?=$totalPayable?>"/>
                                </div>

                                <?php if(isset($_SESSION['uid'])) { ?>
                                    <div class="custom-saperator"></div>

                                    <div class="use-wallet-wrapper common-ele-padding d-none">
                                        <div class="custom-checkbox">
                                            <input id="enableWallet" class="common-chk-controls" type="checkbox">
                                            <label for="enableWallet">
                                                <span>Floral Wallet Balance</span><br />
                                                <input type="hidden" value="" id="hdnWalletBalance" />
                                                <span class="available-balance">Available Balance Rs.</span>
                                            </label>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="promo-code-wrapper common-ele-padding">
                                    <div class="enablePromoCode">
                                        <button type="button" class="c-btn c-btn-block c-btn-semi-compact c-btn-light" id="enablePromoCode">Have a Promocode?</button>
                                        <div class="apply-promocode-wrapper d-none">
                                            <input type="text" id="txtDiscountCode" placeholder="Enter your Promocode" />
                                            <button type="button" id="applyDiscount">Apply</button>
                                            <span class="erro_msg"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="common-ele-padding checkoutbtn">
                                    <button type="submit" class="c-btn c-btn-block c-btn-semi-compact c-btn-primary"><a href="<?=DOMAIN?>index.php?case=checkout&MultiHash=<?=uniqid(rand (), true)?>" >PROCEED TO CHECKOUT</a></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="no-cart">
                <div class="no-cart-img">
                    <img src="<?=DOMAIN?>Content/assets/images/common/empty-cart.png" alt="Image name">
                </div>
                <div class="no-cart-section">
                    <h1>Shopping Cart is Empty</h1>
                    <p>You have no items in your shopping cart.</p>
                </div>
                <div class="shop-section">
                    <a href="<?=rtrim(DOMAIN, '/');?>" class="c-btn c-btn-primary text-uppercase">Continue Shopping</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
