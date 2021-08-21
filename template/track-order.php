<?php
    if(!isset($_SESSION['uid'])) {
        // header("Location:".DOMAIN);
    }
?>

<div class="ab-start">
    <img src="<?=DOMAIN?>Content/assets/images/banners/balloon-bg.jpg" alt="balloon" />
</div>

<div class="trackorder-page overhead">
    <div class="overhead-content">
        <div class="wide-content">
            <div class="search-wide">
                <input type="text" class="txt-search" placeholder="Your Order ID" />
                <button type="submit" class="btn-submit">Track</button>
            </div>
        </div>

        <h1 class="common-heading text-center">Track Order</h1>
    </div>

    <div class="overpage-content">
        <div class="container">
        <?php 
            if(isset($data->results) && !isset($data->results->error) ) {
            foreach($data->results as $key => $val) {

                $date = date_create($val->DeliveryDate);
                $deliveryDate = date_format($date, "d M Y");

        ?>
                <div class="row order-item">
                    <div class="col-12">
                        <div class="tracking-header">
                            <?=$val->ProductName?> <span class="track-delivery-time">(Delivery on <?=$deliveryDate?> - <?=$val->DeliveryTimeSlot?>)</span>
                        </div>
                    </div>
                    <div class="col-3 col-lg-2">
                        <div class="order-item-image">
                            <img src="<?=DOMAIN?><?=$val->ProductImage?>" class="img-fluid">
                            <span class="item-qty"><?=$val->ProductQty?></span>
                        </div>
                    </div>

                    <div class="col-9 col-lg-10">
                        <div class="tracking-info">
                            <div class="tracking-number">
                                Order ID - <?=$val->OrderID?>
                            </div>

                            <div class="horizontal-tracking">
                                <?php
                                    if(isset($val->TrackingSubject[0]->State) && $val->TrackingSubject[0]->State === 'Order Placed') {
                                        $firstStep = 'completed';
                                    } else {
                                        $firstStep = 'activated';
                                    }

                                    if(isset($val->TrackingSubject[1]->State) && $val->TrackingSubject[1]->State === 'Order Confirmed' && $firstStep === 'completed') {
                                        $secondStep = 'completed';
                                    } else {
                                        if($firstStep === 'completed') {
                                            $secondStep = 'activated';
                                        } else {
                                            $secondStep = '';
                                        }
                                    }
                                    
                                    if(isset($val->TrackingSubject[2]->State) && $val->TrackingSubject[2]->State === 'Enroute' && $firstStep === 'completed' && $secondStep === 'completed') {
                                        $thirdStep = 'completed';
                                    } else {
                                        if($firstStep === 'completed' && $secondStep === 'completed') {
                                            $thirdStep = 'activated';
                                        } else {
                                            $thirdStep = '';
                                        }
                                    }
                                    
                                    if(isset($val->TrackingSubject[3]->State) && $val->TrackingSubject[3]->State === 'On the way' && $firstStep === 'completed' && $secondStep === 'completed' && $thirdStep === 'completed') {
                                        $fourthStep = 'completed';
                                    } else {
                                        if($firstStep === 'completed' && $secondStep === 'completed' && $thirdStep === 'completed') {
                                            $fourthStep = 'activated';
                                        } else {
                                            $fourthStep = '';
                                        }
                                    }
                                
                                    if(isset($val->TrackingSubject[4]->State) && $val->TrackingSubject[4]->State === 'Delivered' && $firstStep === 'completed' && $secondStep === 'completed' && $thirdStep === 'completed' && $fourthStep === 'completed') {
                                        $fifthStep = 'completed';
                                    } else {
                                        if($firstStep === 'completed' && $secondStep === 'completed' && $thirdStep === 'completed' && $fourthStep === 'completed') {
                                            $fifthStep = 'activated';
                                        } else {
                                            $fifthStep = '';
                                        }
                                    }
                                ?>

                                <div class="tracking-stage text-center <?=$firstStep?>">
                                    <div class="tracking-round">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <p class="stage-name">Order Placed</p>
                                </div>

                                <div class="tracking-stage text-center <?=$secondStep?>">
                                    <div class="tracking-round">
                                        <i class="fas fa-thumbs-up"></i>
                                    </div>
                                    <p class="stage-name">Order Confirmed</p>
                                </div>

                                <div class="tracking-stage text-center <?=$thirdStep?>">
                                    <div class="tracking-round">
                                        <i class="fas fa-gift"></i>
                                    </div>
                                    <p class="stage-name">Enroute</p>
                                </div>

                                <div class="tracking-stage text-center <?=$fourthStep?>">
                                    <div class="tracking-round">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <p class="stage-name">On the way</p>
                                </div>

                                <div class="tracking-stage text-center <?=$fifthStep?>">
                                    <div class="tracking-round">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <p class="stage-name">Delivered</p>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mt-4 vertical-tracking">

                            <?php 
                                if(isset($val->TrackingSubject[0]->State)) {
                                    $dt = date_create($val->TrackingSubject[0]->TrackingDate);
                                    $dt1 = date_format($dt, "d M Y");
                                    $time1 = date_format($dt, "h:i:sa");
                            ?>

                                <div class="v-tracking-stage completed">
                                    <div class="step-wrapper">
                                        <div class="v-tracking-date-time"><?=$dt1?><br/><?=$time1?></div>
                                        <div class="v-tracking-round">
                                            <i class="fas fa-check"></i>
                                        </div>
                                        <div class="v-tracking-subject">Order Placed</div>
                                    </div>
                                </div>

                            <?php } ?>

                            <?php 
                                if(isset($val->TrackingSubject[1]->State)) {
                                    $dt = date_create($val->TrackingSubject[1]->TrackingDate);
                                    $dt2 = date_format($dt, "d M Y");
                                    $time2 = date_format($dt, "h:i:sa");
                            ?>

                                <div class="v-tracking-stage completed">
                                    <div class="step-wrapper">
                                        <div class="v-tracking-date-time"><?=$dt2?><br/><?=$time2?></div>
                                        <div class="v-tracking-round">
                                            <i class="fas fa-thumbs-up"></i>
                                        </div>
                                        <div class="v-tracking-subject">Order Confirmed</div>
                                    </div>
                                </div>

                            <?php } ?>

                            <?php 
                                if(isset($val->TrackingSubject[2]->State)) {
                                    $dt = date_create($val->TrackingSubject[2]->TrackingDate);
                                    $dt3 = date_format($dt, "d M Y");
                                    $time3 = date_format($dt, "h:i:sa");
                            ?>

                                <div class="v-tracking-stage">
                                    <div class="step-wrapper">
                                        <div class="v-tracking-date-time"><?=$dt3?><br/><?=$time3?></div>
                                        <div class="v-tracking-round">
                                            <i class="fas fa-gift"></i>
                                        </div>
                                        <div class="v-tracking-subject">Enroute</div>
                                    </div>
                                </div>

                            <?php } ?>

                            <?php 
                                if(isset($val->TrackingSubject[3]->State)) {
                                    $dt = date_create($val->TrackingSubject[3]->TrackingDate);
                                    $dt4 = date_format($dt, "d M Y");
                                    $time4 = date_format($dt, "h:i:sa");
                            ?>

                                <div class="v-tracking-stage">
                                    <div class="step-wrapper">
                                        <div class="v-tracking-date-time"><?=$dt4?><br/><?=$time4?></div>
                                        <div class="v-tracking-round">
                                            <i class="fas fa-gift"></i>
                                        </div>
                                        <div class="v-tracking-subject">Enroute</div>
                                    </div>
                                </div>

                            <?php } ?>

                            <?php 
                                if(isset($val->TrackingSubject[4]->State)) {
                                    $dt = date_create($val->TrackingSubject[4]->TrackingDate);
                                    $dt5 = date_format($dt, "d M Y");
                                    $time5 = date_format($dt, "h:i:sa");
                            ?>

                                <div class="v-tracking-stage">
                                    <div class="step-wrapper">
                                        <div class="v-tracking-date-time"><?=$dt5?><br/><?=$time5?></div>
                                        <div class="v-tracking-round">
                                            <i class="fas fa-gift"></i>
                                        </div>
                                        <div class="v-tracking-subject">Enroute</div>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-12"><hr /></div>
                </div>

        <?php }} ?>
        </div>
    </div>
</div>