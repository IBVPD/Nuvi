<?php

namespace NS\SentinelBundle\Entity;

use DateTime;
use NS\SentinelBundle\Form\Types\CaseStatus;

interface BaseSiteLabInterface
{
    public function getCaseFile(): BaseCase;
    public function setCaseFile(BaseCase $case): void;
    public function getSentToNationalLab(): bool;
    public function getSentToReferenceLab(): ?bool;
    public function setUpdatedAt(DateTime $updatedAt): void;
    public function getStatus(): CaseStatus;
    public function setStatus(CaseStatus $status): void;
}
