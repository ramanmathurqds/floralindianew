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


