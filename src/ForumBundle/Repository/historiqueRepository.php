<?php

namespace ForumBundle\Repository;

/**
 * historiqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class historiqueRepository extends \Doctrine\ORM\EntityRepository
{
    public function deletehistorique()
    {
        $query = $this->getEntityManager()
            ->createQuery("Delete  FROM ForumBundle:historique  ");
        return $query->getResult();
    }
}
