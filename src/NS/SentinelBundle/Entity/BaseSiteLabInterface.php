<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 2017-03-07
 * Time: 7:36 PM
 */

namespace NS\SentinelBundle\Entity;


interface BaseSiteLabInterface
{
    /**
     * @return BaseCase
     */
    public function getCaseFile();

    /**
     *
     * @param BaseCase $case
     * @return BaseSiteLabInterface
     */
    public function setCaseFile(BaseCase $case);

    /**
     * Get sentToNationalLab
     *
     * @return boolean
     */
    public function getSentToNationalLab();

    /**
     * Get sentToReferenceLab
     *
     * @return boolean
     */
    public function getSentToReferenceLab();
}
