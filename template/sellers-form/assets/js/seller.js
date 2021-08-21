var isFormValid = true;
$(document).on('click', '.section-toggle-chevron', function () {
    $(this).find('.c-chevron').toggleClass('bottom top');
    $(this).parents('.seller-form').find('.collapsable').toggleClass('d-none');
});

$(function () {
    $('.custom-form-control').on('input', function (e) {
        txtField = e.currentTarget.id;
        checkTextboxValue(txtField);
    });
});

$(document).on('click', '.btn-next', function(e){
    e.preventDefault();
    if (customValidation($(this).parents('.form-block'))){
        if($('.form2').hasClass('active')){
            var $form = $("#basicDetailForm");
            var formData = getFormData($form);
            console.log(formData);
            addSellersData(formData);
        }
    
        let form = $(this).parents('.form-block');
        $(form).removeClass('active');
        $(form).addClass('completed');
        $(form).next('.form-block').addClass('active');
        getFormStatus();
    }
});

function getFormStatus(){
    let formBlocks = $('.form-block'),
    formSectionBlocks = $('.seller-form-section-blocks'),
    formID,
    completedFormID;
    formBlocks.each(function(){
        if($(this).hasClass('active')){
            formID = $(this).data('form');
        }

        if($(this).hasClass('completed')){
            completedFormID = $(this).data('form');
        }
    });

    formSectionBlocks.each(function(){
        let sectionID = $(this).data('form');
        if(formID === sectionID){
            $(this).addClass('active');
        }

        if(sectionID === completedFormID){
            $(this).toggleClass('completed active');
        }
    });
}

$(document).on('input blur', '.custom-form-control', function(e){
    validateField(this, e, $(this).parents('.form-block'));
});

$(document).on('click', '.btn-back', function(){
    let form = $(this).parents('.form-block');
    $(form).removeClass('active');
    $(form).prev('.form-block').addClass('active');
});

function checkTextboxValue(txtField) {
    var txtID = '#' + txtField;
    if ($(txtID).val() !== '') {
        $(txtID).next().addClass('focused');
    } else {
        $(txtID).next().removeClass('focused');
    }
}

function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

function customValidation(form){
    let thisField = $(form).find('.input-field');
    $(thisField).each(function(){
        validateField(this, '', form);
    });

    return isFormValid;
}

$(document).on('click', 'input[name="sellerType"]', function(){
    if($(this).is(':checked')){
        $('.err-type').text('');
        $('#businessType').val($(this).val())
    }
})

function validateField(thisField, event, form){
    let pattern = $(thisField).data('val-validate-regex'),
    errorMessage;
    if($(thisField).hasClass('required')  || $(thisField).hasClass('pattern')){
        isFormValidate = false;
        if($(thisField).val() === ''){
            errorMessage = $(thisField).data('error-required-msg');
            handleFeildHighlight(thisField, errorMessage, 'Failed');
        }else if($(thisField).hasClass('pattern')){
            errorMessage = $(thisField).data('error-invalid-msg');
            if($(thisField).hasClass('no-input-validate') && event.type === 'input'){
                if($(thisField).val().trim().match(pattern)){
                    handleFeildHighlight(thisField, errorMessage, 'Success');
                }
            }else if(event.type !== 'input' && $(thisField).val().trim().match(pattern)){
                handleFeildHighlight(thisField, errorMessage, 'Success');
            }else if($(thisField).val().trim().match(pattern)){
                handleFeildHighlight(thisField, errorMessage, 'Success');
            }else{
                handleFeildHighlight(thisField, errorMessage, 'Failed');
            }
        }else{
            handleFeildHighlight(thisField, errorMessage, 'Success');
        }
    }else{
        handleFeildHighlight(thisField, errorMessage, 'Success');
    }

    inavlidCount = $(form).find('.validation-failed').length;
    if(inavlidCount > 0){
        isFormValid = false;
    }else{
        isFormValid = true;
    }

    return isFormValid;
}

function handleFeildHighlight(element, errorMsg, status){
    const alertMsg = (status === 'Failed' ? 'validation-failed' : '');
    $(element).removeClass('validation-failed');
    $(element).addClass(alertMsg);
    if(status === 'Failed'){
        $(element).siblings('.error-message').text(errorMsg);
    }else{
        $(element).siblings('.error-message').text('');
    }
}

function validationRules(element, event) {
    let pattern = $(element).data('val-validate-regex'), 
    requiredMessage = $(element).data('val-required'),
    patternMessage = $(element).data('val-validate');
 
    //validate only if required or pattern class there on element
    if($(element).hasClass('required') || $(element).hasClass('pattern')){
        if($(element).val() === ''){
            handleFeildHighlight(element, requiredMessage, 'Failed');
        }else{
            //if user typing hide validation errors if patten matches. for better user experience
            if (event.type === 'input' || event.type === 'keyup') {
                if ($(element).val().match(pattern)) {
                    handleFeildHighlight(element, '', 'Success');
                }
            }
            else{
                if($(element).hasClass('pattern') && !$(element).val().trim().match(pattern)){
                    handleFeildHighlight(element, patternMessage, 'Failed');
                }else{
                    handleFeildHighlight(element, '', 'Success');
                }
            }
        }
    }
}

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
      center: { lat: 40.749933, lng: -73.98633 },
      zoom: 13,
    });
    const card = document.getElementById("pac-card");
    const input = document.getElementById("txtLocation");
    const biasInputElement = document.getElementById("use-location-bias");
    const strictBoundsInputElement = document.getElementById("use-strict-bounds");
    const options = {
      componentRestrictions: { country: "in" },
      fields: ["formatted_address", "geometry", "name"],
      origin: map.getCenter(),
      strictBounds: false,
      types: ["establishment"],
    };
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
    const autocomplete = new google.maps.places.Autocomplete(input, options);
    // Bind the map's bounds (viewport) property to the autocomplete object,
    // so that the autocomplete requests use the current map bounds for the
    // bounds option in the request.
    autocomplete.bindTo("bounds", map);
    const infowindow = new google.maps.InfoWindow();
    const infowindowContent = document.getElementById("infowindow-content");
    infowindow.setContent(infowindowContent);
    const marker = new google.maps.Marker({
      map,
      anchorPoint: new google.maps.Point(0, -29),
    });
    autocomplete.addListener("place_changed", () => {
      infowindow.close();
      marker.setVisible(false);
      const place = autocomplete.getPlace();
  
      if (!place.geometry || !place.geometry.location) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        window.alert("No details available for input: '" + place.name + "'");
        return;
      }
  
      // If the place has a geometry, then present it on a map.
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);
      }
      marker.setPosition(place.geometry.location);
      marker.setVisible(true);
      infowindowContent.children["place-name"].textContent = place.name;
      infowindowContent.children["place-address"].textContent =
        place.formatted_address;
      infowindow.open(map, marker);
    });
  
    // Sets a listener on a radio button to change the filter type on Places
    // Autocomplete.
    function setupClickListener(id, types) {
      const radioButton = document.getElementById(id);
      radioButton.addEventListener("click", () => {
        autocomplete.setTypes(types);
        input.value = "";
      });
    }
    setupClickListener("changetype-all", []);
    setupClickListener("changetype-address", ["address"]);
    setupClickListener("changetype-establishment", ["establishment"]);
    setupClickListener("changetype-geocode", ["geocode"]);
    biasInputElement.addEventListener("change", () => {
      if (biasInputElement.checked) {
        autocomplete.bindTo("bounds", map);
      } else {
        // User wants to turn off location bias, so three things need to happen:
        // 1. Unbind from map
        // 2. Reset the bounds to whole world
        // 3. Uncheck the strict bounds checkbox UI (which also disables strict bounds)
        autocomplete.unbind("bounds");
        autocomplete.setBounds({ east: 180, west: -180, north: 90, south: -90 });
        strictBoundsInputElement.checked = biasInputElement.checked;
      }
      input.value = "";
    });
    strictBoundsInputElement.addEventListener("change", () => {
      autocomplete.setOptions({
        strictBounds: strictBoundsInputElement.checked,
      });
  
      if (strictBoundsInputElement.checked) {
        biasInputElement.checked = strictBoundsInputElement.checked;
        autocomplete.bindTo("bounds", map);
      }
      input.value = "";
    });
}

function addSellersData(formData){
    $.ajax({
        type: "POST",
        url: FLORAL_AJAX + "ajx.php?case=addSellersData",
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
}