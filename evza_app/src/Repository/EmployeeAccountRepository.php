<?php

namespace App\Repository;

use App\Entity\EmployeeAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EmployeeAccount>
 *
 * @method EmployeeAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmployeeAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmployeeAccount[]    findAll()
 * @method EmployeeAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeAccount::class);
    }

    public function getAccountsForUser(int $userId): array
    {
        $accounts = [
            new EmployeeAccount()
        ];
    }

//    /**
//     * @return EmployeeAccount[] Returns an array of EmployeeAccount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EmployeeAccount
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
