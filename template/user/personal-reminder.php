<?php if(isset($_SESSION['uid'])) { ?>
<div class="profile-page child-page reminder-page no-order">
    <div class="container custom-container">
        <div class="breadcrumbs">
            <ul>
                <li><a href="<?=rtrim(DOMAIN, '/');?>">Home</a></li>
                <li><a>Occasion Reminder</a></li>
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
                            <div class="row">
                                <div class="col-6">
                                    <h1 class="page-caption">Occasion Reminder</h1>
                                </div>

                                <div class="col-6 text-right">
                                    <button class="add-reminder get-reminder-form" data-action="add"><i class="fas fa-plus"></i> Add a New Reminder</button>
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="list-container">
                            <?php
                            if(!isset($data1->results->error)) {
                                foreach($data1->results as $key => $rem)
                                {
                                    $date = date_create($rem->ReminderDate);
                                    $EventDate = date_format($date, "d M Y");
                            ?>
                                <div class="event-list">
                                    <div class="reminder__item-text">
                                        <p class="reminder__item-name"><?=$rem->ReminderName?></p>
                                        <p class="reminder__item-info"><?=$EventDate?>, <?=$rem->Event?></p>
                                    </div>

                                    <div class="reminder__item-action">
                                        <a class="reminder__item-edit get-reminder-form" data-id="<?=$rem->ID?>" data-action="edit" primary-dark" href="javascript;">Edit</a>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            <?php }} ?>
                        </div>

                        <div class="form-or-content reminder-form d-none">
                            <div class="edit-profile-form">
                                <form name="reminderForm" data-action="add" class="formValidation" autocomplete="off" id="reminderForm">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group"> 
                                                <label>Title</label>
                                                <input type="text" name="reminderName" class="form-control" id="remindeName" placeholder="Title" />
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group"> 
                                                <label>Location</label>
                                                <textarea type="text" name="LocationName" class="form-control" id="LocationName" placeholder="Location"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label>Country Code</label>
                                                <select type="text" name="reminderCountryCode" class="form-control has-combobox" id="reminderCountryCode">
                                                    <option></option>
                                                    <?php
                                                        if(!isset($data2->results->error)) {
                                                            foreach($data2->results as $key => $country)
                                                            {
                                                        ?>
                                                            <option value="<?=$country->PhoneCode?>" data-image="<?=$country->CountryFlag?>" data-class="avatar" data-style="background-image: url(<?=$country->CountryFlag?>);"><?=$country->PhoneCode?></option>
                                                    <?php }} ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-lg-8">
                                            <div class="form-group">
                                                <label>Mobile No</label>
                                                <input type="tel" name="reminderContact" id="reminderContact" data-toggle="tooltip" title="We will notify you on this number" class="form-control tooltipped" placeholder="Contact No" />
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Select Occasion</label>
                                                <select type="text" name="reminderEvent" id="reminderEvent" class="form-control">
                                                    <option></option>
                                                    <?php
                                                        if(!isset($data3->results->error)) {
                                                            foreach($data3->results as $key => $occasion)
                                                            {
                                                        ?>
                                                            <option data-code="<?=$occasion->ID?>" value="<?=$occasion->Name?>"><?=$occasion->Name?></option>
                                                    <?php }} ?>
                                                    <option data-code="0" value="0">Others</option>
                                                </select>
                                                <input type="hidden" id="EventCode" name="EventCode" />
                                            </div>

                                            <div class="form-group d-none">
                                                <label>Enter occasion name</label>
                                                <input type="text" name="txtReminderEvent" id="txtReminderEvent" class="form-control" />
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Reminder Date</label>
                                                <input type="text" name="reminderDate" id="reminderDate" class="form-control js-picker tooltipped" placeholder="Reminder Date" data-toggle="tooltip" title="We will send you reminder 5 days before" />
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group"> 
                                                <label>Notes</label>
                                                <textarea type="text" name="Notes" class="form-control" id="Notes" placeholder="Enter notes if any"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Get notification by</label>
                                                <select id="Preference" name="Preference" class="form-control">
                                                    <option></option>
                                                    <option value="email">Email</option>
                                                    <option value="sms">SMS</option>
                                                    <option value="both">Both</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <button type="button" class="delete-reminder btn btn-link">Remove</button>
                                            </div>
                                        </div>

                                        <div class="col-6 text-right">
                                            <div class="form-group">
                                                <button class="c-btn c-btn-semi-compact c-btn-primary save-reminder" type="submit">Save</button>
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