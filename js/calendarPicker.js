$(document).ready(function() {

	// stores the duration of the treatments in number of quarter hours
	var treatmentDuration = new Array();
		treatmentDuration[1] = 60/15;
		treatmentDuration[2] = 30/15;
		treatmentDuration[3] = 30/15;
		treatmentDuration[4] = 60/15;

	// stores the selected treatment, and updates it whenever it changes
	var selectedTreatment = $('input[name="treatment"]').val();
 	$('input[name="treatment"]').change(function() {
 		selectedTreatment = $(this).val();
 	});

 	 	// returns an array of the cells to recolor, based on the cell the mouse
 	// is currently focussing, and the duration of the treatment (# of quarters)
 	var affectedCells = function(cell) {
 		
 		// computes the location of the delimiter ,
 		delimitPos = cell.attr('id').indexOf(',');

 		// splits the cellID into x and y coordinates
 		selectedCellX = cell.attr('id').substr(0, delimitPos);
		selectedCellY = cell.attr('id').substr(delimitPos+1);

 		var cells = new Array();

 		for(var i=0; i<treatmentDuration[selectedTreatment]; i++) {
 			newY = parseInt(selectedCellY) + i;
 			cells.push( selectedCellX + ',' + newY);
 		}
 		return cells;

 	}


 	// changes the background of a cell
 	var focusBackground = function(cell, state) {
 		if(state == 'over')
 			$('td[id="' + cell + '"]').addClass('focus');
 		else
 			$('td[id="' + cell + '"]').removeClass('focus');
 	}

 	$('#calendar td').mouseover(function() {	
 		cells = affectedCells( $(this) );
 		for(c in cells) {
 			focusBackground( cells[c], 'over' );
 		}
 	});

 	$('#calendar td').mouseout(function() {
		cells = affectedCells( $(this) );
 		for(c in cells) {
 			focusBackground( cells[c] );
 		} 	
 	});
 	

 	// changles the background of a cell
 	var selectBackground = function(cell) {
		$('td[id="' + cell + '"]').addClass('selected');
 	}

	$('#calendar td').click(function() {
		
		// remove all .selected and .focus classes from table cells
		$('#calendar td.selected').removeClass('selected');
 		$('#calendar td.focus').removeClass('focus');

		cells = affectedCells( $(this) );
 		for(c in cells) {
 			selectBackground( cells[c] );
 		}
	});

});