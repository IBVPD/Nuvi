<?php

namespace NS\ImportBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnexpectedResultException;
use Exception;
use NS\ImportBundle\Entity\Import;
use NS\ImportBundle\Entity\Map;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

class ImportRepository extends EntityRepository
{
    /**
     * @param UserInterface $user
     * @param string        $alias
     *
     * @return QueryBuilder
     * @throws ORMException
     */
    public function getResultsForUser(UserInterface $user, $alias = 'r'): QueryBuilder
    {
        return $this->createQueryBuilder($alias)
            ->where(sprintf('%s.user = :user', $alias))
            ->setParameter('user', $this->_em->getReference(get_class($user), $user->getId()))
            ->orderBy($alias . '.id', 'DESC');
    }

    public function findForUser(UserInterface $user, int $resultId): ?Import
    {
        try {
            return $this->createQueryBuilder('r')
                ->where('r.user = :user AND r.id = :id')
                ->setParameters(['user' => $this->_em->getReference(get_class($user), $user->getId()), 'id' => $resultId])
                ->getQuery()
                ->getSingleResult();
        } catch (UnexpectedResultException $exception) {
            return null;
        }
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getStatistics($id): ?array
    {
        try {
            $result               = $this->createQueryBuilder('r')
                ->select('r.id,r.importedCount, r.processedCount, r.sourceCount, r.warningCount, r.skippedCount, r.status')
                ->where('r.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->setHydrationMode(Query::HYDRATE_ARRAY)
                ->getSingleResult();
            $result['percent']    = sprintf("%d%%", ($result['sourceCount'] > 0) ? (($result['processedCount'] / $result['sourceCount']) * 100) : 0);
            $result['errorCount'] = $result['processedCount'] - $result['importedCount'];
            $result['status']     = $result['status'];

            return $result;
        } catch (UnexpectedResultException $exception) {
            return [];
        }
    }

    /**
     * @param Import              $import
     * @param Exception|Throwable $exception
     *
     * @throws OptimisticLockException
     */
    public function setImportException(Import $import, $exception): void
    {
        $exceptionStr = 'Unknown error';
        if ($exception instanceof Throwable || $exception instanceof Exception) {
            $exceptionStr = $exception->getMessage() . "\n\n";

            foreach ($exception->getTrace() as $index => $trace) {
                $exceptionStr .= sprintf("%d: %s::%s on line %d\n", $index, $trace['class'] ?? 'Unknown', $trace['function'] ?? 'Unknown', $trace['line'] ?? -1);
            }
        }

        $import->setStackTrace($exceptionStr);
        $import->setStatus(Import::STATUS_BURIED);
        $this->_em->persist($import);
        $this->_em->flush($import);
    }

    public function getNewOrRunning(): ?Import
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

    public function getMapResultCount(Map $map): int
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
