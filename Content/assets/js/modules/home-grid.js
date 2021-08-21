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