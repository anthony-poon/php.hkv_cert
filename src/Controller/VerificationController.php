<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\DocData;

class VerificationController extends Controller {
    /**
     * @Route("/verification", name="verify_doc")
     */
    public function queryForm(Request $request) {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl("verify_by_doc_id"))
            ->add("cert_id", TextType::class, [
                "label" => "Enter the certification ID here: "
            ])
            ->add("submit", SubmitType::class, [
                "attr" => [
                    "class" => "btn-sm btn-primary"
                ]
            ])->getForm();
        return $this->render("verification_form.html.twig", ["form" => $form->createView()]);
    }
    /**
     * @Route("/verification/query", name="verify_by_doc_id")
     */
    public function verifyByDocId(Request $request) {
        $arr = [];
        $docId = $request->request->get("form")["cert_id"];
        if ($docId) {
            $repo = $this->getDoctrine()->getRepository(DocData::class);
            /* @var \App\Entity\DocData $docData */
            $docData = $repo->findOneBy(['docId' => $docId]);
            if ($docData) {
                $arr = [
                    "name" => $docData->getRecipientName(),
                    "course" => $docData->getCourseCode(),
                    "cert_no" => $docData->getDocId(),
                    "course_name" => $docData->getJsonData()["description"]
                ];
            }
        }
        return $this->render("verification_result.html.twig", $arr);
    }
}