$(document).ready(function() {
	
	$(document).on('click', 'input[id="checkin"]', function() {
        if( $(this).is(':checked') )
			state = 1;
		else
			state = 0;
	
		checkIn( $(this).attr('name'), state );
console.log( $(this).attr('name'));	
    });

    var checkIn = function(id, state) {
    	$.post('checkinHandler.php?id='+id+'&state='+state) 
    	
    }

})