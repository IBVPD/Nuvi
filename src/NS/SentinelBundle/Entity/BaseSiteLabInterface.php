<?php

namespace NS\SentinelBundle\Entity;

interface BaseSiteLabInterface
{
    public function getCaseFile(): BaseCase;

    public function setCaseFile(BaseCase $case): void;

    public function getSentToNationalLab(): bool;

    public function getSentToReferenceLab(): bool;
}
