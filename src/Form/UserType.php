<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'USER' => 'ROLE_USER',
                    'ADMIN' => 'ROLE_ADMIN',
                    'SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            // ->add('plainPassword', PasswordType::class, [
            //     'mapped' => false,
            // ])
            ->add('firstname')
            ->add('lastname')
        ;

        // On va se brancher à un évènement PRE_SET_DATA
        // Pour afficher le champ password en fonction du contexte
        // dans lequel on se trouve :
        // - à la création : on affiche le champ
        // - à l'édition : on affiche pas le champ

        // $button.addEventListener('click', app.handleClick)
        // handleClick(event)

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            // ON récupère les données de l'utilisateur que l'on s'apprete
            // à créer ou à éditer
            $user = $event->getData();
            $form = $event->getForm();
            dump($user);
            
            // Si on est dans le cas d'une création de compte utilisateur
            // Alors on ajoute le champs password
            if ($user->getId() === null) {
                $form->add('plainPassword', PasswordType::class, [

                    // On indique à Symfony que la propriété 'plainPassword'
                    // n'est pas liée (mapped) à l'entité User
                    'mapped' => false
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
