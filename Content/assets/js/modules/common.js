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