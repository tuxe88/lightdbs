<?php

namespace App\Repository;

use App\Entity\DownloadTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DownloadTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method DownloadTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method DownloadTicket[]    findAll()
 * @method DownloadTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadTicketRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DownloadTicket::class);
    }

//    /**
//     * @return DownloadTicket[] Returns an array of DownloadTicket objects
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
    public function findOneBySomeField($value): ?DownloadTicket
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
