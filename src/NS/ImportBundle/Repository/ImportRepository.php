<?php

namespace NS\ImportBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\UnexpectedResultException;
use NS\ImportBundle\Entity\Import;
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
     * @throws NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     */
    public function findForUser(UserInterface $user, $resultId)
    {
        try {
            return $this->createQueryBuilder('r')
                ->where('r.user = :user AND r.id = :id')
                ->setParameters(array('user'=>$this->_em->getReference(get_class($user), $user->getId()), 'id'=>$resultId))
                ->getQuery()
                ->getSingleResult();
        } catch (UnexpectedResultException $exception) {
        }
    }

    /**
     * @param $id
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStatistics($id)
    {
        try {
            $result = $this->createQueryBuilder('r')
                ->select('r.id,r.importedCount, r.processedCount, r.sourceCount, r.warningCount, r.skippedCount, r.pheanstalkStatus')
                ->where('r.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->setHydrationMode(Query::HYDRATE_ARRAY)
                ->getSingleResult();
            $result['percent'] = sprintf("%d%%", ($result['sourceCount'] > 0) ? (($result['processedCount']/$result['sourceCount']) * 100):0);
            $result['errorCount'] = $result['processedCount'] - $result['importedCount'];
            $result['status'] = $result['pheanstalkStatus'];

            return $result;
        } catch (UnexpectedResultException $exception) {
            return array();
        }
    }

    /**
     * @param $importId
     * @param \Exception $exception
     *
     * @return mixed
     */
    public function setImportException($importId, \Exception $exception)
    {
        $exceptionStr = $exception->getMessage()."\n\n";

        foreach ($exception->getTrace() as $index => $trace) {
            $exceptionStr .= sprintf("%d: %s::%s on line %d\n", $index, isset($trace['class'])?$trace['class']:'Unknown', isset($trace['function'])?$trace['function']:'Unknown', isset($trace['line'])?$trace['line']:-1);
        }

        return $this->_em->createQueryBuilder()
            ->update($this->getClassName(), 'i')
            ->set('i.pheanstalkStackTrace', ':exceptStr')
            ->set('i.pheanstalkStatus', ':status')
            ->where('i.id = :id')
            ->setParameter('id', $importId)
            ->setParameter('exceptStr', $exceptionStr)
            ->setParameter('status', Import::STATUS_BURIED)
            ->getQuery()
            ->execute();
    }
}
