<?php

namespace NS\SentinelBundle\Interfaces;

/**
 *
 * @author gnat
 */
interface SerializedSitesInterface
{
    public function hasMultipleSites();
    public function setSites(array $sites);
    public function getSites();
    public function getSite($managed = false);
}
