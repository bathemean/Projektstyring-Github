$(document).ready(function() {

	var selectedEmployee = $('input[name=employeeid]').val();
	// when a new employee is selected
	$('input[name="employeeid"]').change(function() {
 		selectedEmployee = $(this).val();
 		updateCalendar();
 	});

	var updateCalendar = function() {
		clearBooked();
		$("td[emp_"+ selectedEmployee +"]").addClass('booked');
	}

	var clearBooked = function() {
		$('td').removeClass('booked');
	}

	updateCalendar();

});