<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Form\Model\EmployeeTypeModel;
use App\Repository\EmployeeRepository;
use App\Service\Employee\EmployeeManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function create(Request $request, EmployeeManager $employeeManager): Response
    {
        $employeeModel = new EmployeeTypeModel(null, null, null, null, null, new ArrayCollection(), null, null);

        $form = $this->createForm(EmployeeType::class, $employeeModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EmployeeTypeModel $filledEmployeeModel */
            $filledEmployeeModel = $form->getData();
            /** @var UploadedFile $profilePhotoFile */
            $profilePhotoFile = $form['profilePhoto']->getData();

            if ($profilePhotoFile) {
                $originalFilename = pathinfo($profilePhotoFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $filledEmployeeModel->profilePhotoFilename = $safeFilename.'-'.uniqid().'.'.$profilePhotoFile->guessExtension();

                try {
                    $profilePhotoFile->move(
                        $this->getParameter('profile_photo_directory'),
                        $filledEmployeeModel->profilePhotoFilename
                    );
                } catch (FileException $e) {
                    $filledEmployeeModel->profilePhotoFilename = null;
                }
            }

            $newEmployee = $employeeManager->saveModelToDatabase($filledEmployeeModel, null);

            return $this->redirectToRoute('app_employee_detail', ['id' => $newEmployee->id]);
        }
        return $this->render('pages/employee/employee-form.html.twig', [
            'form' => $form,
            'heading' => 'Vytvořit nového zaměstance'
        ]);
    }

    #[Route('/employee/{id}/edit', name: 'app_employee_edit')]
    public function edit(int $employeeId): Response
    {

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