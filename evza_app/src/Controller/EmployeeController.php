<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('pages/index.html.twig', []);
    }

    #[Route('/users', name: 'app_users_index')]
    public function listUsers(): Response
    {
        // get all Employees from EmployeeRepository
        return $this->render('pages/overview.html.twig', []);
    }

    /**
     * @param int $id ID of the user
     * @return Response
     */
    #[Route('/user/{id}', name: 'app_user_detail')]
    public function showUserDetail(int $id): Response
    {
        // get Employee by ID
        if($id == 1) {
            return $this->render('pages/detail.html.twig', []);
        }
        else if($id == 2) {
            return $this->render('pages/detail.html.twig', []);
        }
        else {
            return $this->render('pages/error.html.twig', []);
        }
    }

    /**
     * @param int $id ID of the user
     * @return Response
     */
    #[Route('/user/{id}/accounts', name: 'app_user_detail_accounts')]
    public function showUserAccounts(int $id): Response
    {
        // get Accounts for user (and his ID)
        return $this->render('pages/detail-accounts.html.twig', []);
    }
}