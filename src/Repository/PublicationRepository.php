<?php

namespace App\Repository;

use App\Entity\Publication;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    public function findPubForUser($id, FollowRepository $follow_repo, $user)
    {
        $following = $follow_repo->findBy(array('user' => $user));

        $following_array = array();
        foreach ($following as $follow) {
            $following_array[] = $follow->getFollowed();
        }

        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM App\Entity\Publication p
                WHERE p.user IN (:followed) OR p.user = :iduser ORDER BY p.CreatedAt DESC'
            )
            ->setParameter('followed', $following_array)
            ->setParameter('iduser', $id)
            ->getResult();
    }

/*
    public function findPub($id)
    {

        return $this->getEntityManager()
            ->createQuery(
                'SELECT p
                FROM App\Entity\Publication p
                WHERE p.user = :iduser ORDER BY p.CreatedAt DESC'
            )
            ->setParameter('iduser', $id)
            ->getResult();
    }
     */
    // /**
    //  * @return Publication[] Returns an array of Publication objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
     */

    /*
    public function findOneBySomeField($value): ?Publication
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
     */
}
