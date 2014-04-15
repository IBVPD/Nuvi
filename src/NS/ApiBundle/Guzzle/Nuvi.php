<?php

namespace NS\ApiBundle\Guzzle;
use Orkestra\Bundle\GuzzleBundle\Services\Service as AbstractService;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Command;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Doc;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Param;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Headers;
use Orkestra\Bundle\GuzzleBundle\Services\Annotation\Type;

/**
 * Description of Nuvi
 *
 * @author gnat
 */
class Nuvi extends AbstractService
{
    /**
     * @Command(name="lastUpdated", method="GET", uri="/lastUpdated")
     * @Doc("Get list of Acme users")
     */
    public function lastUpdatedCommand()
    {
        return $this->getResponse();
    }

}
