<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 2016-04-05
 * Time: 3:44 PM
 */

namespace NS\SentinelBundle\Twig;


class WidgetExtension extends \Twig_Extension
{
    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('widget_filter_toolbox',array($this,'renderFilterToolbox'),array('is_safe'=>array('html')))
        );
    }

    public function renderFilterToolbox()
    {
        return '<div class="widget-toolbar">
                    <a href="#filters" data-toggle="collapse" class="white small">
                        <i class="ace-icon fa fa-plus" data-icon-hide="fa-minus" data-icon-show="fa-plus"></i>
                        Filters
                    </a>
                </div>
                <div class="widget-toolbar no-border">
                    <a href="#" data-action="fullscreen" class="white">
                        <i class="ace-icon fa fa-expand"></i>
                    </a>
                </div>';
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'widget_extension';
    }

}
