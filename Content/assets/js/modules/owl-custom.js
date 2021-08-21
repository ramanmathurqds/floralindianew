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