$(document).on('click', '.trackorder-page .btn-submit', function(){
    getProductTrackingStatus();
});

const getTimeAMPMFormat = (date) => {
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let seconds = date.getSeconds();
    const ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    hours = hours < 10 ? '0' + hours : hours;
    // appending zero in the start if hours less than 10
    minutes = minutes < 10 ? '0' + minutes : minutes;
    return hours + ':' + minutes + ':' + seconds + '' + ampm;
};

//fetch tracking status of products
function getProductTrackingStatus() {
    var order_ID = $('.trackorder-page .txt-search').val();

    $.ajax({
        url: FLORAL_AJAX + "ajx.php?case=fetchOrdersWithTracking&OrderID="+ order_ID,
        method:"POST",
        cache: false,
        contentType: false,
        beforeSend: function() {
            $(".full-loader-image").show();
        },
        success: function(data) {
            var rsjson = $.parseJSON(data);
            $('.overpage-content .container').html('');
            if(rsjson.results.error === 1) {
                $('.overpage-content .container').html('<div class="col-12"><h3>No product found...!!</h3></div>');
                return false;
            }
 
            var html = "";

            $.each(rsjson.results, function(k, b) {
                let dd = new Date(b['DeliveryDate']);
                let ddM = dd.toLocaleString('default', { month: 'short' });
                let dDate = dd.getDate() +' '+ ddM +' '+ dd.getFullYear();
                html += '<div class="row order-item"><div class="col-12"><div class="tracking-header">'+ b['ProductName'] +' <span class="track-delivery-time">(Delivery on '+ dDate +' - '+ b['DeliveryTimeSlot'] +')</span></div></div>';
                html += '<div class="col-3 col-lg-2"><div class="order-item-image"><img src="'+ DOMAIN + b['ProductImage'] +'" class="img-fluid"><span class="item-qty">'+ b['ProductQty'] +'</span></div></div>';

                html += '<div class="col-9 col-lg-10"><div class="tracking-info"><div class="tracking-number">Order ID - '+ b['OrderID'] +'</div>';
        
                html += '<div class="horizontal-tracking">';

                    var firstStep, secondStep, thirdStep, fourthStep, fifthStep;
                
                    if(b['TrackingSubject'][0]) {
                        if(b['TrackingSubject'][0]['State'] === 'Order Placed') {
                            firstStep = 'completed';
                        } else {
                            firstStep = 'activated';
                        }
                    } else {
                        firstStep = 'activated';
                    }


                    if(b['TrackingSubject'][1]) {
                        if((b['TrackingSubject'][1]['State'] === 'Order Confirmed') && firstStep === 'completed') {
                            secondStep = 'completed';
                        }
                    } else {
                        if(firstStep === 'completed') {
                            secondStep = 'activated';
                        }
                    }
                    
                    if(b['TrackingSubject'][2]) {
                        if((b['TrackingSubject'][2]['State'] === 'Enroute') && firstStep === 'completed' && secondStep === 'completed') {
                            thirdStep = 'completed';
                        }
                    } else {
                        if(firstStep === 'completed' && secondStep === 'completed') {
                            thirdStep = 'activated';
                        } else {
                            thirdStep = '';
                        }
                    }
                    
                    if(b['TrackingSubject'][3]) {
                        if((b['TrackingSubject'][3]['State'] === 'On the way') && firstStep === 'completed' && secondStep === 'completed' && thirdStep === 'completed') {
                            fourthStep = 'completed';
                        }
                    } else {
                        if(firstStep === 'completed' && secondStep === 'completed' && thirdStep === 'completed') {
                            fourthStep = 'activated';
                        } else {
                            fourthStep = '';
                        }
                    }
                
                    if(b['TrackingSubject'][4]) {
                        if((b['TrackingSubject'][4]['State'] === 'Delivered') && firstStep === 'completed' && secondStep === 'completed' && thirdStep === 'completed' && fourthStep === 'completed') {
                            fifthStep = 'completed';
                        }
                    } else {
                        if(firstStep === 'completed' && secondStep === 'completed' && thirdStep === 'completed' && fourthStep === 'completed') {
                            fifthStep = 'activated';
                        } else {
                            fifthStep = '';
                        }
                    }
        
                    html += '<div class="tracking-stage text-center '+ firstStep + '"><div class="tracking-round"><i class="fas fa-check"></i></div><p class="stage-name">Order Placed</p></div>';
        
                    html += '<div class="tracking-stage text-center '+ secondStep + '"><div class="tracking-round"><i class="fas fa-thumbs-up"></i></div><p class="stage-name">Order Confirmed</p></div>';
        
                    html += '<div class="tracking-stage text-center '+ thirdStep + '"><div class="tracking-round"><i class="fas fa-gift"></i></div><p class="stage-name">Enroute</p></div>';
        
                    html += '<div class="tracking-stage text-center '+ fourthStep + '"><div class="tracking-round"><i class="fas fa-truck"></i></div><p class="stage-name">On the way</p></div>';
        
                    html += '<div class="tracking-stage text-center '+ fifthStep + '"><div class="tracking-round"><i class="fas fa-heart"></i></div><p class="stage-name">Delivered</p></div>';
        
                    html += '<div class="clearfix"></div></div></div></div>';
        
                    html += '<div class="col-12"><div class="mt-4 vertical-tracking">';
        
                    if(b['TrackingSubject'][0]) {
                        let dt1 = new Date(b['TrackingSubject'][0]['TrackingDate']);
                        let ddMonth = dt1.toLocaleString('default', { month: 'short' });
                        let dte = dt1.getDate() +' '+ ddMonth +' '+ dt1.getFullYear();
                        let time = getTimeAMPMFormat(dt1);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time +'</div><div class="v-tracking-round"><i class="fas fa-check"></i></div><div class="v-tracking-subject">Order Placed</div></div></div>';

                    }
        
                    if(b['TrackingSubject'][1]) {
                        let dt2 = new Date(b['TrackingSubject'][1]['TrackingDate']);
                        let ddMonth = dt2.toLocaleString('default', { month: 'short' });
                        let dte = dt2.getDate() +' '+ ddMonth +' '+ dt2.getFullYear();
                        let time2 = getTimeAMPMFormat(dt2);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time2 +'</div><div class="v-tracking-round"><i class="fas fa-thumbs-up"></i></div><div class="v-tracking-subject">Order Confirmed</div></div></div>';

                    }

                    if(b['TrackingSubject'][2]) {
                        let dt3 = new Date(b['TrackingSubject'][2]['TrackingDate']);
                        let ddMonth = dt3.toLocaleString('default', { month: 'short' });
                        let dte = dt3.getDate() +' '+ ddMonth +' '+ dt3.getFullYear();
                        let time3 = getTimeAMPMFormat(dt3);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time3 +'</div><div class="v-tracking-round"><i class="fas fa-gift"></i></div><div class="v-tracking-subject">Enroute</div></div></div>';

                    }

                    if(b['TrackingSubject'][3]) {
                        let dt4 = new Date(b['TrackingSubject'][3]['TrackingDate']);
                        let ddMonth = dt4.toLocaleString('default', { month: 'short' });
                        let dte = dt4.getDate() +' '+ ddMonth +' '+ dt4.getFullYear();
                        let time4 = getTimeAMPMFormat(dt4);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time4 +'</div><div class="v-tracking-round"><i class="fas fa-truck"></i></div><div class="v-tracking-subject">On the way</div></div></div>';

                    }

                    if(b['TrackingSubject'][4]) {
                        let dt5 = new Date(b['TrackingSubject'][4]['TrackingDate']);
                        let ddMonth = dt5.toLocaleString('default', { month: 'short' });
                        let dte = dt5.getDate() +' '+ ddMonth +' '+ dt5.getFullYear();
                        let time5 = getTimeAMPMFormat(dt5);

                        html += '<div class="v-tracking-stage completed"><div class="step-wrapper"><div class="v-tracking-date-time">'+ dte +'<br/>'+ time5 +'</div><div class="v-tracking-round"><i class="fas fa-heart"></i></div><div class="v-tracking-subject">Delivered</div></div></div>';

                    }
                    html += '</div></div><div class="col-12"><hr /></div></div>';

                $('.overpage-content .container').html(html);
            });
        }
    }).done(function() {
        $(".full-loader-image").hide();
    });
    return false;
}