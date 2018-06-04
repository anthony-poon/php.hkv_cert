<?php

namespace App\Repository;

use App\Entity\DocBase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DocBase|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocBase|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocBase[]    findAll()
 * @method DocBase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocBaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocBase::class);
    }

//    /**
//     * @return DocBase[] Returns an array of DocBase objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocBase
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
