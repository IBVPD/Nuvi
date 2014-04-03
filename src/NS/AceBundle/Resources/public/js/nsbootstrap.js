$(document).ready(function() {
    $('.date-picker').datepicker({autoclose:true}).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

    $('input[type=file]:not([multiple])').ace_file_input({
        no_file:'No File ...',
        btn_choose:'Choose',
        btn_change:'Change',
        droppable:false,
        onchange:null,
        thumbnail:false //| true | large
        //whitelist:'gif|png|jpg|jpeg'
        //blacklist:'exe|php'
        //onchange:''
        //
    });

    $('div.nsFileUpload div.nsUploader').each(function(i, el)
    {
        $(el).after('\
            <div class="ace-file-input">\
                <label class="file-label" data-title="Browse">\
                    <span class="file-name" data-title="Drop file here or click &quot;Browse&quot;...">\
                        <i class="icon-upload-alt"></i>\
                    </span>\
                </label>\
            </div>');
        new PunkAveFileUploader({
            'uploadUrl': $(el).data('uploadUrl'),
            'viewUrl': $(el).data('uploadUrl'),
            'el': $(el),
            'existingFiles': [],
            'delaySubmitWhileUploading': '.edit-form'
        });
    });

    $('input.nsAutocompleter').each(function(i, el)
    {
        $(el).tokenInput($(el).data('autocompleteurl'), $(el).data('options'));
    });
    
    $('input.nsTag').each(function(i, el)
    {
        $(el).tag({source:$(el).data('source'), caseInsensitive:$(el).data('case-insensitive'), allowDuplicates:$(el).data('case-allow-duplicates'), autocompleteOnComma:$(el).data('autocomplete-on-comma')});
    });

    $('input.nsSpinner').each(function(i, el)
    {
        $(el).ace_spinner($(el).data('options'));
    });

    $('input.nsMasked').each(function(i, el)
    {
        $.extend($.mask.definitions, $(el).data('definitions'));
        
        $(el).mask($(el).data('mask'), {placeholder:$(el).data('placeholder')});
        $(el).parents('div.form-group').children('label').append(' <small class="text-info">'+$(el).data('mask')+'</small>');
    });

    $('input.nsKnob').knob();

    $('input.time-picker').timepicker({
					minuteStep: 1,
					showSeconds: ($(this).data('showSeconds') === 'true'),
					showMeridian: ($(this).data('showMeridian') === 'true'),
                    defaultTime: false
				}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
    $('a.filter_legend').click(function(event)
    {
        var icon = $(this).find('i');
        icon.toggleClass('icon-chevron-down').toggleClass('icon-chevron-up');
        $(this).find('span').html((icon.hasClass('icon-chevron-up')?'Simple':'Advanced'));
        
        $(this).parents('.widget-box').find('div.filter_container .sonata-filter-option').toggle();
    });
    
    $('select[data-context-group]:not([data-context-field])').each(function(i, el)
    {
        $(el).change(function(event)
        {
            toggleContextFields(el);
        });
        
        toggleContextFields(el);
    });

    function toggleContextFields(el)
    {
        $('[data-context-field][data-context-group='+$(el).data('context-group')+']').each(function(i, input)
        {
            var active = [];
            if($(input).data('context-field') == el.options[el.selectedIndex].getAttribute('data-context-field'))
            {
                active.push(input.id);
                var p = $(input).parent();
            
                if($(p).hasClass('input-group'))
                    $(p).show();
                else
                    $(input).show();

                $('label[for='+input.id+']').show();
            }
            else if(active.indexOf(input.id) < 0)
            {           
                var p = $(input).parent();

                if($(p).hasClass('input-group'))
                    $(p).hide();
                else
                    $(input).hide();

                $('label[for='+input.id+']').hide();
            }
        });
    }
});
