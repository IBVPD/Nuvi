<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of ElisaKit
 *
 */
class ElisaKit extends TranslatableArrayChoice
{
    public const PROSPECT = 1;
    public const RIDA     = 2;
    public const ROTA     = 3;
    public const OTHER    = 99;

    protected $values = [
        self::PROSPECT    => 'ProspecT',
        self::RIDA        => 'Ridascreen',
        self::ROTA        => 'Rotaclone',
        self::OTHER       => 'Other',
    ];
}
