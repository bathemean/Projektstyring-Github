$(document).ready(function() { 
	var selectedEmployee = $('input[name=employeeid]').val();
	// when a new employee is selected
	$('input[name="employeeid"]').change(function() {
 		selectedEmployee = $(this).val();
 		updateCalendar();
 	});

	// updates the calendar to reflect the selected employees work horus
	var updateCalendar = function() {
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
			return $(this).attr('time') >= schedule[n].start && $(this).attr('time') <= schedule[n].end;
				}).removeClass('unavailable');

		}
	}

	// update calendar when page loads
	updateCalendar();


});