$(document).ready(function() {



    date = $('input[id="1"][name="unixdate"]').val();
    // add values to the navigation links, allows for +- 1 week
    $('a#calNavRight').attr('href', parseInt(date)+604800);
    $('a#calNavLeft').attr('href', parseInt(date)-604800);


    $('a#calNavRight, a#calNavLeft').click(function() {
        advance( $(this).attr('href') );
        return false;
    });


    var advance = function(start) {

        $('div#calendarContainer').load('pages/calendar.php?start=' + start);

    }

});