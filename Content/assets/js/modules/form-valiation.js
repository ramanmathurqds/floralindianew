$(function () {
    //custome regex rules
    jQuery.validator.addMethod("numbersonly", function (value, element) {
        return this.optional(element) || /^[0-9]{10}$/i.test(value);
    });

    jQuery.validator.addMethod("numbersonly2", function (value, element) {
        return this.optional(element) || /^[0-9]{9,11}$/i.test(value);
    });

    jQuery.validator.addMethod("postcode", function (value, element) {
        var PostcodePattern  = new RegExp(element.getAttribute('data-pattern'), 'i');
        //return this.optional(element) || PostcodePattern.test(value);
        return this.optional(element) || PostcodePattern.test(value);
    });

    jQuery.validator.addMethod("alphanumeric", function (value, element) {
        return this.optional(element) || /^[A-Za-z\d\-_\s]+$/.test(value);
    });

    $('.formValidation').each(function () {
        $(this).validate({
            rules: {
                //reg validation
                "FirstName": {
                    required: true
                },

                "LastName": {
                    required: true
                },

                "Email": {
                    required: true,
                    email: true
                },

                "Password": {
                    required: true
                },

                //login validation
                "loginEmail": {
                    required: true
                },

                "loginPassword": {
                    required: true
                },

                "mobloginUserID": {
                    required: true
                },

                "mobloginPassword": {
                    required: true
                },

                "mobRegFirstname": {
                    required: true
                },

                "mobRegLastname": {
                    required: true
                },

                "mobRegEmail": {
                    required: true
                },

                "mobRegPassword": {
                    required: true
                },

                //edit profile
                "editFirstname": {
                    required: true
                },

                "editLastname": {
                    required: true
                },

                "editGender": {
                    required: true
                },

                "editDOB": {
                    required: true
                },

                "editEmail": {
                    required: true,
                    email: true
                },

                "editMobile": {
                    required: true
                },

                //CONTACT
                "contactName":{
                    required: true
                },

                "contactSurname":{
                    required: true
                },

                "contactEmail":{
                    required: true,
                    email:true
                },

                "contactPhone": {
                    required:true,
                    number: true
                },

                "contactSubject": {
                    required: true
                },

                "contactMessage": {
                    required:true
                },

                //shipping address form
                "Title":{
                    required: true
                },

                "BuildingName":{
                    required: true
                },

                "StreetName":{
                    required:true
                },

                "City":{
                    required:true
                },

                "State":{
                    required:true
                },

                "Postcode":{
                    required:true,
                    postcode:true
                },

                "postCodeBilling":{
                    required:true,
                    alphanumeric:true
                },

                "Country":{
                    required:true
                },

                "MobileNumber":{
                    required:true,
                    numbersonly:true
                },

                "MobileNumberBilling":{
                    required:true,
                    numbersonly2:true
                },

                //billing form
                "Type":{
                    required: true
                },

                "fName":{
                    required: true
                },

                "lName":{
                    required:true
                },

                "blockNo":{
                    required: true
                },

                "streetName":{
                    required:true
                },

                "city":{
                    required:true
                },

                "state":{
                    required:true
                },

                "postCode":{
                    required:true,
                    postcode:true
                },

                "country":{
                    required:true
                },

                // reminder form
                "reminderName":{
                    required: true,
                    alphanumeric:true
                },

                "reminderCountryCode":{
                    required: true
                },

                "reminderContact":{
                    required:true,
                    numbersonly2:true
                },

                "reminderEvent":{
                    required:true
                },

                "reminderDate":{
                    required:true
                }
            },

            messages: {
                "FirstName": {
                    required: "Please enter your first name"
                },

                "LastName": {
                    required: "Please enter your last name"
                },

                "Email": {
                    required: "Please enter your email",
                    email: "Please enter valid email"
                },

                "Password": {
                    required: "Please enter your password"
                },

                //login validation
                "loginEmail": {
                    required: "Please enter your username/email"
                },

                "loginPassword": {
                    required: "Please enter your password"
                },

                "mobloginUserID": {
                    required: ""
                },

                "mobloginPassword": {
                    required: ""
                },

                "mobRegFirstname": {
                    required: ""
                },

                "mobRegLastname": {
                    required: ""
                },

                "mobRegEmail": {
                    required: ""
                },

                "mobRegPassword": {
                    required: ""
                },

                //edit profile
                "editFirstname": {
                    required: "Please enter your first name"
                },

                "editLastname": {
                    required: "Please enter your last name"
                },

                "editGender": {
                    required: "Please select your gender"
                },

                "editDOB": {
                    required: "Please enter your Birthdate"
                },

                "editEmail": {
                    required: "Please enter your email address",
                    email: "Please enter valid email address"
                },

                "editMobile": {
                    required: "Please enter your mobile number",
                    number: "Please enter valid phone number",
                    pattern: "Please enter valid phone number"
                },

                //CONTACT
                "contactName":{
                    required: "Please enter your name"
                },

                "contactSurname":{
                    required: "Please enter your surname"
                },

                "contactEmail":{
                    required: "Please enter your email address",
                    email:"Please enter valid email address"
                },

                "contactPhone": {
                    required:"PLease enter your phone number",
                },

                "contactSubject": {
                    required: "Please select anyone subject"
                },

                "contactMessage": {
                    required:"Please enter your message"
                },

                "MobileNumber":{
                    required:"Please enter mobile number",
                    numbersonly:"Please enter valid mobile number"
                },

                "MobileNumberBilling":{
                    required:"Please enter mobile number",
                    numbersonly2:"Please enter valid mobile number"
                },

                "Postcode":{
                    postcode:"Please enter valid postcode"
                },

                "postCodeBilling":{
                    required:"Please enter postcode",
                    alphanumeric:"Please enter valid postcode"
                },

                //billing form
                "postCode":{
                    postcode:"Please enter valid postcode"
                },

                // reminder form
                "reminderName":{
                    required: "Please enter name of reminder",
                    alphanumeric: "Please enter valid reminder name using numbers or letter only"
                },

                "reminderCountryCode":{
                    required: "Please select country code"
                },

                "reminderContact":{
                    required: "Please enter number on which you want us to send reminder",
                    numbersonly2:"Please enter valid contact number"
                },

                "reminderEvent":{
                    required: "Please select event"
                },

                "reminderDate":{
                    required:"Please enter on which date you want to set a reminder"
                }
            },

            highlight: function (element) {
                $(element).addClass("error-input");
                if ($('.login-form:visible')) {
                    $(element).parent().addClass("error-input");
                }
            },

            unhighlight: function (element) {
                $(element).removeClass("error-input");
                if ($('.login-form:visible')) {
                    $(element).parent().removeClass("error-input");
                }
            },

            submitHandler: function (form) {
                form.submit();
            }
        });
     });

    //$('.form-title').rules('add', {
    //    required: true,
    //    messages: {
    //        required: "Please select your title"
    //    }
    //});

    //$('.form-firstname').rules('add', {
    //    required: true,
    //    messages: {
    //        required: "Please enter first name"
    //    }
    //});
     

    // $('.form-lastname').rules('add', {
    //     required: true,
    //     messages: {
    //         required: "Please enter last name"
    //     }
    // });

    // $('.form-block-number').rules('add', {
    //     required: true,
    //     messages: {
    //         required: "Please enter building name and appartment number"
    //     }
    // });

    // $('.form-street-name').rules('add', {
    //     required: true,
    //     messages: {
    //         required: "Please enter street name"
    //     }
    // });

     //$('.form-area-name').rules('add', {
     //    required: true,
     //    messages: {
     //        required: "Please enter area name"
     //    }
     //});

     //$('.form-area-name').rules('add', {
     //    required: true,
     //    messages: {
     //        required: "Please enter area name"
     //    }
     //});

     //$('.form-landmark').rules('add', {
     //    required: true,
     //    messages: {
     //        required: "Please enter landmark"
     //    }
     //});

     //$('.form-postcode').rules('add', {
     //    required: true,
     //    messages: {
     //        required: "Please enter postcode"
     //    }
     //});

    // $('.form-mobile').rules('add', {
    //     required: true,
    //     //mobileNumber:true,
    //     messages: {
    //         required: "Please enter mobile number",
    //         //mobileNumber: "Please enter valid mobile number"
    //     }
    //});

    //$('.form-mobile').rules('add', {
    //    mobileNumber: true,
    //    messages: {
    //        mobileNumber: "Please enter valid mobile number"
    //    }
    //});
});

