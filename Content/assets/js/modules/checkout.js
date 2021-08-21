var shippingForm = $('#updateAddress');
var billingForm = $('#shippingFormSubmit');
var isAddressSet = false;
var isWalletSet = false;
var ItemCount = Number($('.cart-item-wrapper').length);
var AddressBox = Number($('.address-box').length);
$(document).ready(function () {
    // Guest login
    $("#guestForm button").on('click', function(e) {
        e.preventDefault();
        var formData = new FormData($('#guestForm')[0]);

        if($('#guestEmail').length) {
            $.ajax({
                type: "POST",
                url: FLORAL_AJAX + "ajx.php?case=guestLogin",
                data: formData,
                processData: false,
                contentType: false,
                success: function (data) {
                    var rsjson = $.parseJSON(data);
    
                    if(rsjson.results.error === 0) {
                        window.location.reload();
                    }
    
                    if(rsjson.results.error === 1) {
                        $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                        var toastMsg = $.cookie("toastMsg");
                        if(toastMsg) {
                            $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                            $.removeCookie('toastMsg', { path: '/' });
                        }
                    }
                }
            });
            return false;
        } 
    });

    // Update address
    $("form#updateAddress").submit(function(e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);

        if($(this).valid()){
            if($('.addAddress').length) {
                $.ajax({
                    type: "POST",
                    url: FLORAL_AJAX + "ajx.php?case=addUserAddress",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var rsjson = $.parseJSON(data);
        
                        if(rsjson.results.error === 0) {
                            $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                        }
        
                        if(rsjson.results.error === 1) {
                            console.log(rsjson.results.msg);
                        }
                    }
                });
                return false;
            } 
            else {
                var ID = $(this).parents('.address-form').attr('data-address');
                $.ajax({
                    type: "POST",
                    url: FLORAL_AJAX + "ajx.php?case=updateUserAddress&ID="+ ID,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        var rsjson = $.parseJSON(data);
    
                        if(rsjson.results.error === 0) {
                            $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                        }
    
                        if(rsjson.results.error === 1) {
                            console.log(rsjson.results.msg);
                        }
                    }
                });
                return false;
            }
        }
    });

    $(document).on('click', '#enableWallet', function (e) {
        let finalPay = $('#finalPay').val();
        if ($('#enableWallet').is(':checked')) {
            var prc = finalPay - JSON.parse($('.walletbag').text());
            $('.uwallet').val('1');
            $('.wlltAm').val(JSON.parse($('.walletbag').text()));
            $('.walletBalance').text(JSON.parse($('.walletbag').text()) -  $('.finalbag').text());
            if (JSON.parse($('.walletbag').text()) > $('.finalbag').text()) {
                $('.finalbag').text(0);
            } else {
                $('.finalbag').text(prc);
                $('.walletBalance').text(0);
            }
        } else {
            $('.wlltAm').val(0);
            $('.uwallet').val('0');
            $('.walletBalance').text($('.walletbag').text());
            $('.finalbag').text(finalPay);
        }
    });
});

function checkShippingAddress(){
    $('.cart-products').each(function(){
        if($(this).find('.address-box').length){
            $('.address-box').each(function(){
                if($(this).find('.btn-deliver').hasClass('selected')){
                    isAddressSet = true;
                    return false;
                }else{
                    isAddressSet = false;
                }
            });
        }else{
            isAddressSet = false;
            return isAddressSet;
        }
    });
}

function checkWalletPrice() {
    var UserID = $('#PID').val();
    var finalPay = $('#finalPay').val();
    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=fetchWalletDetails&UserID=" + UserID + "&TransactionCurrency=INR",
        type: "POST",
        success: function(data) {
            var rsjson = $.parseJSON(data);
            if(rsjson.results.CreditValue > finalPay) {
                isWalletSet = true;
            } else {
                isWalletSet = false;
            }
        }
    });
}

$(document).on('change', '.h_119', function () {

    var cityID = $(this).find('option:selected').attr('data-cityID');
    var self = $(this);

    if(cityID != undefined) {
        $.ajax({
            url: FLORAL_AJAX + "ajx.php?case=fetchStateForCity&CityID=" + cityID,
            type: "POST",
            success: function(data) {
                var rsjson = $.parseJSON(data);

                self.parents('form').find('.h_1110').val(rsjson.results.StateName);
                self.parents('form').find('.h_1112').val(rsjson.results.CountryName);
                self.parents('form').find('.h_11121').val(rsjson.results.CountryCode);
            }
        });
    }
});

$(document).on('click', '#po22212', function () {
    checkShippingAddress();
    if(isAddressSet) {
        checkWalletPrice();
        if(shippingForm.valid() && billingForm.valid()) {
            
            var trgr = $("#shippingFormSubmit");    
            $('.ad1').val(trgr.find('[name="type"]').val());
            $('.ad2').val(trgr.find('[name="fName"]').val());
            $('.ad3').val(trgr.find('[name="lName"]').val());
            $('.ad4').val(trgr.find('[name="blockNo"]').val());
            $('.ad5').val(trgr.find('[name="streetName"]').val());
            $('.ad6').val(trgr.find('[name="areaName"]').val());
            $('.ad7').val(trgr.find('[name="landmark"]').val());
            $('.ad8').val(trgr.find('[name="city"]').val());
            $('.ad9').val(trgr.find('[name="state"]').val());
            $('.ad10').val(trgr.find('[name="postCodeBilling"]').val());
            $('.ad11').val(trgr.find('[name="country"]').val());
            $('.ad13').val(trgr.find('[name="countryCode"]').val());
            $('.ad14').val(trgr.find('[name="MobileNumberBilling"]').val());

            if(isWalletSet) {
                var wltHtml = $('#pyu12PP').html();
                $('#wltu12PP').html(wltHtml);
                $('#wltu12PP').submit();
            } else {
                if($('input[name=chkPayment]:checked').length > 0) {
                    $('.payments1110').each(function() {
                        if($(this).is(':checked')) {
                            $(this).parents('.custom-radio').find('form').submit();
                        }
                    });
                } else {
                    $.snackbar({content: "Please select any one payment method", timeout: 5000, style: 'toast'});
                }
            }  
        }
    }else{
        $.snackbar({content: "Please save and select your delivery address", timeout: 5000, style: 'toast'});
    }
});