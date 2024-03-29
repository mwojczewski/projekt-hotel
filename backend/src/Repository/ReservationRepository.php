<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 *
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function save(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Reservation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Reservation[] Returns an array of Reservation objects
     */
    public function findConflicts(?\DateTimeImmutable $start = null, ?\DateTimeImmutable $end = null, ?int $room_id = null): array
    {
        return $this->createQueryBuilder('r')
            // ->where('r.starts_at BETWEEN :start AND :end')
            ->where('r.starts_at BETWEEN :start AND :end OR r.ends_at BETWEEN :start AND :end')
            ->orWhere(':start BETWEEN r.starts_at AND r.ends_at OR :end BETWEEN r.starts_at AND r.ends_at')
            // ->orWhere('r.ends_at BETWEEN :start AND :end')
            // ->orWhere(':start BETWEEN r.starts_at AND r.ends_at')
            // ->orWhere(':end BETWEEN r.starts_at AND r.ends_at')
            ->andWhere('r.room_id = :room_id')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('room_id', $room_id)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
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

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
