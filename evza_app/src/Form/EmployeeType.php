<?php

namespace App\Form;

use App\Form\Model\EmployeeTypeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Křestní jméno',
            ])
            ->add('secondName', TextType::class, [
                'label' => 'Příjmení',
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Telefonní číslo',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => false,
            ])
            ->add('note', TextType::class, [
                'label' => 'Poznámka',
                'required' => false,
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Je zaměstnanec aktivní?',
                'required' => false,
            ])
            ->add('profilePhoto', FileType::class, [
                'label' => 'Profilová fotografie',
                'mapped' => false,
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Uložit',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EmployeeTypeModel::class,
        ]);
    }
}