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
                <li><a>Wallet History</a></li>
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
                            <h1 class="page-caption text-uppercase">Wallet History</h1>
                        </div>
                        <?php
                            if(!isset($data1->results->error)) {
                         ?>
                        <div class="form-or-content no-style">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                    <th scope="col">Sr. No.</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Wallet Status</th>
                                    <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      
                                            $a = 0;
                                        foreach($data1->results as $key => $val) {
                                            $a++;
                                    ?>
                                        <tr>
                                            <th scope="row"><?=$a?></th>
                                            <?php if($val->WalletStatus == 'Expired' || $val->WalletStatus == 'Used') { ?>
                                                <td><?=$val->UpdatedDate?></td>
                                            <?php } elseif($val->IsActve == '1') { ?>
                                                <td><?=$val->CreatedDate?></td>
                                            <?php } ?>

                                            <?php if($val->WalletStatus == 'Expired') { ?>
                                                <td>Expired</td>
                                            <?php } elseif($val->WalletStatus == 'Used') { ?>
                                                <td>Debited</td>
                                            <?php } elseif($val->IsActve == '1') { ?>
                                                <td>Credited</td>
                                            <?php } ?>

                                            <?php if($val->WalletStatus == 'Expired' || $val->WalletStatus == 'Used') { ?>
                                                <td class="red">- <?=$val->CreditValue?></td>
                                            <?php } elseif($val->IsActve == '1') { ?>
                                                <td class="green">+ <?=$val->CreditValue?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                            <div class="mt-3"><strong>No records found.</strong></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>