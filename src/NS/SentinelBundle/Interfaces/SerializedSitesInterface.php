<?php

namespace NS\SentinelBundle\Interfaces;

/**
 *
 * @author gnat
 */
interface SerializedSitesInterface
{
    public function hasMultipleSites();
    public function getSites();
    public function getSite($managed = false);
}
