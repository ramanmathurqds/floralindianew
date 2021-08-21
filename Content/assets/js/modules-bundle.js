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


$(document).ready(function () {

    if(localStorage.getItem("crncySetSymb") !== '' && localStorage.getItem("crncySetPrc") !=='') {
        CURRENCY_LOGO = localStorage.getItem("crncySetSymb");
    }

    if($('.cart-page').length){
        var floralCash = 0;
        floralCash = parseInt($('.subtotalbag').text()) * 5 / 100
        $('.floral-coin').text(Math.round(floralCash));
    }
    var toastMsg = $.cookie("toastMsg");
    if(toastMsg) {
        $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
        $.removeCookie('toastMsg', { path: '/' });
    }

    $(document).on('click', '.btn-add-cart', function (e) {
        e.preventDefault();
        AddToCart('add');
    });

    $(document).on('click', '.btn-cart-item-remove', function (e) {
        e.preventDefault();
        var CartID = $(this).parents('.add-drop-items').attr('cart-id');
        deleteCart('removeCart', CartID);
    });

    $(document).on('click', '.btn-express-checkout', function (e) {
        e.preventDefault();
        AddToCart('checkout');
    });

    $(document).on('click', '.addon-wrapper .button-addon', function (e) {
        e.preventDefault();
        var sec = $(this).parents('.addon-item');
        AddonAddToCart('addon', sec);
    });

    $('#CakePhotoFile').on("change", function(){
        uploadPhotoImage();
    });

    function uploadPhotoImage() {
        var formData = new FormData();
        formData.append('CakePhotoFile', $('#CakePhotoFile')[0].files[0]);

        $.ajax({
            type: "POST",
            url: FLORAL_AJAX + "ajx.php?case=addPhotoCakeImage",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                var rsjson = $.parseJSON(data);

                if(rsjson.results.error === 0) {
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    setTimeout(function(){
                        var toastMsg = $.cookie("toastMsg");
                        if(toastMsg) {
                            $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                            $.removeCookie('toastMsg', { path: '/' });
                        }
                    }, 500);
                    $('#CakePhotoFileInput').val(rsjson.results.filename);
                    $('.photoImg').show();
                    $('.photoImg img').attr('src', DOMAIN + '/Content/assets/images/photoCakeImages/' + rsjson.results.filename);
                }

                if(rsjson.results.error === 1) {
                    console.log(rsjson.results.msg);
                }
            }
        });
    }

    function AddonAddToCart(Type, el) {
        var action = Type;
        var CartProduct = new Object();

        if(action === 'addon') {
            CartProduct.action = action;
            CartProduct.ProductCategoryID = $('#ChildProductCategoryID').val();
            CartProduct.productType = el.find("#addonProductCategoryID").val();
            CartProduct.City = CITY;
            CartProduct.ProductID = el.find("#addonProductID").val();
            CartProduct.Feature = '';
            CartProduct.Feature = '';
            CartProduct.Type = '';
            CartProduct.ProductQty = '1';
            CartProduct.ChildProductCategoryID = '';
            CartProduct.SenderMessage = '';
            CartProduct.CustomType = '';
            CartProduct.CaptionMessage = '';
            CartProduct.PackingPrice = '';
            CartProduct.AnonymousPerson = 1;
            CartProduct.SenderName = '';
            CartProduct.RecieverName = '';
            CartProduct.TimeSlotCharges = '0';
            CartProduct.DeliveryDate = $("#selectedDeliveryDate").val();
            CartProduct.DeliveryTimeText = $("#ui-id-1 :selected").text();
            CartProduct.DeliveryTimeSlot =  $("#selectedDeliveryTime").val();

            CartProduct.ParentProductID = $('#ProductID').val();
            CartProduct.ParentProductType = $("#addonProductCategoryID").val();
            CartProduct.ParentDeliveryDate = $("#selectedDeliveryDate").val();
            CartProduct.ParentTimeSlotCharges = atob($('#_flash_1454_11').val());

            if(el.length) {
                CartProduct.ProductSizePrice = el.find("#addonProductSizePrice").val();
            }

            $.ajax({
                type: "POST",
                url: FLORAL_AJAX + "ajx.php?case=addonAddToCart",
                data: JSON.stringify(CartProduct),
                processData: false,
                contentType: false,
                success: function (data) {
                    var rsjson = $.parseJSON(data);
    
                    if(rsjson.results.error === 1) {

                    }
    
                    if(rsjson.results.error === 0) {
                        $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                        var toastMsg = $.cookie("toastMsg");
                        if(toastMsg) {
                            $.snackbar({content: toastMsg, timeout: 3000, style: 'toast'});
                            $.removeCookie('toastMsg', { path: '/' });
                        }
                        // window.location.reload();
                    }
    
                }, error: function (err) {
                    console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
                }
            });
        }
    }

    function AddToCart(Type) {
        var action = Type;
        var CartProduct = new Object();

        var productType = $("#ProductCategoryID").val();
        var cakesType = $("#CakeType").val();

        CartProduct.City = CITY;
        CartProduct.action = action;
        CartProduct.productType = productType;
        CartProduct.ProductID = $('#ProductID').val();
        CartProduct.ProductQty = $('.txt-qty').val();
        CartProduct.ChildProductCategoryID = $('#ChildProductCategoryID').val();
        CartProduct.SenderMessage = $('#giftSendingMessage').val();
        CartProduct.CustomType = '';
        CartProduct.CaptionMessage = '';
        CartProduct.ParentProductID = 0;

        CartProduct.TimeSlotCharges = atob($('#_flash_1454_11').val());
        if($('.size-options').length) {
            CartProduct.ProductSizePrice = atob($('#_flash_1454_12').val());
        }

        CartProduct.PackingPrice = atob($('#_flash_1454_13').val());
        CartProduct.AnonymousPerson = 1;
        CartProduct.SenderName = '';
        CartProduct.RecieverName = '';

        if ($('#giftSenderName').val() != '') {
            CartProduct.SenderName = $('#giftSenderName').val();
            CartProduct.RecieverName = $('#giftRecieverName').val();
            CartProduct.AnonymousPerson = 0;
        }

        CartProduct.DeliveryDate = $("#selectedDeliveryDate").val();
        CartProduct.DeliveryTimeText = $("#ui-id-1 :selected").text();
        CartProduct.DeliveryTimeSlot = $("#selectedDeliveryTime").val();

        if (productType === "92") {

            CartProduct.Size = ($('#selectedSize').val()) ? $('#selectedSize').val() : null;
            CartProduct.CaptionMessage = $('.txt-location').val();
            CartProduct.Feature = "";

            $('.baking-list input[type="checkbox"]:checked').each(function (index) { 
                CartProduct.Feature += ($(this).val() + "|") 
            });

            CartProduct.Feature = CartProduct.Feature.substring(0, CartProduct.Feature.lastIndexOf('|'))
            CartProduct.Type = ($("#ui-id-2 :selected").val()) ? $("#ui-id-2 :selected").val() : null;

            if (cakesType == "Photo Cake") {
                CartProduct.CustomType = "photo"
                var fileUpload = $("#CakePhotoFileInput").val();

                CartProduct.CakePhotoFile = fileUpload;

            }
            else if (cakesType == "Digit Cake") {
                CartProduct.CustomType = "digit|" + $("#ui-id-3 :selected").val();
            }
        }
        else if (productType === "91") {
            CartProduct.Size = ($('#selectedSize').val()) ? $('#selectedSize').val() : null;
            CartProduct.Feature = ($('input[name="colorSelection"]:checked').val()) ? $('input[name="colorSelection"]:checked').val() : null;
            CartProduct.Type = ($("#ui-id-2 :selected").val()) ? $("#ui-id-2 :selected").val() : null;
        }
        else if (productType === "94") {
            CartProduct.Size = ($('#selectedSize').val()) ? $('#selectedSize').val() : null;
            CartProduct.CaptionMessage = $("#ui-id-2 :selected").val();
            CartProduct.Feature = ($('input[name="colorSelection"]:checked').val()) ? $('input[name="colorSelection"]:checked').val() : null;
            CartProduct.Type = ($("#ui-id-3 :selected").val()) ? $('#ui-id-3 :selected').val() : null;
        }
        else if (productType === "97") {
            CartProduct.Size = ($('#selectedSize').val()) ? $('#selectedSize').val() : null;
            CartProduct.SellingPrice = $('.min-value').text();
        }

        $.ajax({
            type: "POST",
            url: FLORAL_AJAX + "ajx.php?case=addToCart",
            data: JSON.stringify(CartProduct),
            processData: false,
            contentType: false,
            success: function (data) {
                var rsjson = $.parseJSON(data);

                if(rsjson.results.error === 1) {
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    var toastMsg = $.cookie("toastMsg");
                    if(toastMsg) {
                        $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                        $.removeCookie('toastMsg', { path: '/' });
                    }
                }

                if(rsjson.results.error === 0) {
                    if (Type == 'checkout') {
                        var link = $('.btn-express-checkout').attr('href');
                        window.location.href = link;
                    } else {
                        $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                        var toastMsg = $.cookie("toastMsg");
                        if(toastMsg) {
                            $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                            $.removeCookie('toastMsg', { path: '/' });
                        }

                        setTimeout(function() {
                            $('#addon').modal();
                        }, 2000);
                        // window.location.reload();
                    }
                }

            }, error: function (err) {
                console.log("AJAX error in request: " + JSON.stringify(err, null, 2));
            }
        });
    }

    function deleteCart(action, CartID) {
        $.ajax({
            url : FLORAL_AJAX + "ajx.php?case=delteCart",
            data: { "action":action, "CartID":CartID },
            type: "POST",
            success: function(data) {
                var rsjson = $.parseJSON(data);
                if(rsjson.results.error === 0) {
                    $("#Cart-" + CartID).remove();
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    window.location.reload();
                }
            }
        });
    }

    $(document).on('click', '.cart-item-delete', function (e) {
        e.preventDefault();
        var CartID = $(this).parents('.cart-item').attr('cart-id');
        deleteCart('removeCart', CartID);
    });

    $(document).on('click', '.cart-count .minus', function (e) {
        e.preventDefault();

        var cartID = $(this).parents('.cart-item').attr('cart-id');
        var msg = $(this).parents('.cart-item').find('.item-message span').attr('data-message');
        var qty = $(this).parents('.cart-count-inner').find('#Quantity').val();
        modifyCart(cartID, qty, msg);
    });

    $(document).on('click', '.cart-count .plus', function (e) {
        e.preventDefault();

        var cartID = $(this).parents('.cart-item').attr('cart-id');
        var msg = $(this).parents('.cart-item').find('.item-message span').attr('data-message');
        var qty = $(this).parents('.cart-count-inner').find('#Quantity').val();
        modifyCart(cartID, qty, msg);
    });

    $(document).on('change', '.cart-count #Quantity', function (e) {
        e.preventDefault();

        var cartID = $(this).parents('.cart-item').attr('cart-id');
        var msg = $(this).parents('.cart-item').find('.item-message span').attr('data-message');
        var qty = $(this).val();
        modifyCart(cartID, qty, msg);
    });

    $(document).on('click', '.btn-gift-msg', function () {
        var cartID = $(this).parents('.cart-item').attr('cart-id');
        var qty = $(this).parents('.cart-item').find('#Quantity').val();
        var msg = $(this).siblings('.txt-gift-message').val();
        modifyCart(cartID, qty, msg);
        $(this).parent().siblings('.item-message').find('span').attr('data-message', msg);
        $(this).parent().siblings('.item-message').find('span').text(msg.substring(0, 30));
        $(this).parent().hide();
    });

    $(document).on('click', '#applyDiscount', function () {
        var val = $('#txtDiscountCode').val();
        validatePromoCode(val);
    });

    $(document).on('click', '.removePromo', function () {
        removePromoCode();
    });

    function removePromoCode() {
        $.ajax({
            url : FLORAL_AJAX + "ajx.php?case=removePromoCode",
            type: "POST",
            success: function(data) {
                var rsjson = $.parseJSON(data);
                if(rsjson.results.error === 0) {
                    $('#promoCode').html('');
                    var finalPrice = $('#finalPay').val();
                    $('.finalbag').text(finalPrice);
                    $('#pc2121').val('');
                    $('.erro_msg').text('').removeClass('green');
                }
            }
        });
    }

    function modifyCart(cartID, qty, msg) {
        $.ajax({
            url : FLORAL_AJAX + "ajx.php?case=editCart",
            data: { "CartID":cartID, "ProductQty":qty, "SenderMessage": msg},
            type: "POST",
            beforeSend: function() {
                $(".full-loader-image").show();
            },
            success: function(data) {
                var rsjson = $.parseJSON(data);
                if(rsjson.results.error === 0) {
                    // alert('Product cart updated');
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    window.location.reload();
                    ModifyCartValue(rsjson.results, cartID, qty);
                }
            }
        }).done(function() {
            $(".full-loader-image").hide();
        });
    }

    function validatePromoCode(val) {
        $.ajax({
            url : FLORAL_AJAX + "ajx.php?case=promoCodeValidator",
            data: { "PromoCode":val },
            type: "POST",
            success: function(data) {
                $('.erro_msg').removeClass('green').html('');
                var rsjson = $.parseJSON(data);
                
                if(rsjson.results.error === 1) {
                    $('.erro_msg').html(rsjson.results.msg);
                } else {
                    var finalPrice = $('#finalPay').val();

                    $('.erro_msg').addClass('green').html(rsjson.results.msg);

                    if(!$('.promoCodebag').length) {
                        $('#promoCode').append('<td class="color-secondary3">Promo code discount</td><td class="color-secondary3"><span>' + CURRENCY_LOGO + '</span> <span class="promoCodebag"> ' + rsjson.results.PromoDiscount + '</span> <span class="removePromo" title="Remove promo code"></span></td>');
                    }

                    $('#promoCode').removeClass('d-none');
                    $('.promoCodebag').text(rsjson.results.PromoDiscount);
                    $('#pc2121').val(rsjson.results.PromoDiscount);
                    $('.finalbag').text(parseInt(finalPrice) - parseInt(rsjson.results.PromoDiscount));
                    $('#txtDiscountCode').val('');
                }
            }
        });
    }

    function ModifyCartValue(results, cartID, qty) {
        var mrp = (Number(results.Mrp) + Number(results.ProductSizePrice)) * qty;
        var price = (Number(results.Price) + Number(results.ProductSizePrice)) * qty;
        var discount = 100 * Math.abs( (mrp - price) / mrp );
        $('#Cart-' + cartID +' .strike-price span').text(mrp);
        $('#Cart-' + cartID +' .sales-price span').text(price);
        $('#Cart-' + cartID +' .saleprice').val(price);
        $('#Cart-' + cartID +' .discount').text('-' + Math.round(discount) + '%');
        var total = 0;
        $('.cart-amt-time .sales-price').each(function() {
            var totl = $(this).find('span').text();
            total += parseInt( totl );
        });

        var sttl = 0;
        $('.cart-amt-time .strike-price').each(function() {
            var subtotl = $(this).find('span').text();
            sttl += parseInt( subtotl );
        });
        
        var shipping = 0;
        $('.cart-amt-time .shippingCharge').each(function() {
            var shipg = $(this).text();
            shipping += parseInt( shipg );
        });

        $('.totalbag').html(sttl);
        $('.subtotalbag').html(total);
        $('.discountbag').html(sttl - total);
        $('.shippingbag').html(shipping);
        $('.finalbag').html(total + shipping);
    }
});



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
//// grab an element
//var myElement = document.querySelector("header");
//// construct an instance of Headroom, passing the element
//var headroom = new Headroom(myElement);
//// initialise
//headroom.init();

var differencePrice;

//detect scroll up or down position and tewak in header
var lastScrollTop = 0;
$(window).scroll(function(event){
   if($(window).width() > 1023){
    var st = $(this).scrollTop();
    if (st > lastScrollTop){
        //down
        $('header, .page-content').addClass('pos-relative');
        $('.top-menu-wrapper, .menu-saperator, .alert-box').addClass('d-none');
        $('.main-menu-wrapper').addClass('mm-ws');
    } else {
        //up
        $('header, .page-content').removeClass('pos-relative');
        $('.top-menu-wrapper, .menu-saperator, .alert-box').removeClass('d-none');
        $('.main-menu-wrapper').removeClass('mm-ws');
    }
    lastScrollTop = st;
   }
});
window.onbeforeunload = function (e) {
    alert('ok');
}

function setApiCurrencyValue(param){
    $('.setDefaultCurrency').html(localStorage.getItem("crncySetSymb"));
    let currncyVal = localStorage.getItem("crncySetPrc");
    $(param).each(function(){
        let key = $(this).text();
        let val = key/currncyVal;
        $(this).text(Math.round(val));
    });
}

//check for first time visitor or new user to show country dialogue
$(function () {   

    if(localStorage.getItem("crncySetSymb") !== '' && localStorage.getItem("crncySetPrc") !=='' && localStorage.getItem("crncySetSymb") !== null && localStorage.getItem("crncySetPrc") !== null) {
        currencyConverter(localStorage.getItem("crncySet"));
    
        CURRENCY_LOGO = localStorage.getItem("crncySetSymb");
        setApiCurrencyValue('.setCurrBasedPrice');
    }
   
    if (localStorage.getItem("isNewVisit") === null) {
        localStorage.setItem("isNewVisit", true);
    }
    checkIsForstVisit();
    $('.footer-links').find('.col-6:nth-child(3)').toggleClass('col-6 col-12');

    $('.navicon').on('click', function () {
        $('.mobile-menu-box').addClass('mobile-menu-open');
        $('.overlay-bar').css({ 'display': 'block', 'z-index': '9994' });
    });

    $('.overlay-bar').on('click', function () {
        if ($('.mobile-menu-box').hasClass('mobile-menu-open')) {
            $('.mobile-menu-box').removeClass('mobile-menu-open');
        }
        closeOverlay();
    });

    //combobox
    setCustomSelect();
});

function setCustomSelect(){
    $.widget( "custom.iconselectmenu", $.ui.selectmenu, {
        _renderItem: function( ul, item ) {
          var li = $( "<li class='code'>" ),
            wrapper = $( "<div>", { text: item.label } );
   
          if ( item.disabled ) {
            li.addClass( "ui-state-disabled" );
          }
   
          $( "<span>", {
            style: item.element.attr( "data-style" ),
            "class": "ui-icon " + item.element.attr( "data-class" )
          })
            .appendTo( wrapper );
   
          return li.append( wrapper ).appendTo( ul );
        }
      });
    $(".has-combobox").iconselectmenu({
        change: function(event, ui){
            var drpImage = ui.item.element[0].dataset.image;
            $('.ui-selectmenu-image img').attr('src', drpImage);
        }
    }).iconselectmenu("menuWidget").addClass( "ui-menu-icons avatar" );
    $('.ui-selectmenu-text').before('<span class="ui-selectmenu-image"><img src="" /></span>')
    $('.has-combobox').siblings('.ui-selectmenu-button').addClass('form-control');
}

$(document).on('click', '.toggle-rapid-modal', function(e){
    e.preventDefault();
    var thisModal = $(this);
    if(thisModal.hasClass('country')){
        $('.rapid-mobile-modal').toggle();
        $('.rapid-country-selector').toggle();
    }
});

$(document).on('click', '.close-rapid-modal, .close-rapid-modal-btn', function(e){
    e.preventDefault();
    closeRapidModal();
});

//used for mobile main navigation modal
function closeRapidModal(){
    $('.common-rapid-modal').hide();
    $('.common-selector').hide();
}

function checkIsForstVisit() {
    var isFirstVisit = localStorage.getItem("isNewVisit");
    if (isFirstVisit === true || isFirstVisit === 'true') {
        //$('.change-country-dialogue').show();
    }
}

$(document).on('click', '.btn-country-dialogue', function () {
    $('.change-country-dialogue').hide(function () {
        $('.set-country').trigger('click');
    });
});

$(window).on('scroll', function () {
    var scrollPos = $(window).scrollTop();

    if ($(window).width() < 1024) {
        if (scrollPos > 50) {
            $('.page-content').css({ 'top': '40px' });
            $('header .store-logo').slideUp();
            $('header').css('height', 'auto');
        } else {
            $('.page-content').removeAttr('style');
            $('header .store-logo').slideDown();
            $('header').removeAttr('style');
        }

        //for mobile view filter/sorting 
        if ($('.sort-filter-box').length) {
            var startPoint = 800;
            var endPoint = $('.client-wrapper').offset().top - 300;
            if (scrollPos >= startPoint && scrollPos <= endPoint) {
                $('.sort-filter-box').removeClass('d-none');
            } else {
                $('.sort-filter-box').addClass('d-none');
            }
        }
    } else if ($(window).width() >= 1024) {
        //for categorypage filter button visibility
        if ($('.btn-filter').length) {
            var startPoint = 800;
            var endPoint = $('.client-wrapper').offset().top - 300;
            if (scrollPos >= startPoint && scrollPos <= endPoint) {
                $('.btn-filter').fadeIn();
            } else {
                $('.btn-filter').fadeOut();
            }
        }
    }

    if ($('.overhead').length) {
        if (scrollPos >= 200) {
            $('header').removeClass('t-header');
        } else {
            $('header').addClass('t-header');
        }
    }
});

//hide scrollbar when overlay is open
function hidescroll() {
    $('body').addClass('no-scroll');
}

//show scrollbar when overlay closed
function showscroll() {
    $('body').removeClass('no-scroll');
}

function getCityListFromApi(txtCity){
    var searchRequest = null;
        var minlength = 2;
        var that = txtCity,
        value = $(txtCity).val();
    
        $(".list-city").html("");
    
        if (value.length >= minlength) {
            if (searchRequest != null) searchRequest.abort();
            searchRequest = $.ajax({
                type: "GET",
                url: FLORAL_AJAX + "ajx.php?case=searchCityBasedOnCountry",
                data: {
                    CityName: value, CountryCode : COUNTRY_CODE
                },
                dataType: "text",
                beforeSend: function() {
                    // $(".list-city").html("<div class='cityLoader full-loader-image' style='display:block;'></div>");
                },
                success: function(msg) {
                    //we need to check if the value is the same
                    if (value == $(that).val()) {
                        var rsjson = $.parseJSON(msg);

                        $(".list-city").html("");
            
                        var data = "";
            
                        $.each(rsjson, function(key, value) {
                            if (value.error != 1) {
                                $.each(value, function(k, v) {
                                    data += '<li data-name="'+ v.CityName +'" data-id="'+ v.CityID +'">';
                                    data += v.CityName;
                                    data += "</li>";
                                });
                            } else {
                                data += '<div class="nodata"> No data found..!!</div>';
                            }
                        });

                        $(".list-city").append(data);
                    }
                }
            });
        } else {
            $(".list-city").html('');
        }
}

$(document).ready(function () {
    if ($('.no-order').length) {
        $('.order-stats-wrapper').hide();
    }

    // Search City based on country
    $(".txt-city").keyup(function() {
        getCityListFromApi(this);
    });


    //set selected value from dropdown in topheader
    $('.country-selector li button').on('click', function (e) {
        e.stopPropagation();
        localStorage.setItem("isNewVisit", false);
        if ($(this).parents('.has-drop').hasClass('set-country')) {
            var selectedCountryFlag = $(this).find('.country-list').attr('src');
            $('.country-flag').attr('src', selectedCountryFlag);
            $(this).parents('.add-drop').slideUp();
        }
    });

    //if city is empty force open city selector
    $('.btn-close-country-dialogue').on('click', function () {
        if (localStorage.getItem("selectedCityID") === null) {
            localStorage.setItem("isNewVisit", false);
            $('.city-click').trigger('click');
        } else {
            $('.drop-indicator').hide();
        }
    });

    if (localStorage.getItem("selectedCityID") !== null) {
        $('.selected-city').text(localStorage.getItem("selectedCityName"));
    } else {
        if (localStorage.getItem("isNewVisit") === false || localStorage.getItem("isNewVisit") === 'false') {
            $('.city-click').trigger('click');
        }
    }

    // if (localStorage.getItem("selectedCityID") !== null) {
    //     if ($('#isCityRefeshRequired').length) {
    //         refreshCityInSession(localStorage.getItem("country"));
    //     }
    // }

    //put active class on selected category for occasion
    $('.gift-category .btn-category').not('.redirect').on('click', function (e) {
        e.preventDefault();
        var selectedCategory = $(this).data('categoryid');
        $('.gift-category .btn-category').removeClass('active');
        $(this).addClass('active');
        loadSubCategory(selectedCategory);
    });    

    //set Country
    $('.country-selector li button').on('click', function () {
        let countryCode = $(this).data('code');
        $.ajax({
            url: FLORAL_AJAX + "ajx.php?case=fetchSelectedCountry&CountryCode=" + countryCode,
            type: "GET",
            success: function(data) {
                if($(window).width() > 1024){
                    window.location.reload();
                    localStorage.setItem('country', countryCode);
                }else{
                    $('.rapid-country-selector').hide();
                    $('.rapid-city-selector').show();
                    $('.city-selector').show();
                }
            }
        })
        return false;
    });

    //set Currency
    $('.currency-selector li').on('click', function () {
        let countryCode = $(this).data('code');
        let countryCurrency = $(this).data('name').match(/\((.*)\)/);
        $.ajax({
            url: FLORAL_AJAX + "ajx.php?case=setCurrency&CountryCode=" + countryCode,
            type: "GET",
            success: function(data) {
                var rsjson = $.parseJSON(data);
                localStorage.setItem("crncySetSymb", rsjson.results.CurrencyLogo);
                currencyConverter(countryCurrency[1]+'_INR');
               
            }
        }).done(function(){
            window.location.reload();
        })
        return false;
    });

    //get order history based on dates
    $('.order-count select').on('change', function () {
        let month = $(this).val();
        $.ajax({
            url: FLORAL_AJAX + "ajx.php?case=fetchOrderHistory&Month=" + month,
            type: "GET",
            success: function(data) {
                var rsjson = $.parseJSON(data);

                $(".order-card-section").html("");

                var history = "";

                $.each(rsjson, function(key, value) {
                    if (value.error != 1) {

                        $('.order-count .count').html(value.TotalCount);

                        $.each(value.Orders, function(k, v) {

                            let createdDate = new Date(v.CreatedDate);
                            var cdMonth = createdDate.toLocaleString('default', { month: 'short' });
                            let cd = createdDate.getDate() +' '+ cdMonth +' '+ createdDate.getFullYear();

                            let deliveryDate = new Date(v.DeliveryDate);
                            var ddMonth = deliveryDate.toLocaleString('default', { month: 'short' });
                            let dd = deliveryDate.getDate() +' '+ ddMonth +' '+ deliveryDate.getFullYear();

                            let totalPrice = parseInt(v.ProductPrice) + parseInt(v.PackingChrg) + parseInt(v.ShippingChrg) + parseInt(v.ProductSizePrice);

                            history += '<div class="order-card"><div class="order-card-head">';
                            history += '<div class="row">';
                            history += '<div class="col-4"><span class="order-head-title">Order Placed</span><br />';
                            history += cd;
                            history += '</div>';
                            history += '<div class="col-4"><span class="order-head-title">Total</span><br />';
                            history += '<span class="webCurrency">'+ CURRENCY_LOGO + ' ' + totalPrice +'</span>';
                            history += '</div>';
                            history += '<div class="col-4"><span class="order-head-title">Order #'+ v.OrderID +'</span><br />';
                            history += '<a class="primary-dark">Get Invoice</a>';
                            history += '</div>';
                            history += '</div>';
                            history += '</div>';

                            history += '<div class="order-card-body">';
                            history += '<p class="delivery-time">Delivery on '+ dd +' at '+ v.DeliveryTimeSlot +'<p>';
                            history += '<p class="delivery-to">Ship to - <a title="Deliver to" data-toggle="popover" data-html="true" data-placement="bottom" data-content="<p>'+ v.ShippingAddress['Address'] +'</p><p><strong>Message</strong> - Add greeting message here</p>" class="primary-dark pointer">'+ v.ShippingAddress['FirstName'] +' '+ v.ShippingAddress['LastName'] +' (See more)</a></p>';
                            history += '<div class="cart-item-wrapper">';
                            history += '<div class="cart-item mt-4">';
                            history += '<div class="row">';
                            history += '<div class="col-12 col-lg-8"><div class="dv-prod"><span class="qty">'+ v.ProductQty +'</span><img src="'+ DOMAIN + v.ProductImage +'" alt="'+ v.ProductName +'" class="img-product"></div><h3 class="product-title">'+ v.ProductName +'</h3><p class="small-description">'+ v.Size +'</p><p class="item-price"><span class="primary-dark"><span class="webCurrency">'+ CURRENCY_LOGO + '</span> '+ totalPrice +'</span></p></div>';
                            history += '</div>';
                            history += '<div class="col-12 col-lg-4"></div>';
                            history += '</div>';
                            history += '</div>';
                            history += '</div>';
                            history += '</div>';
                            history += '</div>';
                        });
                    } else {
                        history += '<div class="nodata"> No data found...!!</div>';
                    }
                });

                $(".order-card-section").append(history);
            }
        })
        return false;
    });

    //Add and Remove 
    $(document).on('click', '.product-block.inactive .whishlist', function (e) {
        e.preventDefault();
        var productID = $(this).parents('.product-block').attr('id').split("-");
        addWishList('add', productID[1]);
    });

    $(document).on('click', '.product-block.active .whishlist', function (e) {
        e.preventDefault();
        var productID = $(this).parents('.product-block').attr('id').split("-");
        addWishList('remove', productID[1], this);
    });

    function addWishList(action, productID, actionButton) {
        $.ajax({
            url : FLORAL_AJAX + "ajx.php?case=addUserWishlist",
            data: { "action":action, "ProductID":productID },
            type: "POST",
            success: function(data) {
                var rsjson = $.parseJSON(data);
                if(rsjson.results.error === 0) {
                    // alert(rsjson.results.msg);
                    $('#product-' + productID).toggleClass('active inactive');
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    var toastMsg = $.cookie("toastMsg");
                    if(toastMsg) {
                        $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                        $.removeCookie('toastMsg', { path: '/' });
                    }

                    if(action === 'remove'){
                        $(actionButton).parents('.wishlist-item').remove();
                    }

                    if(!$('.wishlist-item').length){
                        $('.empty-wishlist-panel').toggleClass('d-none');
                    }
                }
            }
        });
    }

    //filter subcat on homepage as selected tag
    $('.tabbed-category li a').on('click', function () {
        $('.tabbed-category li a').removeClass('active');
        $(this).addClass('active');

        var selectedTag = $(this).parent('li').data('categorytagid');
        console.log(selectedTag);
        var categoryid = $('.btn-category.active').data('categoryid');
        filterSubCat(selectedTag, categoryid);
    });

    //search and set city
    // $('#city').on('keyup', function () {
    //     let cc = localStorage.getItem('country');
    //     if (cc === null || cc === '' || cc === undefined) {
    //         cc = 'IN';
    //     }
    //     let cityVal = $(this).val();
    //     if (cityVal.length >= 2) {
    //         let value = $(this).val();

    //         if (value.length >= 2) {
    //             var text = $(this).val();
    //             var regex = new RegExp(text, 'ig');
    //             $('.list-city li').each(function () {
    //                 $(this).toggle(regex.test($(this).data('name')));
    //             });
    //         } else {
    //             $(".list-city li").show();
    //         }
    //     }
    // });

    $(document).on('click', '.list-city li', function () {
        localStorage.setItem('selectedCityID', $(this).data('id'));
        localStorage.setItem('selectedCityName', $(this).data('name'));
        setCity($(this).data('id')); 
        $('#city').val('');
    });

    if ($('.product-page').length) {
        createAutoDate();

        $('.date-select').on('click', function (){
            $('.unselected').removeClass('d-none');
            $('.selected').addClass('d-none');
            $('.selected').parents('label').removeClass('active');
            $('#selectedDeliveryDate').val($(this).data('date'));
            $('.time-slot').removeClass('d-none');
        });

        //product page xzoom initialization
        $('.xzoom4, .xzoom-gallery4').xzoom({ tint: '#006699', Xoffset: 15 });

        if ($('.xzoom-thumbs a').length <= 1) {
            $('.xzoom-thumbs a').hide();
        }

        // $('').on('click', function () {
        //     var xzoom = $(this).data('xzoom');
        //     xzoom.closezoom();
        //     $.fancybox.open(xzoom.gallery().cgallery, { padding: 0, helpers: { overlay: { locked: false } } });
        //     event.preventDefault();
        // });

        $('.size-select').each(function () {
            if ($(this).is(':checked') === true) {
                var thisID = $(this).attr('id');
                $('#' + thisID).trigger('click');
                return false;
            }
        });
        differencePrice = $('#_flash_m_final').val() - $('#_flash_p_final').val();
    }

    if ($('.overhead').length) {
        $('header').addClass('t-header');
    }
});

function currencyConverter(action) {
    $.ajax({
        url : "https://free.currconv.com/api/v7/convert",
        data: { "q":action, "compact":"ultra", "apiKey":"e79207d4e7db203c2ef5" },
        type: "GET",
        success: function(data) {
            localStorage.setItem("crncySetPrc", data[action]);
            localStorage.setItem("crncySet", action);
        }
    });
}

function loadSubCategory(categoryID) {
    var selectedTag = $('.tabbed-category').find('.active').parent('.col').data('categorytagid');
    filterSubCat(selectedTag, categoryID);
}

//filter subcat on homepage as selected tag
function filterSubCat(selectedTag, categoryID) {
    url = FLORAL_AJAX + "/admin-panel/API/floralapi.php?case=specialProducts&CityID=" + CITY_ID + "&ProductCategoryID=" + categoryID + "&"+ selectedTag,
    countryCode = $('countryCode').val();
    $('#ProductListPartial').html('');

    $.ajax({
        url: url,
        method:"GET",
        beforeSend: function() {
            $(".full-loader-image").show();
        },
        success: function(data) {
            if(data == '') {
                $('#ProductListPartial').html('<div class="col-12"><h3>No products found...!!</h3></div>');
                return false;
            }

            var rsjson = data;
            $('#ProductListPartial').html('');
            if(rsjson.results.error === 1) {
                $('#ProductListPartial').html('<div class="col-12"><h3>No products found...!!</h3></div>');
                return false;
            }
 
            var html = "";
            var active = '';
            var title = '';
            var heart = '';

            $.each(rsjson.results, function(k, b) {
                if(b['ActiveWishList'] == '1'){
                    active = 'active';
                    title = 'Remove from wishlist';
                    heart = 'fas';
                } else {
                    active = 'inactive';
                    title = 'Add to wishlist';
                    heart = 'far';
                }

                html += '<div class="col-6 col-sm-4 col-xl-3 custom-grid-device item mb-4 tagged-product" data-category='+ b['ProductCategoryID'] +'><div class="product-block '+ active +'" id="product-'+ b['ProductID'] +'">';
                    html += '<a href="'+ DOMAIN + COUNTRY_CODE +'/listing/'+ b['ProductName'].replace(/\s+/g, '-').toLowerCase() +'/'+ b['ProductCategoryID'] +'/'+ b['ProductID'] +'">';
                        html += '<div class="product-image-wrapper">'
                            html += '<div class="img-overlay"></div>'
                            html += '<img src="'+ DOMAIN +''+ b['ProductIamge'] +'" class="product-img" alt="'+ b['ProductName'] +'" />';
                        html += '</div>'

                        html += '<div class="product-info text-center"><div class="product-title">';
                            html += '<p>'+ b['ProductName'] +'</p>';
                            if(b['ProductShortDescription'] != '') {
                                html += '<span class="short-description">('+ b['ProductShortDescription'] +')</span>';
                            }
                            html += '</div><div class="product-price"><p><span class="webCurrency">'+ CURRENCY_LOGO + '</span> <span class="setCurrBasedPrice1">' + b['Price'] +'</span>&nbsp;&nbsp;&nbsp;<span class="cancelled-price"><span class="webCurrency">'+ CURRENCY_LOGO + '</span> <span class="setCurrBasedPrice1">'+ b['Mrp'] +'</span></span></p></div></div></a>';

                    //         html +=
                    //   '<div class="product-action"><span class="boxer rating-box"><span>(0 reviews)</span><span data-avg-rating="0" class="d-none d-lg-inline-block avg-rating"><i class="far fa-star"></i><i class="far fa-star"></i><i class="farfetchFilteredCustomListing fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span></span><a class="boxer share-box"><img src="' +
                    //   DOMAIN + '/Content/assets/images/common/share-icon.png"></a></div>';

                    var ratingCount = (b['ProductRatingCount'] > 0) ? b['ProductRatingCount'] : 0;
                    var ratingStars = (b['ratingCount'] > 0) ? b['ratingCount'] : 0;


                    html += '<div class="product-action">'
                        html += '<table class="extras">'
                            html += '<tr>'
                                html += '<td><span class="nowrap">('+ ratingCount +' reviews)</span>'
                                    html += '<span data-avg-rating="'+ Math.round(ratingStars) +'" class="d-none d-lg-inline-block avg-rating">'
                                    html += '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>'
                                    html += '</span>'
                                html += '</td>'

                                html += '<td>'
                                    html += '<a class="jq-tooltip" data-toggle="tooltip">'
                                        html += '<i class="fas fa-share-alt"></i>'
                                    html += '</a>'
                                html += '</td>'

                                html += '<td class="d-none d-lg-table-cell">'
                                    html += '<a title="View this product" href="'+ COUNTRY_CODE +'/product/'+ b['ProductName'].replace(/\s+/g, '-').toLowerCase() +'/'+ b['ProductCategoryID'] +'/'+ b['ProductID'] +'"><i class="fas fa-eye"></i></a>'
                                html += '</td>'

                                html += '<td>'
                                    html += '<a class="btn-fav whishlist" title="'+ title + '">'
                                        html += '<i class="'+ heart + ' fa-heart"></i>'
                                    html += '</a>'
                                html += '</td>'
                            html += '</tr>'
                        html += '</table>'
                    html += '</div>'

                html += '</div></div>';
                $('#ProductListPartial').html(html);
            });
            scrollTop('#ProductListPartial');
        }
    }).done(function() {
        $(".full-loader-image").hide();
        if(localStorage.getItem("crncySetSymb") !== '' && localStorage.getItem("crncySetPrc") !=='' && localStorage.getItem("crncySetSymb") !== null && localStorage.getItem("crncySetPrc") !== null) {
            setApiCurrencyValue('.setCurrBasedPrice1');
        }
    });
}   

$(document).on('keyup', function (e) {
    if (e.keyCode === 27) {
        closeOverlay();
    }
});

//common ovelray close
function closeOverlay() {
    $('.common-overlay').hide();
    $('.overlay-bar').removeAttr('style').hide();
    $('body').removeClass('no-scroll');
}

//counts submenus inner sections to set its width dynamically
$(function () {
    $('.main-menu li').each(function () {
        var countCol = 0;
        countCol = $(this).find('.col').length;
        if (countCol >= 4) {
            $(this).find('.sub-drop-container').addClass('four');
        } else if (countCol === 3) {
            $(this).find('.sub-drop-container').addClass('three');
        } else if (countCol === 2) {
            $(this).find('.sub-drop-container').addClass('two');
        } else if (countCol === 1) {
            $(this).find('.sub-drop-container').addClass('one');
        }
    });
});

//filter country as per search
$(function () {
    $('.txt-custom-search, .currency-custom-search').on('keyup', function () {
        var value = $(this).val();

        if ($(this).val().length >= 2) {
            var text = $(this).val();
            var regex = new RegExp(text, 'ig');
            $('.country-selector li, .currency-selector li').each(function () {
                $(this).toggle(regex.test($(this).data('name')));
            });
        } else {
            $(".country-selector li, .currency-selector li").show();
        }
    });
});

//average rating star assigner
$(function () {
    var avgRating = $('.avg-rating');
    if ($(avgRating).length) {
    $(avgRating).each(function () {
        var thisRating = $(this).data('avg-rating');
        var iSelector = $(this).find('i').slice(0, thisRating);
        $(this).find(iSelector).toggleClass('far fas');
    });   
    }
});

//add to wishlist
$(document).on('click', '.btn-fav', function () {
    $(this).find('i').toggleClass('fas far');
});

//open search
$(document).on('click', '.btn-search', function () {
   $('.master-search').css('top','0');
   $('.txt-search').focus();
});

function closeSearchBox() {
    $('.master-search').removeAttr('style');
    $('#search_result').html('').addClass('d-none');
    $('body').css('overflow', 'auto');
    $('.txt-search').val('');
}

$(document).on('click', '.close-search', function () {
    closeSearchBox();
});

$(document).on('click touchend', '.child-page',function (event) {
    if (!$(event.target).closest(".master-search").length) {
        closeSearchBox();
    } 
});

//toggle right menu dropdown
$(document).on('click', '.has-add-drop', function () {
    $('.add-drop').not($(this).find('.add-drop')).hide();
    $(this).find('.add-drop').not('.change-country-dialogue').toggle();
});


$(document).on('click', '.add-drop', function (e) {
    e.stopPropagation();
});

$(document).on('click', '.close-drop', function () {
    $('.add-drop').fadeOut();
});

//set city
function setCity(cityID) {
    // $('.selected-city').text(selectedCity);
    // localStorage.setItem('selectedCity', selectedCity);
    $('.add-drop').fadeOut();
    refreshCityInSession(cityID);
}

// set city
function refreshCityInSession(selectedCityID) {
    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=setCity&CityID=" + selectedCityID,
        type: "GET",
        success: function(data) {
            window.location.replace(FLORAL_AJAX);
        }
    })
    return false;
}

// price range slider for filters
$(function () {
    if ($('.custom-range-slider').length) {
        var minValue = $('.min-value').data('min');
        var maxValue = $('.max-value').data('max');
        $('.min-value').text(minValue);
        $('.max-value').text(maxValue);
        $('#slider-container').slider({
            range: true,
            min: minValue,
            max: maxValue,
            values: [minValue, maxValue],
            create: function () {
                jQuery("#amount").val("300 - 30000");
            },
            slide: function (event, ui) {
                jQuery("#amount").val("$" + ui.values[0] + "$" + ui.values[1]);
                var mi = ui.values[0];
                var mx = ui.values[1];
                $('#hidden_minimum_price').val(ui.values[0]);
                $('#hidden_maximum_price').val(ui.values[1]);
                // filterSystem(mi, mx);
                $('.min-value').text(mi).css({ left: $('#slider-container .ui-slider-handle:nth-child(2)').css('left') });
                $('.max-value').text(mx).css({ right: $('#slider-container .ui-slider-handle:last-child').css('right') });
            },
            stop: function( event, ui ) {
                getFilteredProducts();
            }
        });
    }

    // function filterSystem(minPrice, maxPrice) {
    //     jQuery("li.column").hide().filter(function () {
    //         var price = parseInt(jQuery(this).data("price"), 10);
    //         return price >= minPrice && price <= maxPrice;
    //     }).show();
    // }
});

//set price slider
$(function () {
    var handle = $("#custom-handle");
    $('#cSlider').append('<div class="ui-slider-handle"><svg height="18" width="14"><path d="M13,9 5,1 A 10,10 0, 0, 0, 5,17z"/></svg></div>');
    $("#cSlider").slider({
        orientation: "horizontal",
        animate: true,
        range:"min",
        min: 300,
        max: 30000,
        create: function () {
            $('.min-value').text($(this).slider("value"));
        },
        slide: function () {
            $('.min-value').text($(this).slider("value")).css({ left: $(handle).css('left')});
        }
    });
});


//auto date functon
function createAutoDate() {
    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    var dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat'];
    var dayCount =  $('.min-delivery-day').text();
    if ($('#isTodayDelivery').val() === 'false'){
        if (dayCount === '2' || dayCount ===2){
            dayCount = 2
        }else{
            dayCount = 1;
        }
    }
    $('.auto-date').each(function () {
        var d = new Date();
        d.setDate(d.getDate() + parseInt(dayCount));
        $(this).find('.d-date').text(d.getDate());
        $(this).find('.d-month').text(monthNames[d.getMonth()]);
        $(this).find('.d-day').text(dayNames[d.getDay()]);

        var dd = d.getDate();
        if (dd.toString().length === 1) {
            dd = '0' + dd;
        }

        var mm = d.getMonth() + 1;
        if (mm.toString().length === 1) {
            mm = '0' + mm;
        }
        $(this).find('.date-select').attr('data-date', d.getFullYear() + '-' + mm + '-' + dd);
        dayCount++;
    });
}
$(function () {
    //bs popover  init for share product
    $('[data-toggle="popover"]').popover();

    //datepicker general use
    if($('.profile-page, .reminder-page').length){
        var picker = datepicker('.js-picker', {
            dateFormat: "dd-mm-yy",
            onSelect: (instance, date) => {
                var selectedDate2 = new Date(date);
                //var dd = `${selectedDate2.getFullYear()}-${selectedDate2.getMonth()}-${selectedDate2.getDate()}`;
                var dd = `${selectedDate2.getFullYear()}-${((selectedDate2.getMonth() + 1) < 10) ? `0${(selectedDate2.getMonth() + 1)}` : (selectedDate2.getMonth() + 1)}-${(selectedDate2.getDate() < 10) ? `0${selectedDate2.getDate()}` : selectedDate2.getDate()}`;
                $('#editDOB').val(dd);
                $('#reminderDate').val(dd);
            }
        });
    }

    var tooltipPlacement;
    $(window).width() > 1024 ? tooltipPlacement = "right" : tooltipPlacement = "bottom"
    $('.tooltipped').tooltip({
        placement: tooltipPlacement,
        trigger: "focus"
   });
});

//close popover
$('body').on('click', function (e) {
    $('[data-toggle=popover]').each(function () {
        // hide any open popovers when the anywhere else in the body is clicked
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

$(document).on('click', '.popover-content a', function(e){
    if ($(this).hasClass('copy-link')){
        e.preventDefault();
        var copyText = $(this).attr('href');

        /* Select the text field */
        copyText.select();
        copyText.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");
    } 
    else if(!$(this).hasClass('email-share')){
        e.preventDefault();
        window.open($(this).attr('href'), "Share Product", "width=700,height=700");
    }
});

//login/signup switcher
$(document).on('click', '.btn-show-signup', function () {
    $('.reg-form').toggleClass('d-none');
    $('.login-form').toggleClass('d-none');
});

$(document).on('click', '.plus', function (e) {
    e.preventDefault();
    var x = $(this).parents('.qty-box').find('.txt-qty');
    if (x.val() <= 4) {
        x.val(parseInt(x.val()) + 1);
    } else {
        return false;
    }
});

$(document).on('click', '.minus', function (e) {
    e.preventDefault();
    var y = $(this).parents('.qty-box').find('.txt-qty');
    if (y.val() >= 2) {
        y.val(parseInt(y.val()) - 1);
    } else {
        return false;
    }
});

$(document).on('click', '.common-chk-controls', function () {
    var thisID = $(this).attr('id');
    if ($(this).is(':checked' === true)) {
        $('.' + thisID).toggleClass('d-none');
    } else {
        $('.' + thisID).toggleClass('d-none');
    }
});

$(document).on('click', '#enablePromoCode', function () {
    $(this).hide();
    $('.apply-promocode-wrapper').toggleClass('d-none');
});

$(document).on('click', '.item-message a', function () {
    var msg = $(this).siblings('span').data('message');
    $(this).parent().siblings('.message-box-wrapper').find('textarea').val(msg);
    $(this).parent().siblings('.message-box-wrapper').slideToggle();
});

//faq toggle
$(document).on('click', '.gift-options', function () {
    $(this).find('.btn-category').trigger('click');
});

$(document).on('click', '.faq-question', function () {
    $('.gifts-faqs').find('li').removeClass('active');
    $('.faq-answer').slideUp();
    $(this).parent('li').addClass('active');
    $('.faq-question').find('i').addClass('fa-plus');
    $(this).find('i').addClass('fa-minus').removeClass('fa-plus');
    $(this).siblings('.faq-answer').slideToggle();
});

$(document).on('click', '.gift-subcategory li', function () {
    if (!$(this).hasClass('nav-title')) {
        $('.gift-subcategory li').removeClass('active');
        $(this).addClass('active');
        var selected = $(this).data('target');
        $('.gifts-faqs').addClass('d-none');
        $(`${selected}`).removeClass('d-none');
        if ($(window).width() < 1024) {
            $('.gift-subcategory li').not(':first').hide();
        }
    } else {
        if ($(window).width() < 1024) {
            $(this).nextAll('li').show();
        }
    }
});
//faq toggle

$(function () {
    if ($('#case').val() == 'product') {
        addonItemSlider();
    }
});

$(document).ready(function () {
    //$('.addon-item .hover-overlay').hover(function () {
        //$(this).parent().toggleClass('expanded').siblings('.addon-info').toggleClass('d-none');
    //});

    if($('.home-page').length){
        setTimeout(function(){
            $('.gift-category .owl-item:first-child .btn-category').addClass('active');
        }, 1000);
    }
});

var checkoutTopSpace = 35 /*$('.cart-sidebox').offset().top*/;
$(function () {
    if ($('.checkout-page').length) {
        //checkout login/signup switcher
        $(document).on('click', '.login-signup-tab li', function () {
            $('.login-signup-tab li').removeClass('active');
            var thisID = $(this).attr('data-id');
            $(this).addClass('active');
            $('.li-div').addClass('d-none');
            $('#' + thisID).removeClass('d-none');
        });

        $(document).on('click', '.box-title', function () {
            $(this).siblings('.box-body').toggleClass('d-none');
        });
    }
});

$(window).on('scroll', function () {
    var scrollPos2 = $(window).scrollTop();
    if ($('.checkout-page').length) {
        if ($(window).width() >= 1024) {
            if (scrollPos2 >= checkoutTopSpace) {
                let mt = `-${checkoutTopSpace}px`;
                $('.cart-sidebox').css({ 'margin-top': mt });
            } else {
                $('.cart-sidebox').removeAttr('style');
            }
        }
    }
});

$("form#editPassword").submit(function(e) {
    e.preventDefault();
    var formData = new FormData($(this)[0]);

    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=passwordChange",
        type: "POST",
        data: formData,
        async: false,
        success: function(data) {
            var rsjson = $.parseJSON(data); 

            if (rsjson.results.error === 1) { 
                $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
            }

            if (rsjson.results.error === 0) {
                $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
            }
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
});

$("#search_product").keyup(function() {
    var searchRequest = null;
    var minlength = 3;
    var that = this,
      value = $(this).val();

    $("#search_result").html("");

    if (value.length >= minlength) {
      if (searchRequest != null) searchRequest.abort();
      searchRequest = $.ajax({
        type: "GET",
        url : FLORAL_AJAX + "ajx.php?case=search_product_list",
        data: {
            query: value,
            CityID: CITY_ID
        },
        beforeSend: function() {
            $(".full-loader-image").show();
        },
        success: function(msg) {
            //we need to check if the value is the same
            if (value == $(that).val()) {
                var rsjson = $.parseJSON(msg);

                $("#search_result").html("");

                var data = "";

                $('#search_result').removeClass('d-none');

                $.each(rsjson, function(key, value) {
                    if (value.error != 1) {
                        $.each(value, function(k, v) {
                            data += '<li class="list" data-id="product-' + v.ProductID + '"><a href="'+ DOMAIN +  '/' + COUNTRY_CODE + '/product/' + v.ProductName.replace(/\s+/g, '-').toLowerCase() + '/' + v.ProductCategoryID + '/' + v.ProductID + '" title="' + v.ProductName + '"><img src="'+ DOMAIN + v.ProductImage +'" alt="products" /><div><span class="search-item-title">' + v.ProductName + '</span><br/><span class="search-item-price"><span class="setDefaultCurrency">'+ v.Currency +'</span> <span class="setCurrBasedPrice2">'+ v.Price +'</span></span></div></a></li>';
                        });
                    } else {
                        data += '<li class="list"><a href="javscript:;"> No product found..!!</a></li>';
                    }
                });
                $('body').css('overflow', 'hidden');
                $("#search_result").append(data);
            }
        }
      }).done(function() {
        $(".full-loader-image").hide();
        if(localStorage.getItem("crncySetSymb") !== '' && localStorage.getItem("crncySetPrc") !=='' && localStorage.getItem("crncySetSymb") !== null && localStorage.getItem("crncySetPrc") !== null){
            // currencyConverter(localStorage.getItem("crncySet"));
            setApiCurrencyValue('.setCurrBasedPrice2');
        }
    });
    } else {
      $("#search_result").addClass('d-none');
    }
});

//open mobile sort
$(document).on('click', '.get-sort', function(){
    $('.overlay-bar').show();
    $('.drawer').addClass('bottom')
});

//close drawer
$(document).on('click', '.close-drawer', function(){
    $('.overlay-bar').hide();
    $('.drawer').removeClass('bottom')
});

//mobile menu category trigger
$(document).on('click', '.mobile-main-menu-anchor', function(){
    $(this).parent('.mobile-main-menu').find('.sub-drop-container').toggleClass('d-none');
});

$(document).on('click', '.mobile-main-menu .by-button', function(){
    $(this).parent('.list-box').find('li').not(this).toggleClass('d-none');
});

//common function to read cookies
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function submitContactForm(){
    var contactForm = $('#contactForm');
    if ($(contactForm).valid()) {
        var formData = new FormData($(contactForm)[0]);

        $.ajax({
            url : FLORAL_AJAX + "ajx.php?case=insertContactUs",
            data: formData,
            async: false,
            processData: false,
            contentType: false,
            type: "POST",
            success: function(data) {
                var rsjson = $.parseJSON(data);
                if(rsjson.results.error === 0) {
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    var toastMsg = $.cookie("toastMsg");
                    if(toastMsg) {
                        $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                        $.removeCookie('toastMsg', { path: '/' });
                    }

                    $('#contactForm input, #contactForm select, #contactForm textarea').val('');
                }
            }
        });
        return false;
    }
}

$(document).on('click', '.submitContact', function(){
    submitContactForm();
});

$(document).on('click', '.go-contact', function(){
    var contactSection = $('.get-contact').offset().top - 200;
    $('html, body').animate({
        scrollTop: contactSection
    }, 1000);
});

$(document).on('click', '.go-social', function(){
    var socialSection = $('.social-contact').offset().top - 200;
    $('html, body').animate({
        scrollTop: socialSection
    }, 1000);
});
//countdown for offer
$(function () {
    if ($('.offer-section').length) {
        // Set the date we're counting down to
        var countDownDate = new Date($('#offer1Expiry').val()).getTime();

        // Update the count down every 1 second
        var x = setInterval(function () {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"
            if (days < 1) {
                document.getElementById("offer1Count").innerHTML = hours + "h "
                    + minutes + "m " + seconds + "s ";
            } else {
                document.getElementById("offer1Count").innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";
            }

            // If the count down is over, write some text 
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("offer1Count").innerHTML = "EXPIRED";
            }
        }, 1000);
    }
});
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


$(document).on('click', '.btn-locate', function(){
  getCurrentLocation();
});

function getCurrentLocation(){
  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(callBackLatLong);
  } else {
      console.log("Geolocation is not supported by this browser.");
  }
}

function callBackLatLong(position){
  var lat = position.coords.latitude,
  long = position.coords.longitude;

  //Converter for lat and long for full address..
  latlongConverter(lat, long);
}

function latlongConverter(lati, longi){
  var geocoder = new google.maps.Geocoder();
  var latlng = { lat: parseFloat(lati), lng: parseFloat(longi) };

  geocoder.geocode({ 'location': latlng }, function (results, status) {
      if (status === 'OK') {
          if (results) {
              var mainAddress = "";
              for (var i = 0; i <= results.length - 1; i++) {
                  for (var a = 0; a <= results[i].address_components.length - 1; a++) {
                      if (results[i].address_components[a].types[0] === "locality") {
                          mainAddress = (results[i].address_components[a].long_name);
                      }
                  }
              }
              const urlParams = new URLSearchParams(window.location.search);
              const locationParam = urlParams.get('loc');
              if (locationParam) {
                  $('.txt-city').val(locationParam);
                  getCityListFromApi($('.txt-city'));
              }else {
                  $('.txt-city').val(mainAddress);
                  getCityListFromApi($('.txt-city'));
              }
          } else {
              console.log('No results found');
          }
      } else {
          console.log('Geocoder failed due to: ' + status);
      }
  });
}
$(function () {
    if ($('.home-page').length) {
        var catGrid = {
            categoery1: function () {
                var hotCat1 = $('#hotCat_1');
                $('.hotCat_1 .desk-img').attr('src', hotCat1.attr('data-image-desktop')).attr('alt', hotCat1.attr('data-category-name'));
                $('.hotCat_1 .mob-img').attr('src', hotCat1.attr('data-image-mobile')).attr('alt', hotCat1.attr('data-category-name'));
                $('.hotCat_1 .hvrbox-text-inner').text(hotCat1.attr('data-category-name'));
                $('.hotCat_1 .hotCatLink').attr('href', hotCat1.attr('data-link'));
            },

            categoery2: function () {
                var hotCat2 = $('#hotCat_2');
                $('.hotCat_2 .desk-img').attr('src', hotCat2.attr('data-image-desktop')).attr('alt', hotCat2.attr('data-category-name'));
                $('.hotCat_2 .mob-img').attr('src', hotCat2.attr('data-image-mobile')).attr('alt', hotCat2.attr('data-category-name'));
                $('.hotCat_2 .hvrbox-text-inner').text(hotCat2.attr('data-category-name'));
                $('.hotCat_2 .hotCatLink').attr('href', hotCat2.attr('data-link'));
            },

            categoery3: function () {
                var hotCat3 = $('#hotCat_3');
                $('.hotCat_3 .desk-img').attr('src', hotCat3.attr('data-image-desktop')).attr('alt', hotCat3.attr('data-category-name'));
                $('.hotCat_3 .mob-img').attr('src', hotCat3.attr('data-image-mobile')).attr('alt', hotCat3.attr('data-category-name'));
                $('.hotCat_3 .hvrbox-text-inner').text(hotCat3.attr('data-category-name'));
                $('.hotCat_3 .hotCatLink').attr('href', hotCat3.attr('data-link'));
            },

            categoery4: function () {
                var hotCat4 = $('#hotCat_4');
                $('.hotCat_4 .desk-img').attr('src', hotCat4.attr('data-image-desktop')).attr('alt', hotCat4.attr('data-category-name'));
                $('.hotCat_4 .mob-img').attr('src', hotCat4.attr('data-image-mobile')).attr('alt', hotCat4.attr('data-category-name'));
                $('.hotCat_4 .hvrbox-text-inner').text(hotCat4.attr('data-category-name'));
                $('.hotCat_4 .hotCatLink').attr('href', hotCat4.attr('data-link'));
            },

            categoery5: function () {
                var hotCat5 = $('#hotCat_5');
                $('.hotCat_5 .desk-img').attr('src', hotCat5.attr('data-image-desktop')).attr('alt', hotCat5.attr('data-category-name'));
                $('.hotCat_5 .mob-img').attr('src', hotCat5.attr('data-image-mobile')).attr('alt', hotCat5.attr('data-category-name'));
                $('.hotCat_5 .hvrbox-text-inner').text(hotCat5.attr('data-category-name'));
                $('.hotCat_5 .hotCatLink').attr('href', hotCat5.attr('data-link'));
            },

            categoery6: function () {
                var hotCat6 = $('#hotCat_6');
                $('.hotCat_6 .desk-img').attr('src', hotCat6.attr('data-image-desktop')).attr('alt', hotCat6.attr('data-category-name'));
                $('.hotCat_6 .mob-img').attr('src', hotCat6.attr('data-image-mobile')).attr('alt', hotCat6.attr('data-category-name'));
                $('.hotCat_6 .hvrbox-text-inner').text(hotCat6.attr('data-category-name'));
                $('.hotCat_6 .hotCatLink').attr('href', hotCat6.attr('data-link'));
            },

            categoery7: function () {
                var hotCat7 = $('#hotCat_7');
                $('.hotCat_7 .desk-img').attr('src', hotCat7.attr('data-image-desktop')).attr('alt', hotCat7.attr('data-category-name'));
                $('.hotCat_7 .mob-img').attr('src', hotCat7.attr('data-image-mobile')).attr('alt', hotCat7.attr('data-category-name'));
                $('.hotCat_7 .hvrbox-text-inner').text(hotCat7.attr('data-category-name'));
                $('.hotCat_7 .hotCatLink').attr('href', hotCat7.attr('data-link'));
            }
        };

        catGrid.categoery1();
        catGrid.categoery2();
        catGrid.categoery3();
        catGrid.categoery4();
        catGrid.categoery5();
        catGrid.categoery6();
        catGrid.categoery7();
    }
});
var clientCarosuel = $('#clientCarosuel');
var categoryCarosuel = $('.categoryCarosuel');
var giftCategoryCarosuel = $('.giftCategoryCarosuel');
var addonItemCarosuel = $(".addonCarosuel");
var offerCarosuel = $('#offerCarosuel');
var testimonialSlider = $('#testimonialSlider');


var clientSlider = function () {
    if ($('.owl-carousel').length) {
        $(clientCarosuel).owlCarousel({
            loop: true,
            margin: 50,
            responsiveClass: true,
            autoplay: 2000,
            lazyload: true,
            //dots: true,
            responsive: {
                0: {
                    items: 3,
                    nav: true
                },
                600: {
                    items: 4,
                    nav: true
                },
                1000: {
                    items: 8,
                    nav: true,
                }
            },
            navText: ['<img src="/Content/assets/images/common/prev.png">', '<img src="/Content/assets/images/common/next.png">']
        });
    }
};

//for homePage
var categorySlider = function () {
    if ($(window).width() >= 1024) {
        $(categoryCarosuel).owlCarousel({
            loop: true,
            margin: 0,
            responsiveClass: true,
            dots: false,
            lazyload: true,
            scrollPerPage: true,
            mouseDrag: false,
            touchDrag: false,
            responsive: {
                1000: {
                    items: 6,
                    nav: true,
                    loop: false
                }
            },
            navText: ['<img src="/Content/assets/images/common/prev.png">', '<img src="/Content/assets/images/common/next.png">']
        });        
    }
};

//for categoryPage
var listingPageSlider = function () {
    if ($(window).width() >= 1024) {
        $(giftCategoryCarosuel).owlCarousel({
            loop: true,
            margin: 0,
            responsiveClass: true,
            dots: false,
            responsive: {
                1000: {
                    items: 6,
                    nav: true,
                    loop: false,
                    dots: true,
                }
            },
            navText: ['<img src="/Content/assets/images/common/prev.png">', '<img src="/Content/assets/images/common/next.png">']
        });
    }
};

//addonItem on Cartpage
var addonItemSlider = function () {
    if ($('.owl-carousel').length) {
        $(addonItemCarosuel).owlCarousel({
            loop: true,
            margin: 30,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2,
                    nav: true
                },
                600: {
                    items: 3,
                    nav: true
                },
                1000: {
                    items: 4,
                    nav: true,
                    loop: false
                },
                1300: {
                    items: 5,
                    nav: true,
                    loop: false
                },
                1920: {
                    items: 6,
                    nav: true,
                    loop: false
                }
            },
            navText: ['<img src="/Content/assets/images/common/prev.png">', '<img src="/Content/assets/images/common/next.png">']
        });
    }
};

//about us page
var aboutOffer = function(){
    if ($('.owl-carousel').length) {
        offerCarosuel.owlCarousel({
            center: true,
            items:1,
            loop:true,
            margin:20,
            responsiveClass:true,
            dots: false,
            responsive:{
                600:{
                    items:1,
                    nav:false,
                },
                800:{
                    items:2,
                    nav:true,
                }
            },
            navText: ['<img src="/Content/assets/images/common/prev.png">', '<img src="/Content/assets/images/common/next.png">']
        });
    }
}

//about-testimonials
var aboutTestimonials = function(){
    testimonialSlider.owlCarousel({
        center:false,
        items:1,
        loop:true,
        margin:10,
        responsiveClass:true,
        dots:true,
        responsive:{
            600:{
                items:1,
            },
            768:{
                items:3,
            }
        },
    });
}

$(function () {
    if ($('.home-page, .faq-page').length) {
        categorySlider();
    }

    if ($('.product-listing-page').length) {
        listingPageSlider();
    }

    if ($(clientCarosuel).length) {
        clientSlider();
    }

    if(offerCarosuel.length){
        aboutOffer();
    }

    if(testimonialSlider.length){
        aboutTestimonials();
    }
});
const reminderForm = $('#reminderForm');

$(document).on('click', '.get-reminder-form', function(e){
    e.preventDefault();
    if ($(this).data('action') === 'edit'){
        getEditForm(this);
    }
    $('.reminder-form, .list-container').toggleClass('d-none').promise().done(function(){
        if($('.reminder-form').hasClass('d-none')){
            $('.add-reminder').html('<i class="fas fa-plus"></i> Add Reminder');
        }else{
            $('.add-reminder').html('<i class="fas fa-angle-left"></i> Back to Reminders');
            $(reminderForm).trigger('reset').attr({'data-action':'new', 'data-id':'none'});
        }
    });
}).on('click', '.save-reminder', function(e){
    e.preventDefault();
    if($(reminderForm).valid()){
        var actionType = $('#reminderForm').data('action');
        saveReminderForm(actionType);
    }
}).on('click', '.delete-reminder', function(){
    if(confirm("Are you sure you want to delete this?")){
        deleteReminder();
    }
    else{
        return false;
    }
});

function getEditForm(reminder){
    let reminderID = $(reminder).data('id');
    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=editReminder&ID=" + reminderID,
        type: "POST",
        beforeSend: function() {
            $(".full-loader-image").show();
        },
        success: function(data) {
            let rsjson = $.parseJSON(data);
            $(reminderForm).attr({'data-action':'update', 'data-id' : reminderID});
            $('#remindeName').val(rsjson.results.ReminderName);
            $('#LocationName').val(rsjson.results.LocationName);
            $('#reminderCountryCode').val(rsjson.results.STD);
            $('#reminderContact').val(rsjson.results.ContactNumber);
            $('.ui-selectmenu-text').text(rsjson.results.STD);
            $('#EventCode').val(rsjson.results.EventCode);
            rsjson.results.EventCode === '0' ? $('#reminderEvent').val('0') : $('#reminderEvent').val(rsjson.results.Event);
            $('#txtReminderEvent').val(rsjson.results.Event);
            $('#reminderDate').val(rsjson.results.ReminderDate);
            $('#Notes').val(rsjson.results.Notes);
        }
    }).done(function() {
        $(".full-loader-image").hide();
        $(".has-combobox").iconselectmenu();
    });
}

function saveReminderForm(actionType){
    let formData = new FormData($(reminderForm)[0]);
   JSON.stringify(formData);
    if(actionType === 'update'){
        let reminderID = $(reminderForm).data('id');
        $.ajax({
            type: "POST",
            url: FLORAL_AJAX + "ajx.php?case=updateReminder&ID="+ reminderID,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".full-loader-image").show();
            },
            success: function (data) {
                let rsjson = $.parseJSON(data);
                if(rsjson.results.error === 0) {
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    location.reload();
                }

                if(rsjson.results.error === 1) {
                    console.log(rsjson.results.msg);
                }
            }
        }).done(function() {
            $(".full-loader-image").hide();
        });
    }else{
        $.ajax({
            type: "POST",
            url: FLORAL_AJAX + "ajx.php?case=addReminder",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".full-loader-image").show();
            },
            success: function (data) {
                var rsjson = $.parseJSON(data);

                if(rsjson.results.error === 0) {
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    location.reload();
                }

                if(rsjson.results.error === 1) {
                    console.log(rsjson.results.msg);
                }
            }
        }).done(function() {
            $(".full-loader-image").hide();
        });
    }
}

function deleteReminder(){
    let reminderID = $(reminderForm).data('id'),
    formData = new FormData($(reminderForm)[0]);
    $.ajax({
        type: "POST",
        url: FLORAL_AJAX + "ajx.php?case=deleteReminder&ID="+ reminderID,
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $(".full-loader-image").show();
        },
        success: function (data) {
            let rsjson = $.parseJSON(data);
            if(rsjson.results.error === 0) {
                $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                location.reload();
            }

            if(rsjson.results.error === 1) {
                console.log(rsjson.results.msg);
            }
        }
    }).done(function() {
        $(".full-loader-image").hide();
    });
}

$(document).on('change', '#reminderEvent', function(){
    $('#EventCode').val($(this).find(":selected").data('code'));
    setEventText($(this).find(":selected").text());
});

function setEventText(selectedEvent){
    if(selectedEvent === '0' || selectedEvent === 'Others'){ // 0 = Others
        $('#txtReminderEvent').parent('.form-group').removeClass('d-none');
        $('#txtReminderEvent').val('');
    }else{
        $('#txtReminderEvent').parent('.form-group').addClass('d-none');
        $('#txtReminderEvent').val(selectedEvent);
    }
}
function activaTab(tab){
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
    $('html, body').animate({
        scrollTop: $('#' + tab).offset().top - 300,
    }, 1000);
};

//common jquery ui scripts also added datepicker custom logic in this
$(function () {
    
    if ($('.product-page').length) {
        //coundown for todays delivery
        if($('#isTodayDelivery').val() === 'true'){
            // Set the date we're counting down to
            var theDay = new Date();
            var theMonth = theDay.getMonth() + 1;
            var theDate = theDay.getDate();
            var theYear = theDay.getFullYear();
            var countDownDate = new Date(""+theMonth+" "+theDate+", "+ theYear +" 18:00:00").getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();
                
            // Find the distance between now and the count down date
            var distance = countDownDate - now;
                
            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
            $('.countdown-timer').text((hours <= 9 ? '0' + hours : hours) + ":"+ (minutes <=9 ? '0' + minutes : minutes) + ":" + (seconds <= 9 ? '0' + seconds : seconds) + "Hrs. left for today's delivery");
                
            if (distance < 0) {
                clearInterval(x);
                $('.today-timer').html('<a href="#">View products for sameday delivery</a>');
                $('#isTodayDelivery').val('false');
                createAutoDate();
            }
            //setDeliveryDatePicker();
            }, 1000);
        }

        //product page - navigate to description tab
        $(document).on('click', '.more-desc a', function (e) {
            e.preventDefault();
            activaTab('tab2');
        });

        //remove qtybox for main categories
        var catID = Number($('#ProductCategoryID').val());
        if(catID === 91 || catID === 92 || catID === 94 || catID === 97){
            $('.common-size-box').remove();
        }else{
            $('.common-size-box').show();
        }

        //disable inactive size
        $('.size-box').each(function(){
            if($(this).data('active') == 0){
                $(this).find('.size-select').attr('disabled', 'disabled')
            }
        })
        
        //select and trigger first size by default
        $('.size-box').each(function () {
            if($(this).find('#size1').attr('checked'))
            $(this).find('img').attr('src', $(this).find('img').data('selected'));
        });

        var waterMarkURL = $("#selectedWatermark").val();
        applyWatermark(waterMarkURL);
        
        
        $('.drop-time-slot').on('selectmenuchange', function() {
            var drpSelectedTimeSlot = $('.drop-time-slot option:selected');
            $('.delivery-title').text(drpSelectedTimeSlot.text());

            var deliveryID = $(this).val();
            var SelectedDeliveryDate = $('#selectedDeliveryDate').val();

            $.ajax({
                type: "POST",
                url: FLORAL_AJAX + "ajx.php?case=fetchDeliveryTimeSlots",
                data: { "DeliveryID":deliveryID, "DeliveryDate": SelectedDeliveryDate },
                success: function (data) {
                    var rsjson = $.parseJSON(data);
                    var html = '';

                    if (rsjson.results[0].Charges) {
                        var _hsh_code = btoa(rsjson.results[0].Charges);
                        $('#_flash_1454_11').val(_hsh_code);
                    }

                    $.each(rsjson.results, function(k, b) {
                        var ct = new Date();
                        //var currTimeInhour = ct.getHours()+'.'+ct.getMinutes();
                        var currTimeInhour = $('#currentTime').val();
                        var d = new Date();
                        var day = d.getDate();
                        var month = d.getMonth() + 1;
                        var year = d.getFullYear();
                        var selectedDeliveryDate = $('#selectedDeliveryDate').val();
                        var today =  year +'-'+ (month <= 9 ? "0" + month : month) +'-'+ (day <= 9 ? "0" + day : day);

                        $('.delivery-cost').text(b['Charges']);
                        if(localStorage.getItem("crncySetSymb") !== null && localStorage.getItem("crncySetPrc") !== null) {
                            let currncyVal = localStorage.getItem("crncySetPrc");
                            let key = b['Charges'];
                            let val = key/currncyVal;
                            $('.delivery-cost').text(Math.round(val));
                        }

                        if(today != selectedDeliveryDate) {
                            //show all time slot available for selected delivery date
                            html += '<li><input type="radio" id="slot-'+ b['ID'] +'" class="slot-btn" data-charges="'+ b['Charges'] +'" data-maxtime="'+ b['MaxTime'] +'" name="rdoTimeSlots"><label for="slot-'+ b['ID'] +'">'+ b['TimeSlot'] +'</label></li>';
                        } else {
                            // when delivery date and current date are same show only available time slots
                            if(((parseInt(b['InitiateTime']) - 4) > currTimeInhour)){
                                html += '<li><input type="radio" id="slot-'+ b['ID'] +'" class="slot-btn" data-charges="'+ b['Charges'] +'" data-maxtime="'+ b['MaxTime'] +'" name="rdoTimeSlots"><label for="slot-'+ b['ID'] +'">'+ b['TimeSlot'] +'</label></li>';
                            }
                        }

                        $('.time-slot-nav').html(html);
                        if($('.time-slot-nav').html() === ''){
                            $('.no-slots').removeClass('d-none');
                        }else{
                            $('.no-slots').addClass('d-none');
                        }
                    });
                }
            });

            $('#timeSlotModal').modal('toggle');
        });

        $(document).on('click', '.slot-btn', function() {
            if($(this).is(':checked')) {
                var rupee = $('.setDefaultCurrency').html();
                var selectedDeliveryMaxTime = $(this).data('maxtime');
                var selectedTimeSlotBox = $('.drop-time-slot').siblings('.ui-widget').children('.ui-selectmenu-text');
                var actualCharges = $(this).data('charges');

                if(localStorage.getItem("crncySetSymb") !== null && localStorage.getItem("crncySetPrc") !== null) {
                    let currncyVal = localStorage.getItem("crncySetPrc");
                    let key = $(this).data('charges');
                    let val = key/currncyVal;
                    actualCharges = Math.round(val);
                }

                selectedTimeSlotBox.text(''); //empty selected time dropdown value first
                $('#selectedDeliveryMaxTime').val(selectedDeliveryMaxTime);
                $('#selectedDeliveryTime').val($(this).siblings('label').text());
                selectedTimeSlotBox.text($('.drop-time-slot option:selected').text() + ' (' + $(this).siblings('label').text() + ') - ' + rupee + '' + actualCharges);
                $('#timeSlotModal').modal('toggle');
            }
        });

        //size selector
        $(document).on('click', '.size-select', function () {
            $('#selectedSize').val($(this).data('value'));
            $('.size-box').each(function () {
                $(this).find('img').attr('src', $(this).find('img').data('unselected'));
            });

            $('.size-info').find('.size-name').removeClass('primary-dark');
            if ($(this).is(':checked')) {
                $(this).siblings('.item-tile').find('img').attr('src', $(this).siblings('.item-tile').find('img').data('selected'));
                $(this).siblings('.size-info').find('.size-name').addClass('primary-dark');
                $('.final-price').text(parseInt($('#staticFinalPrice').val()) + parseInt($(this).siblings('.size-info').find('.size-price-actual').text()));
                setTimeout(function () {
                    $('.final-mrp-price').text(parseInt($('.final-price').text()) + parseInt(differencePrice));

                    if(localStorage.getItem("crncySetSymb") !== null && localStorage.getItem("crncySetPrc") !== null) {
                        let currncyVal = localStorage.getItem("crncySetPrc");
                        let key = $('.final-mrp-price').text();
                        let val = key/currncyVal;
                        let key1 = $('.final-price').text();
                        let val1 = key1/currncyVal;
                        $('.final-mrp-price').text(Math.round(val));
                        $('.final-price').text(Math.round(val1));
                    }
                }, 1);

                var filterID = $(this).attr('catFilterID');
                var productID = $('#ProductID').val();

                $.ajax({
                    type: "POST",
                    url: FLORAL_AJAX + "ajx.php?case=eventFilter",
                    data: { "CategoryFilterID":filterID, "ProductID": productID },
                    success: function (data) {
                        var rsjson = $.parseJSON(data);
                        if(rsjson.results.error === 0 && rsjson.results.sizePrice.toString()) {
                            var _hsh_code = btoa(rsjson.results.sizePrice);
                            $('#_flash_1454_12').val(_hsh_code);
                        }
                    }
                });
            } else {
                $(this).siblings('.size-info').find('.size-name').removeClass('primary-dark');
            }
        });

        //toggle eggless icon if eggless checked
        $(document).on('click', '#chk-Eggless', function (){
            var chkValue = $(this).val();
            if($(this).is(':checked')){
                $(this).next('label').html('').html(chkValue + ' ' + $('.baking-list').attr('data-eggless-src'))
            }else{
                $(this).next('label').html(chkValue)
            }
        });

        $('.drop-packing').on('selectmenuchange', function() {
            var packingID = $('.drop-packing option:selected').attr('catFilterID');
            var productID = $('#ProductID').val();

            $.ajax({
                type: "POST",
                url: FLORAL_AJAX + "ajx.php?case=eventFilter",
                data: { "CategoryFilterID":packingID, "ProductID": productID },
                success: function (data) {
                    var rsjson = $.parseJSON(data);
                    if (rsjson.results.error === 0 && rsjson.results.packingPrice) {
                        var _hsh_code = btoa(rsjson.results.packingPrice);
                        $('#_flash_1454_13').val(_hsh_code);
                    }
                }
            });
        });

        /* 1. Visualizing things on Hover - See next part for action on click */
        $('#stars li').on('mouseover', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

            // Now highlight all the stars that's not after the current hovered star
            $(this).parent().children('li.star').each(function(e){
            if (e < onStar) {
                $(this).addClass('hover');
            }
            else {
                $(this).removeClass('hover');
            }
            });
        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
            $(this).removeClass('hover');
            });
        });

        /* 2. Action to perform on click */
        $('#stars li').on('click', function(){
            var onStar = parseInt($(this).data('value'), 10); // The star currently selected
            var stars = $(this).parent().children('li.star');
            
            for (i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }
            
            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }
            
            // JUST RESPONSE (Not needed)
            var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
            $('#productRating').val(ratingValue);

            // var msg = "";
            // if (ratingValue > 1) {
            //     msg = "Thanks! You rated this " + ratingValue + " stars.";
            // }
            // else {
            //     msg = "We will improve ourselves. You rated this " + ratingValue + " stars.";
            // }
            // responseMessage(msg);
            
        });

        // function responseMessage(msg) {
        //     $('.success-box').fadeIn(200);  
        //     $('.success-box div.text-message').html("<span>" + msg + "</span>");
        // }

        //iinsert user review for product
        $("form#reviewForm button").on('click', function(e) {
            e.preventDefault();
            var reviewForm = $('#reviewForm');
            if ($(reviewForm).valid()) {
                var formData = new FormData($(reviewForm)[0]);
                $.ajax({
                    url: FLORAL_AJAX + "ajx.php?case=addProductReview",
                    type: "POST",
                    data: formData,
                    processData: false,
                    async: false,
                    contentType: false,
                    success: function(data) {
                        var rsjson = $.parseJSON(data);

                        if(rsjson.results.error === 0) {
                            $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                            var toastMsg = $.cookie("toastMsg");
                            if(toastMsg) {
                                $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                                $.removeCookie('toastMsg', { path: '/' });
                            }
                        }
                    }
                })
                return false;
            }
        });

        setDeliveryDatePicker();
    }

    //dropdown
    $(".jq-drop").selectmenu();
});

$(document).on('click', '.btn-message', function(){
    var giftSendingMessage = $('#giftSendingMessage').val();
    if(giftSendingMessage !== ''){
        $('.btn-gift-message span').text(giftSendingMessage.substring(0, 30) + '....');
    }else{
        $('.btn-gift-message span').text($('.btn-gift-message').data('title'));
    }
    $('#giftMessage').modal('hide');
});

function setDeliveryDatePicker(){
    var d2 = new Date();
        var dayCount123 = parseInt($('.min-delivery-day').text());
        if ($('#isTodayDelivery').val() === 'false'){
            if (dayCount123 === 2){
                dayCount123 = 2
            }else{
                dayCount123 = 1;
            }
        }
        var picker = datepicker('.js-picker', {
            maxDate: new Date(d2.getFullYear(), d2.getMonth(), (d2.getDate() + 30)),
            minDate: new Date(d2.getFullYear(), d2.getMonth(), (d2.getDate() + dayCount123)),
            dateFormat: "yy-mm-dd",
            onSelect: (instance, date) => {
                var selectedDate2 = new Date(date);
                var thisYear = selectedDate2.getFullYear();
                var thisMonth = selectedDate2.getMonth() <= 9 ? `${selectedDate2.getMonth()}` : selectedDate2.getMonth();
                var thisDate = selectedDate2.getDate() <= 9 ? `${selectedDate2.getDate()}` : selectedDate2.getDate();
                var dd = `${thisYear}-${thisMonth + 1}-${thisDate}`;
                var selectedDate = ""+ date + "";
                selectedDate = selectedDate.split(' ');
                $('.has-datepicker .d-day').text(selectedDate[0]);
                $('.has-datepicker .d-month').text(selectedDate[1]);
                $('.has-datepicker .d-date').text(selectedDate[2]);
                $('.item-tile-inner .selected').removeClass('d-none');
                $('.item-tile-inner .unselected').addClass('d-none');
                $('.date-select').prop('checked', false);
                $('.selected').parents('label').addClass('active');
                console.log(dd);
                $('#selectedDeliveryDate').val(dd);
                $('.time-slot').removeClass('d-none');
            },
            onShow: instance => {
                // Do stuff when the calendar is shown.
                // You have access to the datepicker instance for convenience.
            },
            onHide: instance => {
                // Do stuff once the calendar goes away.
                // You have access to the datepicker instance for convenience.
            },
            formatter: (input, date, instance) => {
                var value = date.toLocaleDateString();
                input.value = value // => '1/1/2099'
            }
        });
}

function applyWatermark(selectedWatermark) {
    if ($('.img_awesome').length) {
        $('.img_awesome').watermark({
            //text: 'Floral India',
            //textWidth: 400,
            //textSize: 40,
            path: selectedWatermark,
            gravity: 'c',
            opacity: 1,
            margin: 12,
            outputWidth: 'auto',
            outputHeight: 'auto'
        });
    }
}

$(document).on('click', '.load-video', function(){
    var videoPlayer = `<video id="productVideo" controls style="width:100%">
                            <source src="${$(this).attr('href')}" type="video/mp4">
                            Your browser does not support HTML video.
                        </video>`

    $('.main-image img').hide();
    $('.main-image video').remove();
    $('.main-image').append(videoPlayer);
    document.getElementById("productVideo").load();
    $('html').scrollTop(0);
    $("#productVideo").prop('autoplay', true);
}).on('click', '.load-image', function(){
    $('.main-image video').remove();
    $('.main-image img').show();
});
$(function(){

    if(localStorage.getItem("crncySetSymb") !== '' && localStorage.getItem("crncySetPrc") !=='') {
        CURRENCY_LOGO = localStorage.getItem("crncySetSymb");
    }

    $('#clear-filters').click(function(){
        clearAll();
        $('.btn-close').trigger('click');
        scrollTop('#ProductListPartial');
    });

    $('#selectBox').on('change', function() {
        getFilteredProducts(1);
    });

    //fetch filtered products on category listing page
    $('.common_selector').click(function() {
        var page = $(this).data('page_number');
        var selector = $(this).attr('data-name');

        if($(this).val() == 'oneDay' && $(this).is(":checked")){
            $('#filter_delivery').val('1');
        } else {
            $('#filter_delivery').val('');
        }

        if($(this).is(":checked")) {
            set_filters(selector);
        } else {
            if(!$(this).is(":checked")){
                $('#'+selector).parent('li').remove();
                if($('.filter-tags li').length === 0) {
                    $('.selected-filters').hide();
                }
            }
        }
        getFilteredProducts(page);
    });

    $(document).on('click', '.c-page-link', function(){
        var page = $(this).data('page_number');
        getFilteredProducts(page);
    });

    hidePaginaton();
});

//mobile sorting trigger
$(document).on('click', '.btn-li-sort', function(){
    var selectedSort = $(this).data('value');
    $('#selectBox').val(selectedSort);
    var page = $(this).data('page_number');
    getFilteredProducts(1);
});

function hidePaginaton() {
    var count = $('.totalProducts').text();
    if(parseInt(count) <= 32) {
        $('.c-main-pagination').hide();
    } else {
        $('.c-main-pagination').show();
    }
}

//fetch filtered products on category listing page
function getFilteredProducts(page_num) {
    page_num = page_num ? page_num : 0;
    //var minimum_price = ($('#hidden_minimum_price').val() > 300) ? $('#hidden_minimum_price').val() : '';
    //var maximum_price = ($('#hidden_maximum_price').val() < 30000) ? $('#hidden_maximum_price').val() : '';
    var minimum_price = $('#hidden_minimum_price').val();
    var maximum_price = $('#hidden_maximum_price').val();
    var oneDay = $('#filter_delivery').val();
    var ProductCategoryID = $('#ProductCategoryID').val();
    var sort = $('#selectBox').find(":selected").val();
    var listType = $('#listType').val();
    var subMenuOfMenu = $('#subMenuOfMenu').val();
    var menuName = $('#menuName').val();
    var countryCode = $('#countryCode').val();
    var filter_items = get_filter('filter_items');
    var postData = '';

    postData = {ProductCategoryID:ProductCategoryID, minimum_price:minimum_price, maximum_price:maximum_price, filter_items:filter_items, oneDay:oneDay, sort:sort, page:page_num, CityID:CITY_ID, ListType:listType, ProductSubCategoryName:subMenuOfMenu, MenuName:menuName};

    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=fetchFilteredCustomListingCount",
        method:"POST",
        data: postData,
        success: function(data) {
            if(data == '') {
                return false;
            }

            var rsjson = $.parseJSON(data);
            if(rsjson.results.error === 1) {
                $('.totalProducts').html('0');
                $('.totalLinks').html('0');
                $('.start').html('0');

                hidePaginaton();

                return false;
            }

            $('.pagination').remove();
 
            $('.totalProducts').html(rsjson.results.TotalProducts);
            $('.totalLinks').html(rsjson.results.TotalLinks);
            $('.start').html(rsjson.results.Page);
            
            $('.c-main-pagination').html(rsjson.results.PaginationHtml);

            hidePaginaton();
        }
    });


    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=fetchFilteredCustomListing",
        method:"POST",
        data: postData,
        beforeSend: function() {
            $(".full-loader-image").show();
        },
        success: function(data) {
            if(data == '') {
                $('#ProductListPartial').html('<div class="col-12"><h3>No products found...!!</h3></div>');
                return false;
            }

            var rsjson = $.parseJSON(data);
            $('#ProductListPartial').html('');
            if(rsjson.results.error === 1) {
                $('#ProductListPartial').html('<div class="col-12"><h3>No products found...!!</h3></div>');
                return false;
            }
 
            var html = "";
            var active = '';
            var title = '';
            var heart = '';

            $.each(rsjson.results.Products, function(k, b) {
                if(b['ActiveWishList'] == '1'){
                    active = 'active';
                    title = 'Remove from wishlist';
                    heart = 'fas';
                } else {
                    active = 'inactive';
                    title = 'Add to wishlist';
                    heart = 'far';
                }

                html += '<div class="col-6 col-sm-4 col-xl-3 item custom-grid-device"><div class="product-block '+ active +'" id="product-'+ b['ProductID'] +'">';
                    html += '<a href="'+ DOMAIN +'/'+ countryCode +'/product/'+ b['ProductName'].replace(/\s+/g, '-').toLowerCase() +'/'+ b['ProductCategoryID'] +'/'+ b['ProductID'] +'">';
                        html += '<div class="product-image-wrapper">'
                            html += '<div class="img-overlay"></div>'
                            html += '<img src="'+ DOMAIN +''+ b['ProductIamge'] +'" class="product-img" alt="'+ b['ProductName'] +'" />';
                        html += '</div>'

                        html += '<div class="product-info text-center"><div class="product-title">';
                            html += '<p>'+ b['ProductName'] +'</p>';
                            if(b['ProductShortDescription'] != '') {
                                html += '<span class="short-description">('+ b['ProductShortDescription'] +')</span>';
                            }
                            html += '</div><div class="product-price"><p><span class="webCurrency">'+ CURRENCY_LOGO + '</span> ' + b['Price'] +'&nbsp;&nbsp;&nbsp;<span class="cancelled-price"><span class="webCurrency">'+ CURRENCY_LOGO + '</span> '+ b['Mrp'] +'</span></p></div></div></a>';

                    //         html +=
                    //   '<div class="product-action"><span class="boxer rating-box"><span>(0 reviews)</span><span data-avg-rating="0" class="d-none d-lg-inline-block avg-rating"><i class="far fa-star"></i><i class="far fa-star"></i><i class="farfetchFilteredCustomListing fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i></span></span><a class="boxer share-box"><img src="' +
                    //   DOMAIN + '/Content/assets/images/common/share-icon.png"></a></div>';

                    var ratingCount = (b['ProductRatingCount'] > 0) ? b['ProductRatingCount'] : 0;
                    var ratingStars = (b['ratingCount'] > 0) ? b['ratingCount'] : 0;


                    html += '<div class="product-action">'
                        html += '<table class="extras">'
                            html += '<tr>'
                                html += '<td><span class="nowrap">('+ ratingCount +' reviews)</span>'
                                    html += '<span data-avg-rating="'+ Math.round(ratingStars) +'" class="d-none d-lg-inline-block avg-rating">'
                                    html += '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>'
                                    html += '</span>'
                                html += '</td>'

                                html += '<td>'
                                    html += '<a class="jq-tooltip" data-toggle="tooltip">'
                                        html += '<i class="fas fa-share-alt"></i>'
                                    html += '</a>'
                                html += '</td>'

                                html += '<td class="d-none d-lg-table-cell">'
                                    html += '<a title="View this product" href="'+ countryCode +'/product/'+ b['ProductName'].replace(/\s+/g, '-').toLowerCase() +'/'+ b['ProductCategoryID'] +'/'+ b['ProductID'] +'"><i class="fas fa-eye"></i></a>'
                                html += '</td>'

                                html += '<td>'
                                    html += '<a class="btn-fav whishlist" title="'+ title + '">'
                                        html += '<i class="'+ heart + ' fa-heart"></i>'
                                    html += '</a>'
                                html += '</td>'
                            html += '</tr>'
                        html += '</table>'
                    html += '</div>'

                html += '</div></div>';
                $('#ProductListPartial').html(html);
            });

            scrollTop('#ProductListPartial');
        }
    }).done(function() {
        $(".full-loader-image").hide();
    });
}

function get_filter(class_name) {
    var filter = [];
    $('.'+class_name+':checked').each(function(){
        filter.push($(this).val().split('-').slice(1).join('.'));
    });
    return filter;
}

function set_filters(class_name) {
    var html = '<li>'+ class_name +' <button class="btn-remove-filter" id="'+ class_name +'" type="button"><i class="fas fa-times"></i></button></li>';
    $('.filter-box-wrapper .filter-tags').append(html);
    if($('.filter-tags li').length >= 1) {
        $('.selected-filters').show();
    }
}

function clearAll(){
    $('input.filter_items:checkbox:checked').prop('checked', false);
    $('#hidden_minimum_price').val('300');
    $('#hidden_maximum_price').val('30000');
    $('#filter_delivery').val('');
    $("#slider-container").slider("values", "0", "300");
    $("#slider-container").slider("values", "1", "30000");
    $('.min-value').css('left', '0').text($('.min-value').data('min'));
    $('.max-value').css('right', '-5px').text($('.max-value').data('max'));
    getFilteredProducts();
    $('.filter-tags').html('');
}

function scrollTop(val) {
    $('html, body').animate({
        scrollTop : $(val).offset().top - 180
    }, 400);
}

function removeFilter(val) {
    $('.filter-section').find("[data-name='"+ val +"']").trigger('click');
    if($('.filter-tags li').length === 0) {
        $('.selected-filters').hide();
    }
}

//filter activator
$(document).on('click', '.btn-filter, .get-filter', function () {
    $('.overlay-bar').show();
    // hidescroll();
    $('.btn-filter').addClass('d-none');
    $('.filter-box').addClass('filter-box-open');
    $('body .page-content').addClass('filter-open');
});

//filter collapse
$(document).on('click', '.filter-section:not(:first-child) .filter-title', function () {
    $('.filter-item').slideUp();
    $('.filter-collapse').text('+').removeAttr('style');
    $(this).next('.filter-item').slideToggle();
    $(this).prev('.filter-collapse').text('-').css({'font-size': '50px', 'top': '-10px', 'line-height': 'normal'});
});

//close filter
$(document).on('click', '.filter-header .btn-close', function () {
    $('.overlay-bar').hide();
    showscroll();
    $('.btn-filter').removeClass('d-none');
    $('.filter-box').removeClass('filter-box-open');
    $('body .page-content').removeClass('filter-open');
});

$(document).on('click', '.btn-remove-filter', function(){
    var filterbox = $(this).attr('id');
    $(this).parent().remove();
    removeFilter(filterbox);
});

$(document).on('selectmenuchange','.sorting.jq-drop', function(){
    getFilteredProducts(1);
});
function registration() {
    var regForm = $('#regForm');
    if ($(regForm).valid()) {
        var formData = new FormData($(regForm)[0]);

        $.ajax({
            url: FLORAL_AJAX + "ajx.php?case=signupUser",
            type: "POST",
            data: formData,
            async: false,
            processData: false,
            contentType: false,
            success: function(data) {
                var rsjson = $.parseJSON(data);
                // alert(rsjson.results.msg);

                if (rsjson.results.flag === 0) {
                    $('.invalid-signup-msg').toggleClass('d-none');
                    $('.signup-valid-msg').addClass('d-none');
                    return false;
                } 
                if (rsjson.results.error === 0){
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    $('.signup-valid-msg').toggleClass('d-none');
                    $('.invalid-signup-msg').addClass('d-none');
                    window.location.reload();
                }

                if (rsjson.results.error === 1) {
                    return false;
                }
            },
            cache: false,
        });
    }
}

$(document).on('click', '.btn-register', function (e) {
    e.preventDefault();
    registration();
});

function login(button) {
    var logForm = $(button).parents('form').attr('id');

    if ($('#' + logForm).valid()) {
        var formData = new FormData($('#' + logForm)[0]);

        $.ajax({
            url: FLORAL_AJAX + "ajx.php?case=loginFunction",
            type: "POST",
            data: formData,
            async: false,
            processData: false,
            contentType: false,
            success: function(data) {
                var rsjson = $.parseJSON(data);
                // alert(rsjson.results.msg);

                if (rsjson.results.error === 0) {
                    window.location.reload();
                } else {
                    $('.invalid-login-msg, .custom-field-alert').toggleClass('d-none');
                }
            },
            cache: false,
        });
        return false;
    }
}

$(document).on('click', '.btn-SignIn', function (e) {
    e.preventDefault();
    login(this);
});

function onSuccess(googleUser) {
    var cookie = $.cookie("google_token");
    if(cookie === undefined || cookie === 'undefined'){
        $.ajax({
            type: "POST",
            async: false,
            processData: false,
            contentType: false,
            url: FLORAL_AJAX + "ajx.php?case=socialLogin&socialEmail="+ googleUser.getBasicProfile().getEmail() +"&first_name="+ googleUser.getBasicProfile().getGivenName() +"&last_name="+ googleUser.getBasicProfile().getFamilyName() +"&ProfileImage="+ googleUser.getBasicProfile().getImageUrl() +"&loginType=gmailLogin",
            success: function (data) {
                $.cookie("google_token", googleUser.getBasicProfile().getId());
                window.location.reload();
            }
        });
    }
}

function onFailure(error) {
    console.log(error);
}

function gmailSignOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    delete_cookie('google_token');
    auth2.signOut().then(function () {
        auth2.disconnect();
    });
}

function delete_cookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function facebookLogout(){
    FB.logout(function(response) {
        document.location.reload();
    });
}

$(document).on('click', '.cls-logout', function(){
    websiteLogout();
})

function websiteLogout(){
    // User logout
    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=logout",
        success: function(data) {
            gmailSignOut();
            //delete amazon login from session
            delete_cookie('amazon_Login_state_cache');
            window.location.reload();
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return false;
}

$(document).on('click', '.fbLoginButton', function (e) {
    e.preventDefault();
    FB.getLoginStatus(function (response) {
        if (response.status == 'connected') {
            FB.api('/me?fields=email,name,first_name,last_name,picture.width(200).height(200)', function (userinfo) {
                // console.log('Good to see you, ' + userinfo.name + '.');
                $.ajax({
                    type: "POST",
                    async: false,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".full-loader-image").show();
                    },
                    url: FLORAL_AJAX + "ajx.php?case=socialLogin&socialEmail="+ userinfo.email +"&first_name="+ userinfo.first_name +"&last_name="+ userinfo.last_name +"&loginType=fbLogin&ProfileImage=",
                    success: function (data) {
                        // console.log(userinfo);
                        var rsjson = $.parseJSON(data);
                        if (rsjson.results.error === 0) {
                            window.location.reload();
                        }
                        else {
                            $('.invalid-login-msg').toggleClass('d-none');
                        }
                    }
                }).done(function(){
                    $(".full-loader-image").show();
                });
            });
        }
        else {
            FB.login(function (response) {
                if (response.authResponse) {
                    // console.log('Welcome!  Fetching your information.... ');
                    FB.api('/me?fields=email,name,first_name,last_name,picture.width(200).height(200)', function (userinfo) {
                        console.log('Good to see you, ' + userinfo.name + '.');
                        $.ajax({
                            type: "POST",
                            async: false,
                            processData: false,
                            contentType: false,
                            beforeSend: function() {
                                $(".full-loader-image").show();
                            },
                            url: FLORAL_AJAX + "ajx.php?case=socialLogin&socialEmail="+ userinfo.email +"&first_name="+ userinfo.first_name +"&last_name="+ userinfo.last_name +"&loginType=fbLogin&ProfileImage=",
                            success: function (data) {
                                // console.log(userinfo);
                                var rsjson = $.parseJSON(data);
                                if (rsjson.results.error === 0) {
                                    window.location.reload();
                                }
                                else {
                                    $('.invalid-login-msg').toggleClass('d-none');
                                }
                            }
                        }).done(function(){
                            $(".full-loader-image").show();
                        });
                    });
                }
            }, { scope: 'email', return_scopes: true });
        }
    });
});

$(document).on('click', '.amz-login', function(){
    setTimeout(window.doLogin, 1);
    return false;
});

window.doLogin = function() {
    options = {};
    options.scope = 'profile';
    options.pkce = true;
    amazon.Login.authorize(options, function(response) {
        if ( response.error ) {
            alert('oauth error ' + response.error);
        return;
        }
        amazon.Login.retrieveToken(response.code, function(response) {
            if ( response.error ) {
                alert('oauth error ' + response.error);
            return;
            }
            amazon.Login.retrieveProfile(response.access_token, function(response) {
                //alert(response.profile.Name + '<br>' + response.profile.PrimaryEmail + '<br>' + response.profile.CustomerId);
                $.ajax({
                    type: "POST",
                    async: false,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".full-loader-image").show();
                    },
                    url: FLORAL_AJAX + "ajx.php?case=socialLogin&socialEmail="+ response.profile.PrimaryEmail +"&first_name="+ response.profile.Name +"&last_name=" +"&loginType=amazon&ProfileImage=",
                    success: function (data) {
                        // console.log(userinfo);
                        var rsjson = $.parseJSON(data);
                        if (rsjson.results.error === 0) {
                            window.location.reload();
                        }
                        else {
                            $('.invalid-login-msg').toggleClass('d-none');
                        }
                    }
                });
            });
        });
    });
};

$(document).on('click', '.mob-account', function () {
    $('.mob-login-modal').toggleClass('d-none');
});

$(document).on('click', '.login-switcher', function () {
    $('.mob-login-modal').find('.login-reg').toggleClass('d-none');
});

//update user profile
$("form#editForm").submit(function(e) {
    e.preventDefault();
    var editForm = $('#editForm');
    if ($(editForm).valid()) {
        var formData = new FormData($(this)[0]);
        $.ajax({
            url: FLORAL_AJAX + "ajx.php?case=updateUserDetails",
            type: "POST",
            data: formData,
            processData: false,
            async: false,
            contentType: false,
            success: function(data) {
                var rsjson = $.parseJSON(data);

                if(rsjson.results.error === 0) {
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                }
            }
        })
        return false;
    }
});
//edit profile


$('#ProfileImage').on("change", function(){
    uploadUserPhotoImage();
});

function uploadUserPhotoImage() {
    var formData = new FormData();
    formData.append('ProfileImage', $('#ProfileImage')[0].files[0]);

    $.ajax({
        type: "POST",
        url: FLORAL_AJAX + "ajx.php?case=addUserImage",
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            var rsjson = $.parseJSON(data);

            if(rsjson.results.error === 0) {
                window.location.reload();
                $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                var toastMsg = $.cookie("toastMsg");
                if(toastMsg) {
                    $.snackbar({content: toastMsg, timeout: 5000, style: 'toast'});
                    $.removeCookie('toastMsg', { path: '/' });
                }
            }

            if(rsjson.results.error === 1) {
                // console.log(rsjson.results.msg);
            }
        }
    });
}
$(document).on('click', '.trackorder-page .btn-submit', function(){
    getProductTrackingStatus();
});

const getTimeAMPMFormat = (date) => {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let seconds = date.getSeconds();
    const ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    hours = hours < 10 ? '0' + hours : hours;
    // appending zero in the start if hours less than 10
    minutes = minutes < 10 ? '0' + minutes : minutes;
    return hours + ':' + minutes + ':' + seconds + '' + ampm;
};

//fetch tracking status of products
function getProductTrackingStatus() {
    var order_ID = $('.trackorder-page .txt-search').val();

    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=fetchOrdersWithTracking&OrderID="+ order_ID,
        method:"POST",
        cache: false,
        contentType: false,
        beforeSend: function() {
            $(".full-loader-image").show();
        },
        success: function(data) {
            var rsjson = $.parseJSON(data);
            $('.overpage-content .container').html('');
            if(rsjson.results.error === 1) {
                $('.overpage-content .container').html('<div class="col-12"><h3>No product found...!!</h3></div>');
                return false;
            }
 
            var html = "";

            $.each(rsjson.results, function(k, b) {
                let dd = new Date(b['DeliveryDate']);
                let ddM = dd.toLocaleString('default', { month: 'short' });
                let dDate = dd.getDate() +' '+ ddM +' '+ dd.getFullYear();
                html += '<div class="row order-item"><div class="col-12"><div class="tracking-header">'+ b['ProductName'] +' <span class="track-delivery-time">(Delivery on '+ dDate +' - '+ b['DeliveryTimeSlot'] +')</span></div></div>';
                html += '<div class="col-3 col-lg-2"><div class="order-item-image"><img src="'+ DOMAIN + b['ProductImage'] +'" class="img-fluid"><span class="item-qty">'+ b['ProductQty'] +'</span></div></div>';

                html += '<div class="col-9 col-lg-10"><div class="tracking-info"><div class="tracking-number">Order ID - '+ b['OrderID'] +'</div>';
        
                html += '<div class="horizontal-tracking">';

                    var firstStep, secondStep, thirdStep, fourthStep, fifthStep;
                
                    if(b['TrackingSubject'][0]) {
                        if(b['TrackingSubject'][0]['State'] === 'Order Placed') {
                            firstStep = 'completed';
                        } else {
                            firstStep = 'activated';
                        }
                    } else {
                        firstStep = 'activated';
                    }


                    if(b['TrackingSubject'][1]) {
                        if((b['TrackingSubject'][1]['State'] === 'Order Confirmed') && firstStep === 'completed') {
                            secondStep = 'completed';
                        }
                    } else {
                        if(firstStep === 'completed') {
                            secondStep = 'activated';
                        }
                    }
                    
                    if(b['TrackingSubject'][2]) {
                        if((b['TrackingSubject'][2]['State'] === 'Enroute') && firstStep === 'completed' && secondStep === 'completed') {
                            thirdStep = 'completed';
                        }
                    } else {
                        if(firstStep === 'completed' && secondStep === 'completed') {
                            thirdStep = 'activated';
                        } else {
                            thirdStep = '';
                        }
                    }
                    
                    if(b['TrackingSubject'][3]) {
                        if((b['TrackingSubject'][3]['State'] === 'On the way') && firstStep === 'completed' && secondStep === 'completed' && thirdStep === 'completed') {
                            fourthStep = 'completed';
                        }
                    } else {
                        if(firstStep === 'completed' && secondStep === 'completed' && thirdStep === 'completed') {
                            fourthStep = 'activated';
                        } else {
                            fourthStep = '';
                        }
                    }
                
                    if(b['TrackingSubject'][4]) {
                        if((b['TrackingSubject'][4]['State'] === 'Delivered') && firstStep === 'completed' && secondStep === 'completed' && thirdStep === 'completed' && fourthStep === 'completed') {
                            fifthStep = 'completed';
                        }
                    } else {
                        if(firstStep === 'completed' && secondStep === 'completed' && thirdStep === 'completed' && fourthStep === 'completed') {
                            fifthStep = 'activated';
                        } else {
                            fifthStep = '';
                        }
                    }
        
                    html += '<div class="tracking-stage text-center '+ firstStep + '"><div class="tracking-round"><i class="fas fa-check"></i></div><p class="stage-name">Order Placed</p></div>';
        
                    html += '<div class="tracking-stage text-center '+ secondStep + '"><div class="tracking-round"><i class="fas fa-thumbs-up"></i></div><p class="stage-name">Order Confirmed</p></div>';
        
                    html += '<div class="tracking-stage text-center '+ thirdStep + '"><div class="tracking-round"><i class="fas fa-gift"></i></div><p class="stage-name">Enroute</p></div>';
        
                    html += '<div class="tracking-stage text-center '+ fourthStep + '"><div class="tracking-round"><i class="fas fa-truck"></i></div><p class="stage-name">On the way</p></div>';
        
                    html += '<div class="tracking-stage text-center '+ fifthStep + '"><div class="tracking-round"><i class="fas fa-heart"></i></div><p class="stage-name">Delivered</p></div>';
        
                    html += '<div class="clearfix"></div></div></div></div>';
        
                    html += '<div class="col-12"><div class="mt-4 vertical-tracking">';
        
                    if(b['TrackingSubject'][0]) {
                        let dt1 = new Date(b['TrackingSubject'][0]['TrackingDate']);
                        let ddMonth = dt1.toLocaleString('default', { month: 'short' });
                        let dte = dt1.getDate() +' '+ ddMonth +' '+ dt1.getFullYear();
                        let time = getTimeAMPMFormat(dt1);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time +'</div><div class="v-tracking-round"><i class="fas fa-check"></i></div><div class="v-tracking-subject">Order Placed</div></div></div>';

                    }
        
                    if(b['TrackingSubject'][1]) {
                        let dt2 = new Date(b['TrackingSubject'][1]['TrackingDate']);
                        let ddMonth = dt2.toLocaleString('default', { month: 'short' });
                        let dte = dt2.getDate() +' '+ ddMonth +' '+ dt2.getFullYear();
                        let time2 = getTimeAMPMFormat(dt2);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time2 +'</div><div class="v-tracking-round"><i class="fas fa-thumbs-up"></i></div><div class="v-tracking-subject">Order Confirmed</div></div></div>';

                    }

                    if(b['TrackingSubject'][2]) {
                        let dt3 = new Date(b['TrackingSubject'][2]['TrackingDate']);
                        let ddMonth = dt3.toLocaleString('default', { month: 'short' });
                        let dte = dt3.getDate() +' '+ ddMonth +' '+ dt3.getFullYear();
                        let time3 = getTimeAMPMFormat(dt3);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time3 +'</div><div class="v-tracking-round"><i class="fas fa-gift"></i></div><div class="v-tracking-subject">Enroute</div></div></div>';

                    }

                    if(b['TrackingSubject'][3]) {
                        let dt4 = new Date(b['TrackingSubject'][3]['TrackingDate']);
                        let ddMonth = dt4.toLocaleString('default', { month: 'short' });
                        let dte = dt4.getDate() +' '+ ddMonth +' '+ dt4.getFullYear();
                        let time4 = getTimeAMPMFormat(dt4);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time4 +'</div><div class="v-tracking-round"><i class="fas fa-truck"></i></div><div class="v-tracking-subject">On the way</div></div></div>';

                    }

                    if(b['TrackingSubject'][4]) {
                        let dt5 = new Date(b['TrackingSubject'][4]['TrackingDate']);
                        let ddMonth = dt5.toLocaleString('default', { month: 'short' });
                        let dte = dt5.getDate() +' '+ ddMonth +' '+ dt5.getFullYear();
                        let time5 = getTimeAMPMFormat(dt5);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time5 +'</div><div class="v-tracking-round"><i class="fas fa-heart"></i></div><div class="v-tracking-subject">Delivered</div></div></div>';

                    }
                    html += '</div></div><div class="col-12"><hr /></div></div>';

                $('.overpage-content .container').html(html);
            });
        }
    }).done(function() {
        $(".full-loader-image").hide();
    });
    return false;
}