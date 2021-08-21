$(document).on('click', '.address-box .btn-edit', function () {
    var dataAddrs = $(this).attr('data-addresssno');
    toggleAddressView(this, dataAddrs);
    var form = $(this).parents('.address-wrapper').find('form');
    form.removeClass('addAddress').addClass('updateAddress');
    $('.btn-add-shipping-address').addClass('d-none').prop('disabled', true);
    $('.btn-update-shipping-address').removeClass('d-none').prop('disabled', false);
    
    let addressID = $(this).attr('data-addressSNO');
    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=editUserAddress&ID=" + addressID,
        type: "POST",
        success: function(data) {
            var rsjson = $.parseJSON(data);

            form.find('.h_112').val(rsjson.results.Title);
            form.find('.h_113').val(rsjson.results.FirstName);
            form.find('.h_114').val(rsjson.results.LastName);
            form.find('.h_115').val(rsjson.results.BuildingName);
            form.find('.h_116').val(rsjson.results.StreetName);
            form.find('.h_117').val(rsjson.results.AreaName);
            form.find('.h_118').val(rsjson.results.Landmark);
            form.find('.h_119').val(rsjson.results.City);
            form.find('.h_1110').val(rsjson.results.State);
            form.find('.h_1111').val(rsjson.results.Postcode);
            form.find('.h_1112').val(rsjson.results.Country);
            form.find('.h_1113').val(rsjson.results.MobileNumber);
            form.find('.h_1114').val(rsjson.results.AlternateNumber);
            form.find('.h_1115').val(rsjson.results.SpecialInstruction);

        }
    });
    return false;
});

$(document).on('click', '.address-box .btn-remove-address', function () {
    let addressID = $(this).attr('data-addressSNO'),
    addressBox = $('.address-' + addressID).parents('.address-grid')
    
    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=deleteUserAddress",
        data: { "ID":addressID },
        type: "POST",
        success: function(data) {
            var rsjson = $.parseJSON(data);
            $(addressBox).remove();
            $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
            var toastMsg = $.cookie("toastMsg");
            if(toastMsg) {
                $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                $.removeCookie('toastMsg', { path: '/' });
            }
        }
    });
});

$(document).on('click', '.btn-deliver', function () {
    $(this).parents('.address-box-wrapper').find('.btn-deliver').removeClass('selected');
    $(this).addClass('selected');
    let currCartID = $(this).parents('.cart-item-wrapper').find('.cart-item').attr('cart-id');
    let currAddID = $(this).parents('.address-box').attr('address-id');
    addSelectedAddressToProduct(currAddID, currCartID);
});

$(document).on('click', '.address-box-wrapper .btn-add-new', function () {
    toggleAddressView(this, '');
    $(this).parents('.address-wrapper').find('form').removeClass('updateAddress').addClass('addAddress');
    $('.btn-update-shipping-address').addClass('d-none').prop('disabled', true);
    $('.btn-add-shipping-address').removeClass('d-none').prop('disabled', false);
    clearForm(this);
});

$(document).on('click', '.btn-update-shipping-address', function () {
    var thisForm = $(this).parents('form');
    if ($(thisForm).valid()) {
        $(this).siblings('.btn-back-address').trigger('click');
    }
});

$(document).on('click', '.btn-add-shipping-address', function () {
    var thisForm = $(this).parents('form');
    //$(thisForm).valid();
    //if ($(thisForm).valid()) {
    //    alert('valid');
    //}
    $(this).siblings('.btn-back-address').trigger('click');
});

$(document).on('click', '.btn-back-address', function () {
    toggleAddressViewInvert(this);
});

function toggleAddressView(thisButton, dataAddress) {
    $(thisButton).parents('.address-box-wrapper').toggleClass('d-none');  
    $(thisButton).parents('.address-box-wrapper').siblings('.address-form').toggleClass('d-none');
    $(thisButton).parents('.address-box-wrapper').siblings('.address-form').attr('data-address', dataAddress);
}

function toggleAddressViewInvert(thisButton) {
    $(thisButton).parents('.address-form').toggleClass('d-none');
    $(thisButton).parents('.address-form').siblings('.address-box-wrapper').toggleClass('d-none');
}

function addSelectedAddressToProduct(addressID, cartID) {
    $.ajax({
        url : FLORAL_AJAX + "ajx.php?case=addSelectedAddToProduct",
        data: { "CartID":cartID, "ID":addressID},
        type: "POST",
        success: function(data) {
            var rsjson = $.parseJSON(data);
            if(rsjson.results.error === 0) {
                // $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                // var toastMsg = $.cookie("toastMsg");
                // if(toastMsg) {
                //     $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                //     $.removeCookie('toastMsg', { path: '/' });
                // }
            }
        }
    });
}

function editAddressForm(selectedButton) {
    var boxTarget = $(selectedButton).parents('.address-box');
    var address = {
        Title: $(boxTarget).find('.delivery-title').text(),
        FirstName: $(boxTarget).find('.delivery-fname').text(),
        LastName: $(boxTarget).find('.delivery-lname').text(),
        Block: $(boxTarget).find('.blockNo').text(),
        Street: $(boxTarget).find('.streetName').text(),
        Area: $(boxTarget).find('.areaName').text(),
        LandMark: $(boxTarget).find('.landmark').text(),
        City: $(boxTarget).find('.address-city').text(),
        State: $(boxTarget).find('.address-state').text(),
        PostCode: $(boxTarget).find('.address-postcode').text(),
        Country: $(boxTarget).find('.address-country').text(),
        MobileNo: $(boxTarget).find('.address-contact').text(),
        AltNo: $(boxTarget).find('.address-alt-contact').text()
    };

    var selectedForm = $(selectedButton).parents('.address-wrapper');

    //assign data-target-id to form whichever address is selected
    var targetForm = $(selectedButton).parents('.address-wrapper').find('.address-form').children('form');
    $(targetForm).attr('data-target-id', $(selectedButton).parent('.address-box').attr('id'));
    //assign data-target-id to form whichever address is selected

    $(selectedForm).find('.form-title option');
    $(selectedForm).find('.form-title option').each(function () {
        if ($(this).text() === savedAddressObj.deliveryTitle) {
            $(this).attr('selected', 'selected');
        }
    });

    $(selectedForm).find('.form-firstname').val(address.FirstName);
    $(selectedForm).find('.form-lastname').val(address.LastName);
    $(selectedForm).find('.form-block-number').val(address.Block);
    $(selectedForm).find('.form-street-name').val(address.Street);
    $(selectedForm).find('.form-area-name').val(address.Area);
    $(selectedForm).find('.form-landmark').val(address.Landmark);
    $(selectedForm).find('.form-postcode').val(address.Postcode);
    $(selectedForm).find('.form-mobile').val(address.MobileNo);
    $(selectedForm).find('.form-alt-mobile').val(address.AltNo);
}

function clearForm(targetForm) {
    var thisForm = $(targetForm).parents('.address-wrapper');
    $(thisForm).find('form').find('.form-control').each(function () {
        $(this).val('');
    });
}

