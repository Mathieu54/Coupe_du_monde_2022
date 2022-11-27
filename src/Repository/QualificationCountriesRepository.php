<?php

namespace App\Repository;

use App\Entity\QualificationCountries;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QualificationCountries>
 *
 * @method QualificationCountries|null find($id, $lockMode = null, $lockVersion = null)
 * @method QualificationCountries|null findOneBy(array $criteria, array $orderBy = null)
 * @method QualificationCountries[]    findAll()
 * @method QualificationCountries[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QualificationCountriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QualificationCountries::class);
    }

    public function save(QualificationCountries $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QualificationCountries $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
