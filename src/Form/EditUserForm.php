<?php
namespace App\Form;

use App\Entity\User;
use App\Entity\UserRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class EditUserForm extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class, array(
                "disabled" => True
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'required' => false,
                    // To bypass non-blank assertion for plainPassword, do not affect hashed password
                    'empty_data' => 'placeholder',
                ),
                'required' => false,
                'invalid_message' => "Repeat password do not match.",
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('userRoles', EntityType::class, array(
                'class' => UserRole::class,
                'choice_label' => 'description',
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('submit', SubmitType::class, array(
                "attr" => array(
                    "class" => "btn-primary"
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }
}