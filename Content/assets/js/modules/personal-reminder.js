const reminderForm = $('#reminderForm');

$(document).on('click', '.get-reminder-form', function(e){
    e.preventDefault();
    if ($(this).data('action') === 'edit'){
        getEditForm(this);
    }
    $('.reminder-form, .list-container').toggleClass('d-none').promise().done(function(){
        if($('.reminder-form').hasClass('d-none')){
            $('.add-reminder').html('<i class="fas fa-plus"></i> Add Reminder');
        }else{
            $('.add-reminder').html('<i class="fas fa-angle-left"></i> Back to Reminders');
            $(reminderForm).trigger('reset').attr({'data-action':'new', 'data-id':'none'});
        }
    });
}).on('click', '.save-reminder', function(e){
    e.preventDefault();
    if($(reminderForm).valid()){
        var actionType = $('#reminderForm').data('action');
        saveReminderForm(actionType);
    }
}).on('click', '.delete-reminder', function(){
    if(confirm("Are you sure you want to delete this?")){
        deleteReminder();
    }
    else{
        return false;
    }
});

function getEditForm(reminder){
    let reminderID = $(reminder).data('id');
    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=editReminder&ID=" + reminderID,
        type: "POST",
        beforeSend: function() {
            $(".full-loader-image").show();
        },
        success: function(data) {
            let rsjson = $.parseJSON(data);
            $(reminderForm).attr({'data-action':'update', 'data-id' : reminderID});
            $('#remindeName').val(rsjson.results.ReminderName);
            $('#LocationName').val(rsjson.results.LocationName);
            $('#reminderCountryCode').val(rsjson.results.STD);
            $('#reminderContact').val(rsjson.results.ContactNumber);
            $('.ui-selectmenu-text').text(rsjson.results.STD);
            $('#EventCode').val(rsjson.results.EventCode);
            rsjson.results.EventCode === '0' ? $('#reminderEvent').val('0') : $('#reminderEvent').val(rsjson.results.Event);
            $('#txtReminderEvent').val(rsjson.results.Event);
            $('#reminderDate').val(rsjson.results.ReminderDate);
            $('#Notes').val(rsjson.results.Notes);
        }
    }).done(function() {
        $(".full-loader-image").hide();
        $(".has-combobox").iconselectmenu();
    });
}

function saveReminderForm(actionType){
    let formData = new FormData($(reminderForm)[0]);
   JSON.stringify(formData);
    if(actionType === 'update'){
        let reminderID = $(reminderForm).data('id');
        $.ajax({
            type: "POST",
            url: FLORAL_AJAX + "ajx.php?case=updateReminder&ID="+ reminderID,
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".full-loader-image").show();
            },
            success: function (data) {
                let rsjson = $.parseJSON(data);
                if(rsjson.results.error === 0) {
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    location.reload();
                }

                if(rsjson.results.error === 1) {
                    console.log(rsjson.results.msg);
                }
            }
        }).done(function() {
            $(".full-loader-image").hide();
        });
    }else{
        $.ajax({
            type: "POST",
            url: FLORAL_AJAX + "ajx.php?case=addReminder",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(".full-loader-image").show();
            },
            success: function (data) {
                var rsjson = $.parseJSON(data);

                if(rsjson.results.error === 0) {
                    $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                    location.reload();
                }

                if(rsjson.results.error === 1) {
                    console.log(rsjson.results.msg);
                }
            }
        }).done(function() {
            $(".full-loader-image").hide();
        });
    }
}

function deleteReminder(){
    let reminderID = $(reminderForm).data('id'),
    formData = new FormData($(reminderForm)[0]);
    $.ajax({
        type: "POST",
        url: FLORAL_AJAX + "ajx.php?case=deleteReminder&ID="+ reminderID,
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function() {
            $(".full-loader-image").show();
        },
        success: function (data) {
            let rsjson = $.parseJSON(data);
            if(rsjson.results.error === 0) {
                $.cookie('toastMsg', rsjson.results.msg, { path: '/' });
                location.reload();
            }

            if(rsjson.results.error === 1) {
                console.log(rsjson.results.msg);
            }
        }
    }).done(function() {
        $(".full-loader-image").hide();
    });
}

$(document).on('change', '#reminderEvent', function(){
    $('#EventCode').val($(this).find(":selected").data('code'));
    setEventText($(this).find(":selected").text());
});

function setEventText(selectedEvent){
    if(selectedEvent === '0' || selectedEvent === 'Others'){ // 0 = Others
        $('#txtReminderEvent').parent('.form-group').removeClass('d-none');
        $('#txtReminderEvent').val('');
    }else{
        $('#txtReminderEvent').parent('.form-group').addClass('d-none');
        $('#txtReminderEvent').val(selectedEvent);
    }
}