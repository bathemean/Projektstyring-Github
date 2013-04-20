$(document).ready(function() {

    var d = $('#dayCounter').val();

    var html = function(n) { return '\
        <tr id="' + n + '">\
            <td>\
                <label for="' + n + '">\
                    <select id="' + n + '">\
                        <option value="1" >Mandag</option>\
                        <option value="2" >Tirsdag</option>\
                        <option value="3" >Onsdag</option>\
                        <option value="4" >Torsdag</option>\
                        <option value="5" >Fredag</option>\
                        <option value="6" >Lørdag</option>\
                        <option value="7" >Søndag</option>\
                    </select>\
                </label>\
            </td>\
\
            <td>\
                <input type="text" id="' + n + '" name="' + n + '-start" />\
            </td>\
            <td>\
                <input type="text" id="' + n + '" name="' + n + '-end" />\
            </td>\
\
            <td>\
                <a id="' + n + '" name="removeDay" href="#">fjern</a>\
            </td>\
        </tr>';
    }

    
    $('#addDay').click(function() {

        

        d++;
        $('#dayCounter').val(d);

        $('#scheduleDays tr:last').after(html(d));

    });

    $(document).on('click', 'a[name="removeDay"]', function() {

        id = $(this).attr('id');

        $('#scheduleDays tr#' + id).remove();
        return false;

    });

});