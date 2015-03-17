<?php

namespace NS\ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
/**
 * Description of Client
 *
 * @author gnat
 */
class ClientRepository extends EntityRepository
{
    public function getForUser($user)
    {
        return $this->createQueryBuilder('c')
             ->addSelect('u')
             ->innerJoin('c.user', 'u')
             ->where('c.user = :user')
             ->setParameter('user', $user)
             ->getQuery()
             ->getResult();
    }
}
