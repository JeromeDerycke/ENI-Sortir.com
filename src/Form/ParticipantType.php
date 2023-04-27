<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, ['label'=>'Pseudo'])
            ->add('prenom', TextType::class,['label'=>'Prénom', 'required'=>true])
            ->add('nom', TextType::class, ['label'=>'Nom', 'required'=>true])
            ->add('telephone', TelType::class,['label'=>'Téléphone (0X XX XX XX XX)', 'required'=>true])
            ->add('email', EmailType::class, ['required'=>true])
            ->add('motdepasse',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les passwords doivent être identiques',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'mapped' => false,
                'first_options'  => ['label' => 'Password'],
                'second_options' => ['label' => 'Répéter le password'],])
            ->add('campus', EntityType::class, [
                'class'=>'App\Entity\Campus',
                'choice_label'=>'nom',
                'multiple'=>false])
            ->add('photoFileName', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(['maxSize' => '10240k'])
                ],
                ])
            ->add('submit', SubmitType::class, ['label'=>'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
