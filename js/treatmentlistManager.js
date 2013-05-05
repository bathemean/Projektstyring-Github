$(document).ready(function() {

    var html = function() { return '\
        <tr>\
          <td><input name="treatment[]" value=""</td>\
          <td><input name="duration[]" value=""</td>\
          <td><a id="remove" href="#">fjern</a>\
        </tr>';
    }

    var add = function() {
        $('#treatments tr:last').after(html());
    }

    
    $('#add').click(function() {
        add();        
    });

    $(document).on('click', 'a[id="remove"]', function() {

        $(this).parent().parent().remove();
        return false;

    });

});