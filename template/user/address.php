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
                            <h1 class="page-caption">ADDRESS BOOK</h1>
                            <input type="hidden" id="hashID" value="<?=$data->results->ID?>" class="d-none"/>
                        </div>

                        <div class="form-or-content with-border">
                            <div class="address-wrapper">
                                <div class="address-box-wrapper">
                                    <div class="address-lists">
                                        <div class="row mt-3 lister">
                                        <?php
                                            if(!isset($data1->results->error)) {
                                                foreach($data1->results as $key => $add)
                                                {
                                            ?>
                                                <div class="col-12 col-lg-4 address-grid">
                                                    <div id="addBox<?=$add->ID?>" class="address-box">
                                                        <button type="button" data-addressSNO="<?=$add->ID?>" class="btn-remove-address address-<?=$add->ID?>">&times;</button>
                                                        <button type="button" data-addressSNO="<?=$add->ID?>" class="btn-edit">Edit</button>
                                                        <p><span class="delivery-title"><?=$add->Title?></span>. <span class="delivery-fname"><?=$add->FirstName?> </span> <span class="delivery-lname"><?=$add->LastName?></span></p>
                                                        <p class="address">
                                                            <span class="blockNo"><?=$add->BuildingName?></span>
                                                            <span class="streetName"><?=$add->StreetName?></span>
                                                            <span class="areaName"><?=$add->AreaName?></span>
                                                            <span class="landmark"><?=$add->Landmark?> </span>
                                                        </p>
                                                        <p>
                                                            <span class="address-city"><?=$add->City?> - </span><span class="address-postcode"><?=$add->Postcode?></span> <span class="address-state d-none"><?=$add->ID?>State</span><span class="address-country d-none">India</span></p>
                                                        <p>
                                                            Mobile -
                                                            <span class="address-contact"><?=$add->MobileNumber?></span>
                                                            <span class="address-alt-contact d-none"><?=$add->AlternateNumber?></span>
                                                        </p>
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
                                                        <option value="Mr">Mr</option>
                                                        <option value="Ms">Ms</option>
                                                        <option value="Mrs">Mrs</option>
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
                                                    <label class="form-caption">Area Name</label><span class="mandate">*</span>
                                                    <input type="text" data-val="address" name="AreaName" class="form-control form-area-name h_117" />
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-caption">Landmark</label><span class="mandate">*</span>
                                                    <input type="text" data-val="address" name="Landmark" class="form-control form-landmark h_118" />
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-3">
                                                <div class="form-group">
                                                    <label class="form-caption">City</label><span class="mandate">*</span>
                                                    <select data-val="address" name="City" class="form-control form-city h_119">
                                                        <?php
                                                            foreach($data3->results as $key => $cit) {
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
                                                <a class="link-primary btn-back-address">Back to saved address</a>

                                                <button type="submit" class="c-btn c-btn-secondary c-btn-compact c-btn-semi-smooth btn-shipping-address">Save</button>
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
</div>
<?php } else { ?>
   <?php header("Location:".DOMAIN); ?>
<?php } ?>