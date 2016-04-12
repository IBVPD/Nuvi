<?php

namespace NS\ImportBundle\Services;


class CamelCaser
{
    /**
     * @param $input
     * @return mixed
     */
    static public function process($input)
    {
        if (empty($input)) {
            return $input;
        }

        $output = preg_replace("/[^A-Za-z0-9 ]/", ' ', strtolower($input));

        return str_replace(' ', '', lcfirst(ucwords($output)));
    }
}
