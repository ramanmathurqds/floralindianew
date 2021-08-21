<?php if(isset($_SESSION['uid'])) { ?>
<ul class="profile-nav">
    <li class="<?=($params['case'] == 'my-profile') ? "active" : ""; ?>">
        <a href="<?=DOMAIN?>my-profile">
            <div class="d-flex align-items-center">
                <div class="nav-icon">
                    <span class="icon-placeholder shimmer">
                        <i></i> 
                    </span>
                </div>

                <div class="nav-caption">
                    <span class="nav-title d-block"><?=$data->results->FirstName?> <?=$data->results->LastName?></span>
                    <span class="nav-subtitle d-block"><?=$data->results->Email?></small>
                </div>
            </div>
        </a>
    </li>

    <li class="<?=($params['case'] == 'order-history') ? "active" : ""; ?>">
        <a href="<?=DOMAIN?>index.php?case=order-history&state=ongoing-orders">
            <div class="d-flex align-items-center">
                <div class="nav-icon">
                    <span class="icon-placeholder shimmer">
                        <i></i> 
                    </span>
                </div>

                <div class="nav-caption">
                    <span class="nav-title d-block">Order History</span>
                </div>
            </div>
        </a>
    </li>

    <li class="<?=($params['case'] == 'address-book') ? "active" : ""; ?>">
        <a href="<?=DOMAIN?>address-book">
            <div class="d-flex align-items-center">
                <div class="nav-icon">
                    <span class="icon-placeholder shimmer">
                        <i></i> 
                    </span>
                </div>

                <div class="nav-caption">
                    <span class="nav-title d-block">Address Book</span>
                </div>
            </div>
        </a>
    </li>

    <li class="<?=($params['case'] == 'wallet') ? "active" : ""; ?>">
        <a href="<?=DOMAIN?>wallet">
            <div class="d-flex align-items-center">
                <div class="nav-icon">
                    <span class="icon-placeholder shimmer">
                        <i></i> 
                    </span>
                </div>

                <div class="nav-caption">
                    <span class="nav-title d-block">Wallet</span>
                </div>
            </div>
        </a>
    </li>

    <li class="<?=($params['case'] == 'personal-reminder') ? "active" : ""; ?>">
        <a href="<?=DOMAIN?>occasion-reminder">
            <div class="d-flex align-items-center">
                <div class="nav-icon">
                    <span class="icon-placeholder shimmer">
                        <i></i> 
                    </span>
                </div>

                <div class="nav-caption">
                    <span class="nav-title d-block">Occasion Reminder</span>
                </div>
            </div>
        </a>
    </li>

    <li class="<?=($params['case'] == 'account-setting') ? "active" : ""; ?>">
        <a href="<?=DOMAIN?>account-setting">
            <div class="d-flex align-items-center">
                <div class="nav-icon">
                    <span class="icon-placeholder shimmer">
                        <i></i> 
                    </span>
                </div>

                <div class="nav-caption">
                    <span class="nav-title d-block">Account Setting</span>
                </div>
            </div>
        </a>
    </li>
</ul>
<?php } else { ?>
   <?php header("Location:".DOMAIN); ?>
<?php } ?>