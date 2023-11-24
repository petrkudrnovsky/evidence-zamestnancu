<?php

namespace App\Form;

use App\Form\Model\AccountTypeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Jméno účtu',
            ])
            ->add('isPermanent', CheckboxType::class, [
                'required' => false,
                'label' => 'Jedná se o permanentní účet?',
            ])
            ->add('expiration', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Expirace dočasného účtu',
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
            'data_class' => AccountTypeModel::class,
        ]);
    }
}