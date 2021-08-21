<?php if(isset($_SESSION['uid'])) { ?>
<div class="profile-page child-page no-order">
    <div class="container custom-container">
        <div class="breadcrumbs">
            <ul>
                <li><a href="<?=rtrim(DOMAIN, '/');?>">Home</a></li>
                <li><a>Address Book</a></li>
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
                            <h1 class="page-caption">CHANGE PASSWORD</h1>
                            <input type="hidden" id="hashID" value="<?=$data->results->ID?>" class="d-none"/>
                        </div>

                        <div class="form-or-content with-border">
                            <div class="edit-profile-form">
                                <form action="" method="post" autocomplete="off" name="editForm" id="editPassword" class="form formValidation">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group col-lg-6">
                                                <label for="OldPassword">Old Password</label>
                                                <input type="password" class="form-control" name="OldPassword" value="" id="OldPassword" placeholder="**********" />
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group col-lg-6">
                                                <label for="Password">New Password</label>
                                                <input type="password" class="form-control" name="Password" value="" id="Password" placeholder="**********" />
                                            </div>
                                        </div>

                                        <div class="col-12 edit-form-button">
                                            <div class="form-group col-lg-6 ">
                                                <button type="submit" class="c-btn c-btn-semi-smooth c-btn-semi-compact c-btn-primary mt-3 btn-update-passwrd">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
   <?php header("Location:".DOMAIN); ?>
<?php } ?>