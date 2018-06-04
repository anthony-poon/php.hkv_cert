<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\CreateUserForm;
use App\Form\EditUserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends Controller {
    /**
     * @Route("/admin", name="admin_home")
     */
    public function admin() {
        return new Response('<html><body>Admin page!</body></html>');
    }

    /**
     * @Route("/admin/users/new", name="new_users")
     */
    public function createUsers(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();
        $user = new User();
        $form = $this->createForm(CreateUserForm::class, $user)->add('submit', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin_home');
        }
        return $this->render('admin/create_users.html.twig', array(
            'form' => $form->createView(),
            'users' => $users
        ));
    }

    /**
     * @Route("/admin/users/{id}", name="edit_users", requirements={"id"="\d+"}, defaults={"id"=0})
     */
    public function editUsers(Request $request, UserPasswordEncoderInterface $passwordEncoder, int $id) {
        /* @var \App\Entity\User $user */
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();
        $param = array(
            "users" => $users
        );
        if ($id) {
            $user = $repo->find($id);
            $form = $this->createForm(EditUserForm::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($user->getPlainPassword()) {
                    $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);
                }
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute("edit_users", array(
                    "id" => $id
                ));
            } else {
                $param["form"] = $form->createView();
            }

        }
        return $this->render('admin/edit_users.html.twig', $param);
    }
}