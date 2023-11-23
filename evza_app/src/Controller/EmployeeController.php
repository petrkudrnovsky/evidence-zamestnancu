<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    /**
     * @param EmployeeRepository $repository
     * @return Response
     */
    #[Route('/', name: 'app_index')]
    public function index(EmployeeRepository $repository): Response
    {
        $newestEmployees = $repository->getNewestEmployees(5);

        return $this->render('pages/index.html.twig', [
            'newest_employees' => $newestEmployees
        ]);
    }

    /**
     * @param EmployeeRepository $repository
     * @return Response
     */
    #[Route('/employees', name: 'app_employees_index')]
    public function listAllEmployees(EmployeeRepository $repository): Response
    {
        $allEmployees = $repository->findAll();
        return $this->render('pages/overview.html.twig', [
            'employees' => $allEmployees
        ]);
    }

    /**
     * @param int $id ID of the employee
     * @param EmployeeRepository $repository
     * @return Response
     */
    #[Route('/employee/{id}', name: 'app_employee_detail')]
    public function showUserDetail(int $id, EmployeeRepository $repository): Response
    {
        $user = $repository->find($id);

        return $this->render('pages/detail.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param int $id ID of the employee
     * @param EmployeeRepository $repository
     * @return Response
     */
    #[Route('/employee/{id}/accounts', name: 'app_employee_detail_accounts')]
    public function showEmployeeAccounts(int $id, EmployeeRepository $repository): Response
    {
        $user = $repository->find($id);

        return $this->render('pages/detail-accounts.html.twig', [
            'user' => $user
        ]);
    }
}