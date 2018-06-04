<?php
/**
 * Created by PhpStorm.
 * User: ypoon
 * Date: 18/5/2018
 * Time: 6:10 PM
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils) {
        $form = $this->createFormBuilder()
            ->add("username", TextType::class, array(
                "attr" => array(
                    "name" => "_username"
                )
            ))
            ->add("password", PasswordType::class)
            ->add("submit", SubmitType::class)
            ->getForm();
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', array(
            "form" => $form->createView(),
            "last_username" => $lastUsername,
            "error" => $error,
        ));
    }
}