<?php

namespace NS\ImportBundle\Services;

use Doctrine\DBAL\DBALException;
use Doctrine\Common\Persistence\ObjectManager;
use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Vich\NonUTF8FileException;
use Pheanstalk\Exception\ConnectionException;
use Pheanstalk\Job;

class WorkQueue
{
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
     * @param ObjectManager $entityMgr
     * @param ImportFileCreator $fileCreator
     */
    public function __construct(ObjectManager $entityMgr, ImportFileCreator $fileCreator)
    {
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

        } catch (NonUTF8FileException $exception) {
            return 'The file is not UTF-8 encoded.';
        } catch (DBALException $exception) {
            return 'There was an error communicating with the backend database';
        }

        return true;
    }

    /**
     * @param Import $import
     * @return bool
     */
    public function reSubmit(Import $import)
    {
        if ($import->isBuried()) {
            $import->setStatus(Import::STATUS_RUNNING);
            $this->entityMgr->persist($import);
            $this->entityMgr->flush($import);

            return true;
        }

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
        $newImport->setSourceFile($this->fileCreator->createNewFile($newImport, $orgSourceFile->getFilename(), 'sourceFile'));
        $newImport->setSource($orgSourceFile->getFilename());

        if (!copy($orgSourceFile->getPathname(), $newImport->getSourceFile())) {
            return false;
        }

        return $this->submit($newImport);
    }

    public function pause(Import $import)
    {
        if (!$import->isComplete() && !$import->isBuried()) {
            $import->setStatus(Import::STATUS_PAUSED);
            $this->entityMgr->persist($import);
            $this->entityMgr->flush($import);

            return true;
        }

        return false;
    }

    public function resume(Import $import)
    {
        if ($import->isPaused()) {
            $import->setStatus(Import::STATUS_RUNNING);
            $this->entityMgr->persist($import);
            $this->entityMgr->flush($import);

            return true;
        }

        return false;
    }
}
