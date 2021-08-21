<?php
    if(!isset($_SESSION['uid'])) {
        header("Location:".DOMAIN);
    }
?>

<div class="profile-page child-page order-history-page no-order">
    <div class="container custom-container">
        <div class="breadcrumbs">
            <ul>
                <li><a href="<?=rtrim(DOMAIN, '/');?>">Home</a></li>
                <li><a>Order History</a></li>
            </ul>
        </div>

        <div class="profile-stage">
            <div class="row">
                <div class="col-12 col-lg-3">
                    <?php include 'profile-nav.php';?>
                </div>

                <div class="col-12 col-lg-9">
                    <div class="profile-content">
                        <div class="page-subject">
                            <h1 class="page-caption text-uppercase">Order History</h1>
                            <div class="order-history-tab">
                                <ul>
                                    <li class="<?=($params['state'] === 'ongoing-orders') ? "active" : ""; ?>">
                                        <a href="<?=DOMAIN?>index.php?case=order-history&state=ongoing-orders">Ongoing Orders</a>
                                    </li>
                                    <li class="<?=($params['state'] === 'all-orders') ? "active" : ""; ?>">
                                        <a href="<?=DOMAIN?>index.php?case=order-history&state=all-orders">All Orders</a>
                                    </li>
                                    <li class="<?=($params['state'] === 'cancelled-orders') ? "active" : ""; ?>">
                                        <a href="<?=DOMAIN?>index.php?case=order-history&state=cancelled-orders">Cancelled Orders</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-or-content no-style">

                            <?php if($params['state'] === 'all-orders') { ?>

                                <?php  if(isset($orhistory->results->Orders)) { ?>

                                    <div class="order-count"><span class="count"><?=$orhistory->results->TotalCount?></span><span> Orders</span> placed in 
                                        <select>
                                            <option value="1">Last month</option>
                                            <option value="3">Last 3 Months</option>
                                            <option value="6">Last 6 Months</option>
                                            <option value="12">Last 12 Months</option>
                                            <option value="13">Older</option>
                                        </select>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <div class="order-card-section">
                            <?php 
                                if(isset($orhistory->results->Orders)) {
                                foreach($orhistory->results->Orders as $key => $val) {

                                    $date = date_create($val->CreatedDate);
                                    $createdDate = date_format($date, "d M Y");

                                    $date1 = date_create($val->DeliveryDate);
                                    $deliveryDate = date_format($date1, "d M Y");

                            ?>
                                <div class="order-card">
                                    <div class="order-card-head">
                                        <div class="row">
                                            <div class="col-4">
                                                <span class="order-head-title">Order Placed</span><br />
                                                <?=$createdDate?>
                                            </div>

                                            <?php
                                                $totalPrice = $val->ProductPrice;

                                                if(!empty($val->ShippingChrg)) {
                                                    $totalPrice += $val->ShippingChrg;
                                                }
                                                if(!empty($val->PackingChrg)) {
                                                    $totalPrice += $val->PackingChrg;
                                                }
                                                if(!empty($val->ProductSizePrice)) {
                                                    $totalPrice += $val->ProductSizePrice;
                                                }
                                            ?>

                                            <div class="col-4">
                                                <span class="order-head-title">Total</span><br />
                                                <span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span> <?=$totalPrice?></span>
                                            </div>

                                            <div class="col-4">
                                                <span class="order-head-title">Order #<?=$val->OrderID?></span><br />
                                                <a class="primary-dark d-none">Get Invoice</a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="order-card-body">
                                    <?php
                                        $greetingMsg = isset($val->ShippingAddress->SenderMessage) ? $val->ShippingAddress->SenderMessage : 'No Message';
                                    ?>

                                        <p class="delivery-time">Delivery on <?=$deliveryDate?> at <?=$val->DeliveryTimeSlot?><p>
                                        <p class="delivery-to">Ship to - <a title="Deliver to" data-toggle="popover" data-html="true" data-placement="bottom" data-content="<p><?=ucfirst($val->ShippingAddress->FirstName)?> <?=ucfirst($val->ShippingAddress->LastName)?></p><p><strong>Address -</strong> <?=$val->ShippingAddress->Address?></p><p><strong>Message</strong> - <?=$greetingMsg?></p>" class="primary-dark pointer"><?=ucfirst($val->ShippingAddress->FirstName)?> <?=ucfirst($val->ShippingAddress->LastName)?> (See more)</a></p>
                                    
                                        <div class="cart-item-wrapper">
                                            <div class="cart-item mt-4">
                                                <div class="row">
                                                    <div class="col-12 col-lg-8">
                                                        <div class="dv-prod">
                                                            <span class="qty"><?=$val->ProductQty?></span>
                                                            <img src="<?=DOMAIN?><?=$val->ProductImage?>" alt="<?=$val->ProductName?>" class="img-product">
                                                        </div>
                                                        <h3 class="product-title"><?=$val->ProductName?></h3>
                                                        <p class="small-description"><?=$val->Size?></p>
                                                        <p class="item-price">
                                                            <span class="primary-dark"><span class="webCurrency"><span class="setDefaultCurrency"><?=$CurrencyLogo?></span></span> <?=$totalPrice?></span>
                                                        </p>
                                                    </div>

                                                    <div class="col-12 col-lg-4">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            <?php }} else { ?>
                                <h3>Order list is empty..!!</h3>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>