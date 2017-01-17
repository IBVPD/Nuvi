<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

/**
 * Description of ElisaKit
 *
 */
class ElisaKit extends TranslatableArrayChoice
{
    const PROSPECT = 1;
    const RIDA     = 2;
    const ROTA     = 3;
    const OTHER    = 99;

    protected $values = [
        self::PROSPECT    => 'ProspecT',
        self::RIDA        => 'Ridascreen',
        self::ROTA        => 'Rotaclone',
        self::OTHER       => 'Other',
    ];
}
