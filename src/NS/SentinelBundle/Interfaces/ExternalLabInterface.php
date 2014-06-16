<?php

namespace NS\SentinelBundle\Interfaces;

/**
 *
 * @author gnat
 */
interface ExternalLabInterface
{
    public function hasReferenceLab();
    public function getReferenceLab();
    public function getSentToReferenceLab();

    public function hasNationalLab();
    public function getNationalLab();
    public function getSentToNationalLab();
}
