$(document).ready(function()
{
    $('select.inputOrSelect').each(function()
    {
        if($(this).val() !== '')
            $(this).prev('input.inputOrSelect').hide();
    });
    
    $('select.inputOrSelect').change(function()
    {
        if($(this).val() === '')
        {
            $(this).prev('input.inputOrSelect').show().focus();
        }
        else
        {
            $(this).prev('input.inputOrSelect').hide();
        }
    });

    // Hide dropped columns in mapper
    var $toggle = $('<label>' +
    '<input class="ace ace-switch" type="checkbox">' +
    '<span class="lbl" data-lbl="HIDE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SHOW"></span>' +
    '</label>');

    $toggle.find('input[type=checkbox]').change(function(event)
    {
        var count = 0;
        if($(this).is(':checked'))
        {
            var $rows = $('td.field-ignored input[type=checkbox]:checked').closest('tr');
            count = $rows.length;
            $rows.hide();
        }
        else
        {
            $('td.field-ignored input[type=checkbox]:checked').closest('tr').show();
        }

        $(this).closest('table').find('tr.numHidden:first td:first').html(count+' rows hidden.');
    });

    $('th.field-ignored').append($toggle);

    var $tbody = $('th.field-ignored').closest('thead').next('tbody');
    var $cols = $tbody.children('tr:first').children();

    $tbody.prepend('<tr class="numHidden"><td colspan="'+$cols.length+'"></td></tr>');

    $toggle.find('input[type=checkbox]').prop('checked', true).change();
});