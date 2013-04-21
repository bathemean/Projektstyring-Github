$(document).ready(function() {

    var d = $('#dayCounter').val();

    var timeSelect = function(name) {
        
        var hourOpts;
        var minOpts

        for(h = 1; h <= 24; h++) {
            var selected = (h == 10) ? 'selected' : '';
            hourOpts += '<option '+ selected +' value="'+ h +'">'+ h +'</option>';
        }

        for(m = 0; m < 4; m++) {
            minOpts += '<option value="'+ (m * 15) +'">'+ (m * 15) +'</option>';
        }

        return '<select name="'+ name +'-hours">'+ hourOpts +'</select>:\
                <select name="'+ name +'-min">'+ minOpts +'</select>';
    }

    var html = function(n) { return '\
        <tr id="' + n + '">\
            <td>\
                <label for="' + n + '">\
                    <select id="' + n + '" name="' + n + '-day">\
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
                '+ timeSelect(n+'-start') +'\
            </td>\
            <td>\
                '+ timeSelect(n+'-end') +'\
            </td>\
\
            <td>\
                <a id="' + n + '" name="removeDay" href="#">fjern</a>\
            </td>\
        </tr>';
    }

    var addDay = function() {
        d++;
        $('#dayCounter').val(d);
        $('#scheduleDays tr:last').after(html(d));
    }

    
    $('#addDay').click(function() {
        addDay();        
    });

    $(document).on('click', 'a[name="removeDay"]', function() {

        id = $(this).attr('id');

        $('#scheduleDays tr#' + id).remove();
        return false;

    });

    if(d == 0) {
        addDay();
    }

});