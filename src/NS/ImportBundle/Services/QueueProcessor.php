<?php

namespace NS\ImportBundle\Services;

use \Doctrine\Common\Persistence\ObjectManager;
use \NS\ImportBundle\Filter\Duplicate;
use \NS\ImportBundle\Filter\NotBlank;
use \NS\ImportBundle\Importer\ImportProcessor;

/**
 * Description of QueueProcessor
 *
 * @author gnat
 */
class QueueProcessor
{
    private $entityMgr;

    private $processor;

    public function __construct(ObjectManager $entityMgr, ImportProcessor $processor)
    {
        $this->entityMgr = $entityMgr;
        $this->processor = $processor;
    }

    public function process()
    {
        $imports = $this->entityMgr->getRepository('NS\ImportBundle\Entity\Result')->findAll();
        foreach ($imports as $import) {
//            die("ImportFile: ".$import->getImportFile().' '.$import->getFilename());
            $this->processor->setDuplicate(new Duplicate(array('getcode' => 'site', 1 => 'caseId')));
            $this->processor->setNotBlank(new NotBlank(array('caseId', 'site')));
            $ret = $this->processor->process($import);
            $this->entityMgr->persist($ret);
        }
        $this->entityMgr->flush();
    }
}