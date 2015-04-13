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
});