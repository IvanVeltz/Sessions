<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Trainee;
use App\Entity\Trainer;
use App\Entity\Training;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                "label" => "Nom",
                "attr" => [
                    'class' => 'form-control',
                ]
            ])
            ->add('dateStart', DateType::class, [
                "label" => "Date de début",
                'widget' => 'single_text',
                "attr" => [
                    'class' => 'form-control',
                ]
            ])
            ->add('dateEnd', DateType::class, [
                "label" => "Date de fin",
                'widget' => 'single_text',
                "attr" => [
                    'class' => 'form-control',
                ]
            ])
            ->add('nbrPlaces', IntegerType::class,[
                "label" => "Nombres de places",
                "attr" => [
                    'class' => 'form-control',
                ]
            ])
            ->add('trainer', EntityType::class, [
                'class' => Trainer::class,
                'label' => "Formateur : ",
                'choice_label' => function ($trainer) {
                    return $trainer->getFirstName() . ' ' . $trainer->getLastName();
                },
            ])
            ->add('training', EntityType::class, [
                'class' => Training::class,
                "label" => "Formation :",
                'choice_label' => 'name',
            ])
            ->add('valider', SubmitType::class, [
                "attr" => [
                    'class' => 'btn btn-success',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
