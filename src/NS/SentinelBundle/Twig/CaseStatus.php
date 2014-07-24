<?php

namespace NS\SentinelBundle\Twig;

use NS\SentinelBundle\Entity\BaseCase;

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
            'case_label'     => new \Twig_Function_Method($this, 'getLabel',array('is_safe'=>array('html'))),
            'case_lab_label' => new \Twig_Function_Method($this, 'getLabLabel',array('is_safe'=>array('html'))),
        );
    }

    public function getLabLabel(BaseCase $obj, $message)
    {
        if($obj->hasLab())
            $class = $obj->getLab()->isComplete() ? 'label-success icon icon-ok':'label-warning icon icon-exclamation-sign';
        else
            $class = 'label-danger icon icon-exclamation-sign';

        return '<span class="label label-sm '.$class.'">'.$message.'</span>';
    }

    public function getLabel(BaseCase $obj, $message)
    {
        $noError = true;

        /* This needs to be re-written to check if we sent to them but the corresponding date received field is still empty

        if($obj->getLab()->getCsfSentToRRL() && !$obj->getLab()->getCsfRRLDateTime())
            $noError = false;

        if($obj->getLab()->getCsfSentToNL() && !$obj->getLab()->getCsfNLDateTime())
            $noError = false;

        if($obj->getLab()->getBloodSentToRRL() && !$obj->getLab()->getBloodRRLDateTime())
            $noError = false;

        if($obj->getLab()->getBloodSentToNL() && !$obj->getLab()->getBloodNLDateTime())
            $noError = false;

        if($obj->getLab()->getOtherSentToRRL() && !$obj->getLab()->getOtherRRLDateTime())
            $noError = false;

        if($obj->getLab()->getOtherSentToNL() && !$obj->getLab()->getOtherNLDateTime())
            $noError = false;


        if(!$obj->hasReferenceLab() && $obj->getSentToReferenceLab())
            $noError = false;

        if($obj->hasNationalLab() && !$obj->getSentToNationalLab())
            $noError = false;

        if(!$obj->hasNationalLab() && $obj->getSentToNationalLab())
            $noError = false;
        */

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
