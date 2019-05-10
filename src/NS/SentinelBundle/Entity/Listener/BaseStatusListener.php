<?php

namespace NS\SentinelBundle\Entity\Listener;

use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use NS\SentinelBundle\Entity\BaseSiteLabInterface;
use NS\SentinelBundle\Form\Types\CaseStatus;
use NS\SentinelBundle\Validators\Cache\CachedValidations;

class BaseStatusListener
{
    /** @var CachedValidations */
    protected $validator;

    public function __construct(CachedValidations $validator)
    {
        $this->validator = $validator;
    }

    public function preUpdate(BaseSiteLabInterface $case, LifecycleEventArgs $event): void
    {
        $this->calculateStatus($case);
        $case->setUpdatedAt(new DateTime());
    }

    public function prePersist(BaseSiteLabInterface $case, LifecycleEventArgs $event): void
    {
        $this->calculateStatus($case);
        $case->setUpdatedAt(new DateTime());
    }

    public function calculateStatus(BaseSiteLabInterface $case): void
    {
        if ($case->getStatus()->equal(CaseStatus::CANCELLED)) {
            return;
        }

        $caseFile   = $case->getCaseFile();
        $groups     = [$caseFile->getRegion()->getCode() . '+Completeness', 'Completeness'];
        $violations = $this->validator->validate($caseFile->getId(), $case, $groups, true);

        $case->setStatus(!empty($violations) ? new CaseStatus(CaseStatus::OPEN) : new CaseStatus(CaseStatus::COMPLETE));
    }
}
