<?php

namespace App\Repository;

use App\Entity\UserScores;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserScores>
 *
 * @method UserScores|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserScores|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserScores[]    findAll()
 * @method UserScores[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserScoresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserScores::class);
    }

    public function save(UserScores $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserScores $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findPositionLeadboardUser(int $id_user): int
    {
        $qb = $this->createQueryBuilder('p')->select("p")->orderBy('p.scores', 'DESC');
        $query = $qb->getQuery();
        $query->execute();
        $scores_save = 0;
        $leadboard = 0;
        foreach ($query->execute() as $key => $user) {
            if($user->getScores() != $scores_save) {
                $scores_save = $user->getScores();
                $leadboard++;
            }
            if($user->getUser()->getId() == $id_user) {
                return $leadboard;
            }
        }
    }
}
