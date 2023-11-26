<?php

namespace App\Controller;

use App\Form\EmployeeType;
use App\Form\Model\EmployeeTypeModel;
use App\Repository\EmployeeRepository;
use App\Service\Employee\EmployeeManager;
use App\Service\FileManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/employee/{id}/accounts', name: 'app_employee_detail_accounts')]
    public function showEmployeeAccounts(int $id, EmployeeRepository $repository): Response
    {
        $user = $repository->find($id);

        return $this->render('pages/detail-accounts.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/employee/create', name: 'app_employee_create')]
    public function create(Request $request, EmployeeManager $employeeManager, FileManager $fileManager): Response
    {
        $employeeModel = new EmployeeTypeModel(null, null, null, null, null, new ArrayCollection(), null, null);

        $form = $this->createForm(EmployeeType::class, $employeeModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EmployeeTypeModel $filledEmployeeModel */
            $filledEmployeeModel = $form->getData();
            /** @var UploadedFile $profilePhotoFile */
            $profilePhotoFile = $form['profilePhoto']->getData();

            if($profilePhotoFile != null) {
                $filledEmployeeModel->profilePhotoFilename = $fileManager->uploadImage($profilePhotoFile);
            }
            $newEmployee = $employeeManager->saveModelToDatabase($filledEmployeeModel, null);

            return $this->redirectToRoute('app_employee_detail', ['id' => $newEmployee->id]);
        }
        return $this->render('pages/employee/employee-form.html.twig', [
            'form' => $form,
            'heading' => 'Vytvořit nového zaměstance'
        ]);
    }

    #[Route('/employee/{employeeId}/edit', name: 'app_employee_edit')]
    public function edit(int $employeeId, Request $request, EmployeeManager $employeeManager, FileManager $fileManager): Response
    {
        $employeeModel = $employeeManager->getModelById($employeeId);
        $form = $this->createForm(EmployeeType::class, $employeeModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EmployeeTypeModel $filledEmployeeModel */
            $filledEmployeeModel = $form->getData();
            /** @var UploadedFile $profilePhotoFile */
            $profilePhotoFile = $form['profilePhoto']->getData();

            if($profilePhotoFile != null) {
                $filledEmployeeModel->profilePhotoFilename = $fileManager->uploadImage($profilePhotoFile);
            }
            $editedEmployee = $employeeManager->saveModelToDatabase($filledEmployeeModel, $employeeId);

            return $this->redirectToRoute('app_employee_detail', ['id' => $editedEmployee->id]);
        }

        return $this->render('pages/employee/employee-form.html.twig', [
            'form' => $form,
            'heading' => 'Editovat zaměstance'
        ]);
    }

    #[Route('/employee/{employeeId}/delete', name: 'app_employee_delete')]
    public function delete(int $employeeId, EmployeeManager $employeeManager): Response
    {
        $employeeManager->deleteEmployee($employeeId);
        return $this->redirectToRoute('app_employees_index');
    }

    #[Route('/employee/search', name: 'app_employee_search')]
    public function search(Request $request, EmployeeRepository $repository): Response
    {
        $query = $request->query->get('query', '');

        if(!empty($query)) {
            $employees = $repository->findBySearchQuery($query);
        }
        else {
            $employees = [];
        }

        return $this->render('pages/employee-search.html.twig', [
            'employees' => $employees,
            'queryTerm' => $query
        ]);
    }

    /**
     * @param int $id ID of the employee
     * @param EmployeeRepository $repository
     * @return Response
     */
    #[Route('/employee/{id}', name: 'app_employee_detail')]
    public function showEmployeeDetail(int $id, EmployeeRepository $repository): Response
    {
        $user = $repository->find($id);

        return $this->render('pages/detail.html.twig', [
            'user' => $user
        ]);
    }
}