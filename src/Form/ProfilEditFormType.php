<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfilEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status_score_email', CheckboxType::class, [
                'required' => false
            ])
            ->add('reminder_bet_email', CheckboxType::class, [
                'required' => false
            ])
            ->add('punchline_message', TextType::class, [
                'required' => false
            ]);
    }
}
