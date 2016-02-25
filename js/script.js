$(document).ready(function(){
    
		  
	$('#twatcont').on('keyup change', function(){
		var chLeft = (150- jQuery('textarea#twatcont').val().length);
		$( '#counter' ).text(chLeft);
		checkTwat();
	});
    
    
    function checkTwat(){
        if($('#twatcont').val() !== '')
	    {
		    $('#twat-btn').removeAttr('disabled');
	    }
	    else
	    {
	    	$('#twat-btn').attr('disabled', 'disabled');
	    }
    }
    
    function fixTweets(){
    	var wOW = $(window).outerWidth();

    	if(wOW < 438){
    		$('.ua-wrapper').removeClass('col-xs-2').addClass('col-xs-4');
    	}

    	if(wOW >= 438 && wOW < 768){
    		$('.ua-wrapper').removeClass('col-xs-4').addClass('col-xs-3');
    	}

    	if(wOW < 1400){
    		$('.ua-wrapper').removeClass('col-lg-1').addClass('col-lg-2');
    	}

    	if(wOW >=1700){
    		$('.ua-wrapper').removeClass('col-lg-2').addClass('col-lg-1');
    	}

    }

    $(window).on('load resize', function(){

    	fixTweets();

       if($(this).innerWidth() >= 970){
           $('.panel-tweet').css({height: $('.panel-user').height()});  
       }

    });


});
		