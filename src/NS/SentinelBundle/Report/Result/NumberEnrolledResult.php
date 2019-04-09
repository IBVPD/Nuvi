<?php

namespace NS\SentinelBundle\Report\Result;

use NS\SentinelBundle\Form\IBD\Types\Diagnosis;

class NumberEnrolledResult
{
    /** @var array */
    private $resultByMonth;

    /** @var array */
    private $headers;

    /** @var array */
    private $empty;

    public function __construct()
    {
        $diagnosis   = new Diagnosis();
        $dValues     = $diagnosis->getValues();
        $this->empty = array_fill_keys(array_keys($dValues), 0);

        // Special values (NOT_SET and OUT_OF_RANGE)
        $this->empty[-1]    = 0;
        $this->empty[-9999] = 0;

        $this->resultByMonth = array_fill(1, 12, $this->empty);
        $this->headers       = array_merge(['month' => 'Month'], $dValues, [-1 => 'Not set', -9999 => 'Out of Range']);
    }

    public function load(array $inputResults): void
    {
        foreach ($inputResults as $res) {
            $this->resultByMonth[$res['AdmissionMonth']][$res['adm_dx']->getValue()] = $res['admDxCount'];
        }
    }

    /**
     * @param bool|false $asStrings
     *
     * @return array
     */
    public function getHeaders($asStrings = false): array
    {
        if ($asStrings) {
            return array_flip($this->headers);
        }

        return $this->headers;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->resultByMonth;
    }

    /**
     * @param $month
     *
     * @return array
     */
    public function getMonthResult($month): array
    {
        return $this->resultByMonth[$month] ?? $this->empty;
    }
}
