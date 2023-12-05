<?php

namespace App\Controller;

use App\Entity\Position;
use App\Form\AccountType;
use App\Form\EmployeeType;
use App\Form\Model\AccountTypeModel;
use App\Form\Model\EmployeeTypeModel;
use App\Repository\EmployeeRepository;
use App\Repository\PositionRepository;
use App\Service\Account\AccountManager;
use App\Service\Employee\EmployeeManager;
use App\Service\FileManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
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
    public function index(EmployeeRepository $employeeRepository, PositionRepository $positionRepository): Response
    {
        $newestEmployees = $employeeRepository->getNewestEmployees(5);
        $positions = $positionRepository->findAll();

        return $this->render('pages/index.html.twig', [
            'newest_employees' => $newestEmployees,
            'positions' => $positions,
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
     * @param Request $request
     * @param int $id ID of the employee
     * @param EmployeeRepository $repository
     * @param EmployeeManager $employeeManager
     * @param AccountManager $accountManager
     * @return Response
     */
    #[Route('/employee/{id}/accounts', name: 'app_employee_detail_accounts')]
    public function showEmployeeAccounts(Request $request, int $id, EmployeeRepository $repository, EmployeeManager $employeeManager, AccountManager $accountManager): Response
    {
        $user = $repository->find($id);

        $accountModel = new AccountTypeModel(null, null, null);

        $form = $this->createForm(AccountType::class, $accountModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filledAccountModel = $form->getData();
            $employee = $employeeManager->getEmployeeById($id);
            $accountManager->saveModelToDatabase($filledAccountModel, $employee, null);

            return $this->redirectToRoute('app_employee_detail_accounts', ['id' => $id]);
        }

        return $this->render('pages/detail-accounts.html.twig', [
            'user' => $user,
            'form' => $form
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
    public function delete(int $employeeId, Request $request, EmployeeManager $employeeManager): Response
    {
        $employee = $employeeManager->getEmployeeById($employeeId);

        $form = $this->createDeleteForm($employeeId);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $employeeManager->deleteEmployee($employeeId);
            return $this->redirectToRoute('app_employees_index');
        }

        return $this->render('pages/employee/employee-delete-form.html.twig', [
            'delete_form' => $form->createView(),
            'employee' => $employee
        ]);
    }

    public function createDeleteForm(int $employeeId): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_employee_delete', ['employeeId' => $employeeId]))
            ->add('confirm', SubmitType::class, ['label' => 'Ano'])
            ->getForm();
    }

    #[Route('/employee/search', name: 'app_employee_search')]
    public function search(Request $request, EmployeeRepository $repository, PositionRepository $positionRepository): Response
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
            'queryTerm' => $query,
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