var lvl1 = ['1V', '1C', '1U', '2V', '2C', '2U', '2D', '2A', '3V', '3C', '3U', '3D', '3A', '4V', '4U', '5V'];
var lvl2 = ['1V', '2V', '2C', '2U','3V', '3C', '3U', '4V'];

$(document).ready(function () {
    if ($('.product-listing-page').length) {
        $('.product-type').each(function () {
            if ($(this).attr('data-name') == getUrlParam('type')) {
                $(this).addClass('active');
                return;
            }
        })
    }

    if ($('.owl-carousel').length) {
        $('.owl-carousel').owlCarousel({
            loop: true,
            margin: 0,
            nav: false,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            autoplay: true,
            autoplayTimeout: 7000,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 1
                },
                1000: {
                    items: 1
                }
            }
        });
    }
    
    $("#fileHero").change(function () {
        readURL(this);
    });

    if ($('.product-detail-page').length) {
        showFilters($('#drpCategory'));
    }

    $('.common-file-uploader').change(function () {
        commonFilePreview(this);
    });

    if ($('.dataTable').length) {
        var dataTable = $('.dataTable').DataTable({
            "order": [[0, "asc"]]
        });  

        //common datatable custom filter for country
        $('.filter-by-country').on('change', function () {
            dataTable.search(this.value).draw();
        });
    }

    $('#fileGallery').on('change', function () {
        imagesPreview(this, 'div.lister');
    });

    applyWatermark($('#drpWatermark').find(':selected').data('img'));

    $('#txtVariantSearch').keyup(function () {
        var text = $(this).val();
        var regex = new RegExp(text, 'ig');
        $('#dropVariants label').each(function () {
            $(this).closest('tr').toggle(regex.test(this.innerText));
        });
    });

    if ($('.order-detail-page').length) {
        $('.webCurrency').text($('.payment-currency').text());

        showShippingTrackingBox();

        $('#drpStatus').on('change', function () {
            showShippingTrackingBox();

            if ($(this).val() === 3) {
                alert('You can not cancel any order');
            }
        });
    }
});

function scrollTop() {
    $('body,html').animate({
        scrollTop: 0
    }, 500);
}

//get url parameters
function getUrlParam(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)')
        .exec(window.location.search);

    return (results !== null) ? results[1] || 0 : false;
}

$(document).on('click', '.order-link', function () {
    var orderNumber = $(this).find('.order-id').text();
    window.location = '/admin/order-details.aspx?orderID=' + orderNumber;
});

//discount redeem type selection
$(document).on('#drpRedeem').on('change', function () {
    toggleRedeemView($(this).find(":selected").val());
});

function toggleRedeemView(redeemtype) {
    $('#pnlPercent').hide();
    $('#pnlAmount').hide();
    if (redeemtype === 'amt') {
        $('#pnlPercent').hide();
        $('#pnlAmount').show();
        $('#txtPercent').val('0');
    } else {
        $('#pnlAmount').hide();
        $('#pnlPercent').show();
        $('#txtAmount').val('0');
    }
}

$(function(){
    if ($('.order-detail-page').length){
        $('.order-items').each(function () {
            // Set the date we're counting down to
            var deliveryDate = $(this).find('.delivery-date').text().trim();
            var deliveryTimeSlot = $(this).find('.delivery-time-slot').text();
            deliveryTimeSlot = deliveryTimeSlot.split('-');
            deliveryTimeSlot = deliveryTimeSlot[0].trim() + ":00";
            var countDownDate = new Date(deliveryDate + " "+ deliveryTimeSlot).getTime();
            var currentOrder = $(this);

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

                if (distance > 0) {
                    currentOrder.find('.countdown-timer').text((days <= 9 ? '0' + days : days) + "d " + (hours <= 9 ? '0' + hours : hours) + "h " + (minutes <= 9 ? '0' + minutes : minutes) + "m " + (seconds <= 9 ? '0' + seconds : seconds) + "s left");
                    //clearInterval(x);
                } else {
                    day = days.toString().replace("-", "");
                    currentOrder.find('.countdown-timer').text("Overdue " + days + " days ago");
                }
            }, 1000);
        });
    }

    if ($('.promo-detail-page').length) {
        toggleRedeemView($('#drpRedeem').val());
    }
});

function showShippingTrackingBox() {
    var drpStatus = $('#drpStatus');
    if (drpStatus.val() === "0") {
        $('#pnlShippingDetails').hide();
        $('#txtTrackingNo').val('');
        $('#drpCarrier').val('');
        $('.checkbox-container').hide();
    } else {
        $('#pnlShippingDetails').show();
        $('.checkbox-container').show();
    }
}

// Multiple images preview in browser
function imagesPreview(input, placeToInsertImagePreview) {
    $('.lister').show();
    if (input.files) {
        var filesAmount = input.files.length;

        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function (event) {
                $($.parseHTML('<img style="height:75px; object-fit:contain; display:inline-block; margin:0 7px" class="img-illusion">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
            },

                reader.readAsDataURL(input.files[i]);
        }
    }
}

$(function () {
    $('#txtCities').on('keyup', function () {
        var value = $(this).val();
        var that = this;
        if ($(this).val().length >= 3) {
            //fetch cities
            var cc = $('#selectedCountry').val();
            if (cc === null || cc === '' || cc === undefined) {
                cc = 'IN';
            }
            $.ajax({
                type: "GET",
                dataType: "text",
                contentType: "application/json; charset=utf-8",
                url: `/ajx.php?case=searchCityBasedOnCountry&CityName=${cc}`,
                //url: `/Content/admin/assets/js/localtest.json`,
                data: {
                    CityName: value, CountryCode: cc
                },
                beforeSend: function () {
                    $(".list-city").html('<div class="text-center">Please wait... Loading cities...</div>');
                },
                success: function (msg) {
                    var rsjson = $.parseJSON(msg);
                    if (value == $(that).val()) {
                        rsjson = $.parseJSON(msg);

                        $(".list-city").html("");

                        var data = "";

                        $.each(rsjson, function (key, value) {
                            if (value.error != 1) {
                                $.each(value, function (k, v) {
                                    data += '<li data-name="' + v.CityName + '" data-id="' + v.CityID + '">';
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
        }
    });

    if ($('.datepicker').length) {
        var currentDate = new Date();
        $(".datepicker").datepicker({
            dateFormat: 'yy-mm-dd',
            startDate: new Date(),
            changeYear: true,
            yearRange: "2020:2050"
        }).attr('readonly', 'readonly');
    }
});

$(document).on('click', '.list-city li', function () {
    $('#txtCities').val('');
    if ($('.product-detail-page').length) {
        addInCityMapping($(this).data(`id`), $('#productID').val(), $(this).text());
    } else if ($('.city-group-page').length) {
        addInCityGroupMapping($(this).data(`id`), $(this).text(), $('#lblGroupID').text());
    }
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        UploadSizeValidation(parseInt($(input).attr('data-size')));
        reader.onload = function (e) {
            $('#imgHero').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function commonFilePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        UploadSizeValidation(parseInt($(input).attr('data-size')));
        reader.onload = function (e) {
            $(input).parent(".uploader-wrapper").siblings('.image-wrapper').find('.file-loader').attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function UploadSizeValidation(sizeValue) {
    var maxFileSize = sizeValue;
    var fileUpload = $('.file-size-validation');

    if (fileUpload.val() === '') {
        return false;
    }
    else {
        var fileExt = fileUpload[0].value.split('.')[1];
        if (fileExt === "jpg" || fileExt === "jpeg" || fileExt === "png" || fileExt === "gif") {
            if (fileUpload[0].files[0].size < maxFileSize) {
                return true;
            } else {
                alert('File is too big. Suggested file size - ' + sizeValue / 1024 + 'KB max for better loadtime');
                return false;
            }
        } else {
            if (fileUpload[0].files[0].size < 20480000) {
                return true;
            } else {
                alert('File is too big. Suggested file size - ' + 20480000 / 1024 + 'KB max for better loadtime');
                return false;
            }
        }
    }
}


$(function () {
    setTimeout(function () {
        $('#pnlSuccess').fadeOut();
    }, 3000);

    if ($('.tiny-editor').length)
        tinymce.init({
            selector: '.tiny-editor',
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            imagetools_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            autosave_ask_before_unload: true,
            autosave_interval: "30s",
            autosave_prefix: "{path}{query}-{id}-",
            autosave_restore_when_empty: false,
            autosave_retention: "2m",
            image_advtab: true,
            content_css: '//www.tiny.cloud/css/codepen.min.css',
            link_list: [
                { title: 'My page 1', value: 'http://www.tinymce.com' },
                { title: 'My page 2', value: 'http://www.moxiecode.com' }
            ],
            image_list: [
                { title: 'My page 1', value: 'http://www.tinymce.com' },
                { title: 'My page 2', value: 'http://www.moxiecode.com' }
            ],
            image_class_list: [
                { title: 'None', value: '' },
                { title: 'Some class', value: 'class-name' }
            ],
            importcss_append: true,
            file_picker_callback: function (callback, value, meta) {
                /* Provide file and text for the link dialog */
                if (meta.filetype === 'file') {
                    callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
                }

                /* Provide image and alt text for the image dialog */
                if (meta.filetype === 'image') {
                    callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
                }

                /* Provide alternative source and posted for the media dialog */
                if (meta.filetype === 'media') {
                    callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
                }
            },
            templates: [
                { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
                { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
                { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 600,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: "mceNonEditable",
            toolbar_mode: 'sliding',
            contextmenu: "link image imagetools table",
        });
});

$(function () {
    $("#drpCategory").on('change', function () {
        showFilters(this);
    });


    $('#drpRole').on('change', function () {
        $('.chk-permission').find('input[type="checkbox"]').prop('checked', false);
        if ($(this).val() === 'lvl0') {
            $('.chk-permission').find('input[type="checkbox"]').prop('checked', true);
        } else if ($(this).val() === 'lvl1') {
            $(lvl1).each(function (index, value) {
                let currentLevel = value;
                $('.chk-permission').find('input[type="checkbox"]').each(function () {
                    if ($(this).val() === currentLevel)
                        $(this).prop('checked', true);
                });
            });
        } else if ($(this).val() === 'lvl2') {
            $(lvl2).each(function (index, value) {
                let currentLevel = value;
                $('.chk-permission').find('input[type="checkbox"]').each(function () {
                    if ($(this).val() === currentLevel)
                        $(this).prop('checked', true);
                });
            });
        }
    });
});

$(document).on('keyup', '#txtCities', function () {
    if ($(this).val().length > 3) {
        filterCitySearch(this);
    } else {
        $("#chkCities > li").removeAttr('style');
    }
});

function filterCitySearch(element) {
    var value = $(element).val();

    $("#chkCities > li").each(function () {
        if ($(this).text().search(value) > -1) {
            $(this).show();
        }
        else {
            $(this).hide();
        }
    });
}

$(function () {
    //search and set city
    $('#txtCities').on('keyup', function () {
        let cityVal = $(this).val();
        if (cityVal.length >= 3) {
            let value = $(this).val();

            if (value.length >= 2) {
                var text = $(this).val();
                var regex = new RegExp(text, 'ig');
                $('.list-city li').each(function () {
                    $(this).toggle(regex.test($(this).data('name')));
                });
            } else {
                $(".list-city li").show();
            }
        }
    });
});



function addInCityMapping(selectedCityID, selectedProductID, selectedCityName) {
    var isCityAdd = true;
    $('.selectedCity').find('li').each(function () {
        if ($(this).find('span').attr('title') == selectedCityID) {
            $('.city-alert-danger').fadeIn();
            setTimeout(function () {
                $('.city-alert-danger').fadeOut();
            }, 2000);
            isCityAdd = false;
            return false;
        }
    });

    //checks if already added
    if (isCityAdd) {
        var cityData = {};
        cityData.productID = selectedProductID;
        cityData.cityID = selectedCityID;
        $.ajax({
            type: "POST",
            url: "/admin/service.aspx/addInCityMapping",
            data: '{cityData: ' + JSON.stringify(cityData) + '}',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (response) {
                $('.selectedCity').append(`
                    <li>
                        <span title="${selectedCityID}">${selectedCityName}</span>
                        <button type="button" class="btn-remove city-remove">&#10005;</button>
                    </li>
                `);

                $('.city-alert').fadeIn();

                setTimeout(function () {
                    $('.city-alert').fadeOut();
                }, 2000);
            }
        });
    }
}

function addInCityGroupMapping(selectedCityID, selectedCityName, selectedGroupID) {
    var isCityAdd = true;
    $('.selectedCity').find('li:visible').each(function () {
        if ($(this).find('span').attr('title') == selectedCityID) {
            $('.city-alert-danger').fadeIn();
            setTimeout(function () {
                $('.city-alert-danger').fadeOut();
            }, 2000);
            isCityAdd = false;
            return false;
        }
    });

    if (isCityAdd) {
        var cityGroupData = {};
        cityGroupData.CityGroupID = selectedGroupID;
        cityGroupData.cityID = selectedCityID;
        $.ajax({
            type: "POST",
            url: "/admin/service.aspx/addInCityGroupMapping",
            data: '{cityGroupData: ' + JSON.stringify(cityGroupData) + '}',
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (response) {
                $('.selectedCity').append(`
                    <li>
                        <span title="${selectedCityID}">${selectedCityName}</span>
                    </li>
                `);

                //generateAndPushProductsOfCityGroup(selectedCityID);
                insertCitiesForNewCityAddedInGroup(selectedCityID);

                $('.city-alert').fadeIn();

                setTimeout(function () {
                    $('.city-alert').fadeOut();
                }, 2000);
            }
        });
    }
}

$(document).on('click', '.city-remove', function () {
    var cityData = {};
    cityData.productID = $('#productID').val();
    cityData.cityID = $(this).siblings('span').attr('title');
    $(this).parents('li').remove();
    $.ajax({
        type: "POST",
        url: "/admin/service.aspx/deleteCityProductMapping",
        data: '{cityData: ' + JSON.stringify(cityData) + '}',
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
            
        }
    });
});

function showFilters(category) {
    var selectedCategory = $('option:selected', category).val();
    $('#chkFilters').find('td').hide();
    $('#chkFilters td').each(function () {
        if ($(this).find('span').data('categoryid') == selectedCategory) {
            $(this).show();
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

$(document).on('click', '.chkAll', function () { 
    if ($(this).is(':checked')) {
        $(this).parents('table').siblings('table').find('input[type="checkbox"]').prop('checked', true);
    } else {
        $(this).parents('table').siblings('table').find('input[type="checkbox"]').prop('checked', false);
    }
});

$(document).on('click', '.chkChild', function () {
    if (!$(this).is(':checked')) {
        $(this).parents('table').siblings('table').find('input[type="checkbox"]').prop('checked', false);
    }
});


var cityGroupProduct = [];
function generateAndPushProductsOfCityGroup(selectedCityID) {
    $('.linked-products').each(function () {
        var products = {};
        products.ProductID = $(this).text();
        products.CityID = selectedCityID;   
        products.CityGroupID = $('#lblGroupID').text();
        cityGroupProduct.push(products);
    });
}

$('.linked-products').each(function () {
    var a = {};
    a.productID = $(this).text();
    arr.push(a);
});

function insertCitiesForNewCityAddedInGroup(selectedCityID) {
    generateAndPushProductsOfCityGroup(selectedCityID);
    $.ajax({
        type: "POST",
        url: "/admin/service.aspx/insertCitiesForNewCityAddedInGroup",
        data: JSON.stringify({ cityGroupProduct: cityGroupProduct }),//'{}',
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) { alert(response.d); },
        failure: function (response) { alert(response.d); },
        error: function (response) { alert(response.d); }
    });
}

function OpenGenerictModal(ModalID) {
    $('#' + ModalID).modal('show');
}