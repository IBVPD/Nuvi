<?php

namespace NS\SentinelBundle\Form\RotaVirus\Types;

use NS\UtilBundle\Form\Types\TranslatableArrayChoice;

class ElisaKit extends TranslatableArrayChoice
{
    public const
        PROSPECT = 1,
        RIDA     = 2,
        ROTA     = 3,
        OTHER    = 99;

    protected $values = [
        self::PROSPECT    => 'ProspecT',
        self::RIDA        => 'Ridascreen',
        self::ROTA        => 'Rotaclone',
        self::OTHER       => 'Other',
    ];
}
