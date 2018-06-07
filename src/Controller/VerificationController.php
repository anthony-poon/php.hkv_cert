<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\DocData;

class VerificationController extends Controller {
    /**
     * @Route("/verification", name="verify_doc_id")
     */
    public function verifyByDocId(Request $request) {
        $arr = null;
        $docId = $request->query->get("id");
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
        return $this->render("verification.html.twig", $arr);
    }
}