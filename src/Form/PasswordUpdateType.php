<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword',PasswordType::class,$this->getConfiguration("Ancien mot de passe","Tapez votre mot de passe actuel"))
            ->add('NewPassword',PasswordType::class,$this->getConfiguration("Nouveau mot de passe","Tapez votre nouveau mot de passe"))
            ->add('ConfirmPassword',PasswordType::class,$this->getConfiguration("Confirmez du nouveau mot de passe","Retapez votre nouveau mot de passe"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
