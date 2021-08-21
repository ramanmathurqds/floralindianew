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