<?php

namespace NS\ImportBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\UnexpectedResultException;
use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Entity\Map;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description of Result
 *
 * @author gnat
 */
class ImportRepository extends EntityRepository
{
    /**
     * @param UserInterface $user
     * @param string $alias
     * @return \Doctrine\ORM\QueryBuilder
     * @throws \Doctrine\ORM\ORMException
     */
    public function getResultsForUser(UserInterface $user, $alias ='r')
    {
        return $this->createQueryBuilder($alias)
                    ->where(sprintf('%s.user = :user', $alias))
                    ->setParameter('user', $this->_em->getReference(get_class($user), $user->getId()))
                    ->orderBy($alias.'.id', 'DESC');
    }

    /**
     * @param UserInterface $user
     * @param $resultId
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function findForUser(UserInterface $user, $resultId)
    {
        try {
            return $this->createQueryBuilder('r')
                ->where('r.user = :user AND r.id = :id')
                ->setParameters(['user'=>$this->_em->getReference(get_class($user), $user->getId()), 'id'=>$resultId])
                ->getQuery()
                ->getSingleResult();
        } catch (UnexpectedResultException $exception) {
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function getStatistics($id)
    {
        try {
            $result = $this->createQueryBuilder('r')
                ->select('r.id,r.importedCount, r.processedCount, r.sourceCount, r.warningCount, r.skippedCount, r.status')
                ->where('r.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->setHydrationMode(Query::HYDRATE_ARRAY)
                ->getSingleResult();
            $result['percent'] = sprintf("%d%%", ($result['sourceCount'] > 0) ? (($result['processedCount']/$result['sourceCount']) * 100):0);
            $result['errorCount'] = $result['processedCount'] - $result['importedCount'];
            $result['status'] = $result['status'];

            return $result;
        } catch (UnexpectedResultException $exception) {
            return [];
        }
    }

    /**
     * @param Import $import
     * @param \Exception $exception
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setImportException(Import $import, \Exception $exception)
    {
        $exceptionStr = $exception->getMessage()."\n\n";

        foreach ($exception->getTrace() as $index => $trace) {
            $exceptionStr .= sprintf("%d: %s::%s on line %d\n", $index, isset($trace['class'])?$trace['class']:'Unknown', isset($trace['function'])?$trace['function']:'Unknown', isset($trace['line'])?$trace['line']:-1);
        }

        $import->setStackTrace($exceptionStr);
        $import->setStatus(Import::STATUS_BURIED);
        $this->_em->persist($import);
        $this->_em->flush($import);
    }

    public function getNewOrRunning()
    {
        try {
            return $this->createQueryBuilder('i')
                ->where('i.status = :new OR i.status = :running')
                ->setParameters([
                    'new' => Import::STATUS_NEW,
                    'running' => Import::STATUS_RUNNING,
                ])
                ->setMaxResults(1)
                ->getQuery()
                ->getSingleResult();
        } catch (UnexpectedResultException $exception) {
            return null;
        }
    }

    public function getMapResultCount(Map $map)
    {
        try {
            return $this->createQueryBuilder('i')
                ->select('COUNT(i.id)')
                ->where('i.map = :map')
                ->setParameter('map', $map)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (UnexpectedResultException $exception) {
            return 0;
        }
    }
}
