<?php

namespace NS\ImportBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
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
                    ->where(sprintf('%s.user = :user',$alias))
                    ->setParameter('user', $this->_em->getReference(get_class($user), $user->getId()))
                    ->orderBy($alias.'.id','DESC');
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
        return $this->createQueryBuilder('r')
                    ->where('r.user = :user AND r.id = :id')
                    ->setParameters(array('user'=>$this->_em->getReference(get_class($user), $user->getId()),'id'=>$resultId))
                    ->getQuery()
                    ->getSingleResult();
    }

    /**
     * @param $id
     * @return array|mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStatistics($id)
    {
        try {
            $result = $this->createQueryBuilder('r')
                ->select('r.id,r.importedCount, r.processedCount, r.sourceCount, r.warningCount, r.skippedCount')
                ->where('r.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->setHydrationMode(Query::HYDRATE_ARRAY)
                ->getSingleResult();
            $result['percent'] = sprintf("%d%%",($result['sourceCount'] > 0) ? (($result['processedCount']/$result['sourceCount']) * 100):0);
            $result['errorCount'] = $result['processedCount'] - $result['importedCount'];

            return $result;
        }
        catch(NoResultException $exception) {
            return array();
        }
    }
}
