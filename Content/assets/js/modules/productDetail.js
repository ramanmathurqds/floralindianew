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