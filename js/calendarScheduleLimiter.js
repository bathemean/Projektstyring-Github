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

			var day = new Array();

			var start = $(this).val();
			var end = $('input[name="scheduleEnd"][day="'+ $(this).attr('day') +'"]').val();
			day['start'] = start;
			day['end'] = end;

			schedule[$(this).attr('day')] = day;

		});


		for(n in schedule) {

			$('td[id^="'+ n +',"]').filter(function() {
			return $(this).attr('time') >= schedule[n].start && $(this).attr('time') <= schedule[n].end;
				}).removeClass('unavailable');

		}
	}

	// update calendar when page loads
	updateCalendar();


});