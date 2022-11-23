<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status_score_email', CheckboxType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous ne pouvez pas envoyer un résultat vide',
                    ])
                ],
            ])
            ->add('reminder_bet_email', CheckboxType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous ne pouvez pas envoyer un résultat vide',
                    ])
                ],
            ])
            ->add('submit', SubmitType::class, []);
    }
}
