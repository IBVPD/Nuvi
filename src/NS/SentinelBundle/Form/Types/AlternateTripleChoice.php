<?php

namespace NS\SentinelBundle\Form\Types;

/**
 * Description of AlternateTripleChoice
 *
 */
class AlternateTripleChoice extends TripleChoice
{
    const NOT_APPLICABLE = 3;

    protected $values = array(
        self::YES            => 'Yes',
        self::NO             => 'No',
        self::NOT_APPLICABLE => 'Not Applicable',
    );

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'AlternateTripleChoice';
    }
}
