<?php

namespace ForumBundle\Repository;

/**
 * CommentaireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentaireRepository extends \Doctrine\ORM\EntityRepository
{


    public function nombrecommentaires($id)
    {
        return $this->createQueryBuilder('r')

            ->select('COUNT(r)')
            ->where('r.idF=:id' )
            ->setParameter('id', $id)
            ->getQuery()

            ->getSingleScalarResult();
    }

}