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