<?php

namespace App\Repository;

use App\Entity\BackUpFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BackUpFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackUpFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackUpFile[]    findAll()
 * @method BackUpFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackUpFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BackUpFile::class);
    }

//    /**
//     * @return BackUpFile[] Returns an array of BackUpFile objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BackUpFile
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
