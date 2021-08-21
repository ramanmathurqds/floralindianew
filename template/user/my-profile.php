<?php if(isset($_SESSION['uid'])) { ?>
<div class="profile-page child-page no-order">
    <div class="container custom-container">
        <div class="breadcrumbs">
            <ul>
                <li><a href="<?=rtrim(DOMAIN, '/');?>">Home</a></li>
                <li><a>My Profile</a></li>
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
                            <h1 class="page-caption">EDIT PROFILE</h1>
                            <input type="hidden" id="hashID" value="<?=$data->results->ID?>" class="d-none"/>
                        </div>

                        <div class="form-or-content with-border">
                            <div class="profile-pic-block">
                                <div class="profile-pic">

                                    <?php 
                                        if(isset($data->results->ProfileImage) && !empty($data->results->ProfileImage)) {
                                    ?>
                                        <?php if($data->results->UserType == 'User') { ?>
                                            <img src="<?=IMG?>userProfilePics/<?=$data->results->ProfileImage?>" alt="profile-pic">
                                        <?php } elseif($data->results->UserType == 'gmail-User') { ?>
                                            <img src="<?=$data->results->ProfileImage?>" alt="profile-pic">
                                        <?php } ?>
                                    <?php } else { ?>
                                        <img src="<?=IMG?>userProfilePics/dummy-profile-pic.png" alt="profile-pic">
                                    <?php } ?>

                                    <label for="ProfileImage" class="btn-profile-pic-upload">Click to change your image</label>
                                    <label for="ProfileImage" class="btn-add-pic"><span>+</span></label>
                                    <label for="ProfileImage" class="btn-add-pic2"></label>
                                    <input type="file" id="ProfileImage" name="ProfileImage" class="d-none" />
                                </div>
                            </div>

                            <div class="edit-profile-form mt-4">
                                <form action="" method="post" autocomplete="off" name="editForm" id="editForm" class="form formValidation">
                                    <div class="row">
                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control" name="editFirstname" value="<?=$data->results->FirstName?>" id="editFirstname" />
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" name="editLastname" value="<?=$data->results->LastName?>" id="editLastname" />
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <select class="form-control" name="editGender" value="<?=$data->results->Gender?>" id="editGender">
                                                    <option <?php if($data->results->Gender == 'Female'){ echo ' selected="selected"'; } ?> value="Female">Female</option>
                                                    <option <?php if($data->results->Gender == 'Male'){ echo ' selected="selected"'; } ?> value="Male">Male</option>
                                                    <option <?php if($data->results->Gender == 'Transgender'){ echo ' selected="selected"'; } ?> value="Transgender">Transgender</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Date of Birth</label>
                                                <input type="text" class="form-control js-picker" value="<?=$data->results->DOB?>" name="editDOB" id="editDOB" />
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" class="form-control" name="editEmail" value="<?=$data->results->Email?>" id="editEmail" />
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6">
                                            <div class="form-group">
                                                <label>Mobile No</label>
                                                <input type="text" class="form-control" name="editMobile" value="<?=$data->results->MobileNo?>" id="editMobile" />
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6 edit-form-button">
                                            <div class="form-group">
                                                <button type="reset" class="c-btn c-btn-semi-smooth c-btn-semi-compact c-btn-light mt-3">Clear</button>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-6 edit-form-button">
                                            <div class="form-group">
                                                <button type="submit" class="c-btn c-btn-semi-smooth c-btn-semi-compact c-btn-primary mt-3 btn-edit-profile">Update</button>
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