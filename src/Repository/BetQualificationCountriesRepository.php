<?php

namespace App\Repository;

use App\Entity\BetQualificationCountries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BetQualificationCountries>
 *
 * @method BetQualificationCountries|null find($id, $lockMode = null, $lockVersion = null)
 * @method BetQualificationCountries|null findOneBy(array $criteria, array $orderBy = null)
 * @method BetQualificationCountries[]    findAll()
 * @method BetQualificationCountries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetQualificationCountriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BetQualificationCountries::class);
    }

    public function save(BetQualificationCountries $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BetQualificationCountries $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BetQualificationCountries[] Returns an array of BetQualificationCountries objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BetQualificationCountries
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
