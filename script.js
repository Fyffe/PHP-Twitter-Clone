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
    
});
