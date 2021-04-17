<?php declare(strict_types=1);

namespace NS\SentinelBundle\Report\Result\RotaVirus;

use NS\SentinelBundle\Form\RotaVirus\Types\DischargeClassification;
use RuntimeException;

class DischargeClassificationDosesAgeResult
{
    /** @var int */
    private $classification;

    private $doses;

    private $labels = [
        DischargeClassification::CONFIRMED  => 'Confirmed',
        DischargeClassification::DISCARDED  => 'Discarded',
        DischargeClassification::INADEQUATE => 'Inadequately Investigated',
        DischargeClassification::UNKNOWN    => 'Unknown',
    ];

    public function __construct(int $classification)
    {
        if (!isset($this->labels[$classification])) {
            throw new \InvalidArgumentException("Classification $classification is not valid");
        }

        $this->classification = $classification;

        $counts = [
            -1 => 0,
            2  => 0,
            3  => 0,
            11 => 0,
            23 => 0,
            59 => 0,
        ];

        $this->doses = [
            0  => $counts,
            1  => $counts,
            2  => $counts,
            3  => $counts,
            99 => $counts,
        ];
    }

    public function getClassification(): string
    {
        return $this->labels[$this->classification];
    }

    public function getDoses(): array
    {
        return array_keys($this->doses);
    }

    public function set(int $dose, int $age, int $count): void
    {
        if (!isset($this->doses[$dose][$age])) {
            throw new RuntimeException("Invalid arguments Dose: $dose, Age: $age");
        }

        $this->doses[$dose][$age] = $count;
    }

    public function get(int $dose, int $age): int
    {
        if (!isset($this->doses[$dose][$age])) {
            throw new RuntimeException("Invalid arguments Dose: $dose, Age: $age");
        }

        return $this->doses[$dose][$age];
    }
}
