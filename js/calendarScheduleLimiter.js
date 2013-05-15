$(document).ready(function() { 
	var selectedEmployee = $('input[name=employeeid]').val();
	// when a new employee is selected
	$('input[name="employeeid"]').change(function() {
 		selectedEmployee = $(this).val();
 		updateCalendar();
 	});

 	

 	var markExceptions = function() {
 		
 		var exceptions = new Array();
	 	$('input[name="exception"][id="'+ selectedEmployee +'"]').each(function() {
	 		var arr = new Array();

	 		arr['employee'] = $(this).attr('id');
	 		arr['date'] = $(this).attr('date');
	 		arr['start'] = $(this).attr('start');
	 		arr['end'] = $(this).attr('end');

	 		exceptions.push(arr);
	 	});

 		for(e in exceptions) {
 			$('td').filter(function() { 
				
				return $(this).attr('date') == exceptions[e].date &&
					   $(this).attr('time') >= exceptions[e].start && 
					   $(this).attr('time') <= exceptions[e].end;

			}).addClass('unavailable');
 		}

 	}

	// updates the calendar to reflect the selected employees work horus
	var updateCalendar = function() {
		
		// remove all .selected and .focus classes from table cells
		$('#calendar td.selected').removeClass('selected');
 		$('#calendar td.focus').removeClass('focus');
		
		var schedule = new Array();

		$('td').addClass('unavailable');

		$('input[name="scheduleStart"][id="' + selectedEmployee + '"]').each(function() {

			var start = $(this).attr('start');
			var end = $(this).attr('end');
			var day = $(this).attr('day');
			//var end = $('input[name="scheduleEnd"][day="'+ $(this).attr('day') +'"]').val();

			var arr = new Array();
			arr['day'] = day;
			arr['start'] = start;
			arr['end'] = end;

			schedule.push(arr);

		});

		for(n in schedule) {

			$('td[id^="'+ schedule[n].day +',"]').filter(function() { 
				
				return $(this).attr('time') >= schedule[n].start && 
					   $(this).attr('time') <= schedule[n].end;

			}).removeClass('unavailable');

		}

		markExceptions();
	}

	// update calendar when page loads
	updateCalendar();


});