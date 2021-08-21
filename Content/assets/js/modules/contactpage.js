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