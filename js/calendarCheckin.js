$(document).ready(function() {
	
	$(document).on('click', 'input[id="checkin"]', function() {
        if( $(this).is(':checked') )
			state = 1;
		else
			state = 0;
		
		checkIn( $(this).attr('name'), $(this).attr('employeeid'), state );

    });

    var checkIn = function(date, employeeid, state) {
    	$.post('checkinHandler.php?date='+date+'&employeeid='+employeeid+'&state='+state)
    }

})