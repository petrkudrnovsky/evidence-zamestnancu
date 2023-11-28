<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    /**
     * @param $count - amount of Employees
     * @return Employee[]
     */
    public function getNewestEmployees($count): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySearchQuery(string $query): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.accounts', 'a')
            ->leftJoin('e.positions', 'p')
            ->where('LOWER(e.firstName) LIKE :searchTerm')
            ->orWhere('LOWER(e.secondName) LIKE :searchTerm')
            ->orWhere('LOWER(CONCAT(e.firstName, \' \', e.secondName)) LIKE :searchTerm')
            ->orWhere('LOWER(e.email) LIKE :searchTerm')
            ->orWhere('LOWER(e.phoneNumber) LIKE :searchTerm')
            ->orWhere('LOWER(a.name) LIKE :searchTerm')
            ->orWhere('LOWER(p.name) LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . strtolower($query) . '%');

        return $qb->getQuery()->getResult();
    }

    public function findBySearchQueryEmployeeOnly(string $query): array
    {
        $qb = $this->createQueryBuilder('e')
            ->where('LOWER(e.firstName) LIKE :searchTerm')
            ->orWhere('LOWER(e.secondName) LIKE :searchTerm')
            ->orWhere('LOWER(CONCAT(e.firstName, \' \', e.secondName)) LIKE :searchTerm')
            ->orWhere('LOWER(e.email) LIKE :searchTerm')
            ->orWhere('LOWER(e.phoneNumber) LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . strtolower($query) . '%');

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Employee[] Returns an array of Employee objects
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

//    public function findOneBySomeField($value): ?Employee
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
