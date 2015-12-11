<?php

namespace NS\ImportBundle\Services;

use Doctrine\DBAL\DBALException;
use Leezy\PheanstalkBundle\Proxy\PheanstalkProxy;
use Doctrine\Common\Persistence\ObjectManager;
use NS\ImportBundle\Entity\Import;

class WorkQueue
{
    /**
     * @var PheanstalkProxy
     */
    private $pheanstalk;

    /**
     * @var ObjectManager
     */
    private $entityMgr;

    /**
     * @var ImportFileCreator
     */
    private $fileCreator;

    /**
     * WorkQueue constructor.
     * @param PheanstalkProxy $pheanstalk
     * @param ObjectManager $entityMgr
     */
    public function __construct(PheanstalkProxy $pheanstalk, ObjectManager $entityMgr, ImportFileCreator $fileCreator)
    {
        $this->pheanstalk = $pheanstalk;
        $this->entityMgr = $entityMgr;
        $this->fileCreator = $fileCreator;
    }

    /**
     * @param Import $import
     * @return bool
     */
    public function submit(Import $import)
    {
        try {
            $this->entityMgr->persist($import);
            $this->entityMgr->flush($import);

            $jobId = $this->pheanstalk->useTube('import')->put($import->getId(),null,null,180);

            $import->setPheanstalkJobId($jobId);
            $import->setPheanstalkStatus(Import::STATUS_RUNNING);

            $this->entityMgr->persist($import);
            $this->entityMgr->flush($import);

        } catch (\Pheanstalk_Exception_ConnectionException $excep) {
            return false;
        } catch (DBALException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param Import $import
     * @return bool
     */
    public function reSubmit(Import $import)
    {
        if($import->isBuried()) {
            $this->pheanstalk->useTube('import')->kickJob(new \Pheanstalk_Job($import->getPheanstalkJobId(),$import->getId()));
            $import->setPheanstalkStatus(Import::STATUS_RUNNING);
            $this->entityMgr->persist($import);
            $this->entityMgr->flush($import);

            return true;
        } elseif($import->getPheanstalkJobId()) {
            $newImport = new Import($import->getUser());
            $newImport->setMap($import->getMap());
            $newImport->setInputDateStart($import->getInputDateStart());
            $newImport->setInputDateEnd($import->getInputDateEnd());
            $newImport->setReferenceLab($import->getReferenceLab());

            $orgSourceFile = $import->getSourceFile();

            $newImport->setDuplicateFile($this->fileCreator->createNewFile($newImport, 'duplicate-state.txt', 'duplicateFile'));
            $newImport->setMessageFile($this->fileCreator->createNewFile($newImport, 'messages.csv', 'messageFile'));
            $newImport->setWarningFile($this->fileCreator->createNewFile($newImport, 'warnings.csv', 'warningFile'));
            $newImport->setErrorFile($this->fileCreator->createNewFile($newImport, 'errors.csv', 'errorFile'));
            $newImport->setSuccessFile($this->fileCreator->createNewFile($newImport, 'successes.csv', 'successFile'));
            $newImport->setSourceFile($this->fileCreator->createNewFile($newImport,$orgSourceFile->getFilename(),'sourceFile'));
            $newImport->setSource($orgSourceFile->getFilename());

            if(!copy($orgSourceFile->getPathname(),$newImport->getSourceFile())) {
                return false;
            }

            return $this->submit($newImport);
        }

        return $this->submit($import);
    }

}
