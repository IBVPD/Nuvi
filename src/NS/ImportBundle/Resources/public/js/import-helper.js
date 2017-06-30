(function($)
{
    $.ImportHelper = function(options, autoinit)
    {
        this.options       = options !== undefined ? options: {};
        this.ibd_conf_url  = options.ibd_conf_url ? options.ibd_conf_url : 'http://who-pan/%%lang%%/fields/ibd';
        this.rota_conf_url = options.rota_conf_url ? options.rota_conf_url : 'http://who-pan/%%lang%%/fields/rotavirus';

        this.ibd  = {conf: {}, prefixes: {'':'ibd', 'siteLab': 'siteLab', 'referenceLab':'rl', 'nationalLab':'nl'}};
        this.rotavirus = {conf: {}, prefixes: {'':'rotavirus', 'siteLab': 'siteLab', 'referenceLab':'rl', 'nationalLab':'nl'}};

        this.initialized = false;

        if(autoinit !== undefined && autoinit)
        {
            this.Init();
        }
    };

    $.ImportHelper.prototype = {
        Init: function()
        {
            this.GetConfs();
        },

        GetConfs: function()
        {
            this.GetConf(this.ibd_conf_url, "ibd"); //String, because Javascript can't pass by reference so we have to use array syntax to update the property
            this.GetConf(this.rota_conf_url, "rotavirus");
        },

        GetConf: function(url, conf)
        {
            var helper = this;
            $.ajax(url).success(function(data)
            {
                //Case consistency can't be assured here.  Make all the config indexes lowercase
                $.each(data, function(name, value)
                {
                    delete data[name];
                    var lname = name.toLowerCase();
                    data[lname] = value;

                    $.each(data[lname], function(name, value)
                    {
                        delete data[lname][name];
                        data[lname][name.toLowerCase()] = value;
                    });
                });

               helper[conf].conf = data;
               helper._init();
            }).error(function(data, status, error)
            {
                console.log('Unable to retrieve conf: '+conf+' - '+status+': "'+error+'"');
            });
        },

        _init: function()
        {
            if(this.ibd.conf.ibd !== undefined && this.rotavirus.conf.rotavirus !== undefined && !this.initialized)
            {
                this.initialized = true;
                this.AddListeners();
            }
        },

        AddListeners: function()
        {
            var helper = this;
            var firstRun = true;

            $('[data-dbcolumn]').on('change', function(data, event)
            {
                if($(this).parent().find('.help').length == 0)
                {
                    $(this).parent().append($('<div class="help mapperHelp"></div>'));
                }

                var data_class = helper.GetDataClass(),
                    val     = $(this).val(),
                    split   = val.indexOf('.') != -1  ? val.indexOf('.') : 0,
                    prefix  = helper[data_class].prefixes[val.substr(0, split)].toLowerCase(), //nl., rl., siteLab., || empty string if value is ibd or rotavirus
                    field   = val.substr(split != 0 ?  split+1 : 0).toLowerCase(), //If the column name has a prefix, the "." will be included in the field name, so we have to move the string up character
                    conf    = helper[data_class].conf[prefix][field];

                var help = helper.GetHelpText(conf),
                    converter = helper.GetClassLabel(conf);

                var $validator_select = $('[data-converter][data-ref=' + $(this).data('ref')+']');

                if(!firstRun || (firstRun && !$validator_select.val()))
                {
                    if (converter)
                    {
                        var $option = $validator_select.find('option[data-converterref="' + converter + '"]');
                        $validator_select.val($option.attr('value')).change(); //Fire the change event so Select2 will catch it and update the pretty UI
                    }
                    else
                    {
                        $validator_select.val('').change();
                    }
                }

                $(this).parent().find('.help').html(help);
            }).change(); //Fire once on load for default values

            firstRun = false;
        },

        GetDataClass: function()
        {
            var val = $('[data-Class]').val();
            val = val.substr(val.lastIndexOf('\\')+1);
            return val.toLowerCase();
        },

        GetHelpText: function(conf)
        {
            var text = '';
            if(conf instanceof Object)
            {
                //convert this to an array so we can implode it
                var options = $.map(conf.options, function(value, index)
                {
                    return [index+': '+value];
                });

                var label = this.GetClassLabel(conf);
                text = '<strong>'+label+'</strong><br /><em>'+options.join(' | ')+'</em>';
            }
            else if(conf)
            {
                text = '<strong>'+conf+'</strong>';
            }

            return text ? 'Format: '+text : '';
        },

        GetClassLabel: function(conf)
        {
            if(conf instanceof Object)
            {
                return conf.class.substr(conf.class.lastIndexOf('\\')+1); //Convert the FQDN to simple class name
            }

            return false;
        }
    };
}(jQuery));
