<?php

namespace NS\ImportBundle\Form\Type;

/**
 * Description of IBDColumnType
 *
 * @author gnat
 */
class IBDColumnType extends ColumnType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ibd_columns';
    }
}