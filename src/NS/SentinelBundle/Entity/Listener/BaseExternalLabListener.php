<?php

namespace NS\SentinelBundle\Entity\Listener;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use NS\SentinelBundle\Entity\BaseExternalLab;
use NS\SentinelBundle\Entity\Pneumonia\SiteLab;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Validators\Cache\CachedValidations;

class BaseExternalLabListener
{
    /** @var CachedValidations */
    protected $validator;

    public function __construct(CachedValidations $validator)
    {
        $this->validator = $validator;
    }

    public function preUpdate(BaseExternalLab $case, LifecycleEventArgs $event): void
    {
        $this->calculateStatus($case);
        $case->setUpdatedAt(new DateTime());
    }

    public function prePersist(BaseExternalLab $case, LifecycleEventArgs $event): void
    {
        $this->calculateStatus($case);
        $case->setUpdatedAt(new DateTime());
    }

    public function calculateStatus(BaseExternalLab $case): void
    {
        if ($case->getStatus()->equal(CaseStatus::CANCELLED)) {
            return;
        }

        $case->setUpdatedAt(new \DateTime());
        $caseFile = $case->getCaseFile();
        if (!$caseFile) {
            $case->setStatus(new CaseStatus(CaseStatus::OPEN));
            return;
        }

        $groups     = [$caseFile->getRegion()->getCode() . '+Completeness', 'Completeness'];
        $violations = $this->validator->validate($caseFile->getId(), $case, $groups, true);
        $case->setStatus(!empty($violations) ? new CaseStatus(CaseStatus::OPEN) : new CaseStatus(CaseStatus::COMPLETE));
    }
}
