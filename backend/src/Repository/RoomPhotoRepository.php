<?php

namespace App\Repository;

use App\Entity\RoomPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RoomPhoto>
 *
 * @method RoomPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoomPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoomPhoto[]    findAll()
 * @method RoomPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoomPhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoomPhoto::class);
    }

    public function save(RoomPhoto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RoomPhoto $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RoomPhoto[] Returns an array of RoomPhoto objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RoomPhoto
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
