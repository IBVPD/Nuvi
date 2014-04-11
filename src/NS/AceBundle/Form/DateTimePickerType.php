<?php

namespace NS\AceBundle\Form;

use \Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormView;
use \Symfony\Component\Form\FormInterface;
use \Symfony\Component\OptionsResolver\OptionsResolverInterface;
use \Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of DatePickerType
 *
 * @author gnat
 */
class DateTimePickerType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'date_widget'    => 'single_text',
            'date_format'    => 'MM/dd/yyyy',
            'time_widget'    => 'single_text',
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

//        $view->vars['attr']['data-date-format'] = $this->dateformatToJQueryUI($options['format']);
        $view->vars['type'] = 'text';
    }
    
    public function getName()
    {
        return 'acedatetime';
    }
    
    public function getParent()
    {
        return 'datetime';
    }

   /*
    * Matches each symbol of PHP date format standard
    * with jQuery equivalent codeword
    * @author Tristan Jahier
    */
   private function dateformatToJQueryUI($php_format)
   {
       $SYMBOLS_MATCHING = array(
           // Day
           'd' => 'dd',
           'D' => 'D',
           'j' => 'd',
           'l' => 'DD',
           'N' => '',
           'S' => '',
           'w' => '',
           'z' => 'o',
           // Week
           'W' => '',
           // Month
           'F' => 'MM',
           'm' => 'mm',
           'M' => 'M',
           'n' => 'm',
           't' => '',
           // Year
           'L' => '',
           'o' => '',
           'Y' => 'yy',
           'y' => 'y',
           // Time
           'a' => '',
           'A' => '',
           'B' => '',
           'g' => '',
           'G' => '',
           'h' => '',
           'H' => '',
           'i' => '',
           's' => '',
           'u' => ''
       );

       $jqueryui_format = "";
       $escaping        = false;

       for($i = 0; $i < strlen($php_format); $i++)
       {
           $char = $php_format[$i];
           if($char === '\\') // PHP date format escaping character
           {
               $i++;
               if($escaping) 
                   $jqueryui_format .= $php_format[$i];
               else 
                   $jqueryui_format .= '\'' . $php_format[$i];
               
               $escaping = true;
           }
           else
           {
                if($escaping) 
                { 
                    $jqueryui_format .= "'";
                    $escaping = false;
                }
                if(isset($SYMBOLS_MATCHING[$char]))
                   $jqueryui_format .= $SYMBOLS_MATCHING[$char];
                else
                   $jqueryui_format .= $char;
           }
       }
       
       return $jqueryui_format;
   }
}
