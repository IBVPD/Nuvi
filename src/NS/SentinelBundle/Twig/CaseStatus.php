<?php

namespace NS\SentinelBundle\Twig;

use NS\SentinelBundle\Entity\Meningitis;

/**
 * Description of CaseStatus
 *
 * @author gnat
 */
class CaseStatus extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'ibd_label'     => new \Twig_Function_Method($this, 'getMeningitisLabel',array('is_safe'=>array('html'))),
            'ibd_lab_label' => new \Twig_Function_Method($this, 'getMeningitisLabLabel',array('is_safe'=>array('html'))),
            'ibd_rrl_label' => new \Twig_Function_Method($this, 'getMeningitisRRLLabel',array('is_safe'=>array('html'))),
            'ibd_nl_label'  => new \Twig_Function_Method($this, 'getMeningitisNLLabel',array('is_safe'=>array('html'))),
        );
    }

    public function getMeningitisNLLabel(Meningitis $obj, $message)
    {
        if($obj->getSentToNationalLab() || $obj->hasNationalLab())
        {
            if($obj->getSentToNationalLab() && $obj->hasNationalLab())
                $class = ($obj->getNationalLab()->getIsComplete()) ? 'label-success icon icon-ok':'label-warning icon icon-warning-sign';
            else
                $class = 'label-danger icon icon-exclamation-sign';

            return '<span class="label label-sm '.$class.'">'. $message .'</span>';
        }

        return null;
    }

    public function getMeningitisRRLLabel(Meningitis $obj, $message)
    {
        if($obj->getSentToReferenceLab() || $obj->hasReferenceLab())
        {
            if($obj->getSentToReferenceLab() && $obj->hasReferenceLab())
                $class = ($obj->getReferenceLab()->getIsComplete()) ? 'label-success icon icon-ok':'label-warning icon icon-warning-sign';
            else
                $class = 'label-danger icon icon-exclamation-sign';

            return '<span class="label label-sm '.$class.'">'. $message .'</span>';
        }

        return null;
    }

    public function getMeningitisLabLabel(Meningitis $obj, $message)
    {
        if($obj->hasSiteLab())
            $class = $obj->getSiteLab()->isComplete() ? 'label-success icon icon-ok':'label-warning icon icon-exclamation-sign';
        else
            $class = 'label-danger icon icon-exclamation-sign';

        return '<span class="label label-sm '.$class.'">'.$message.'</span>';
    }

    public function getMeningitisLabel(Meningitis $obj, $message)
    {
        $noError = true;

        if($obj->hasReferenceLab() && !$obj->getSentToReferenceLab())
            $noError = false;

        if(!$obj->hasReferenceLab() && $obj->getSentToReferenceLab())
            $noError = false;

        if($obj->hasNationalLab() && !$obj->getSentToNationalLab())
            $noError = false;

        if(!$obj->hasNationalLab() && $obj->getSentToNationalLab())
            $noError = false;

        if($noError)
            $class = ($obj->isComplete()) ? 'label-success icon icon-ok':'label-warning icon icon-exclamation-sign';
        else
            $class = 'label-danger icon icon-exclamation-sign';

        return '<span class="label label-sm '.$class.'">'.$message.'</span>';
    }

    public function getName()
    {
        return 'twig_case_status';
    }
}
