<?php
namespace App\Controller;

use App\Entity\DocData;
use App\Form\EditDocDataForm;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DocDataController extends Controller {


    /**
     * @Route("/doc-data", name="list_doc_data")
     */
    public function listDocData() {
        $repo = $this->getDoctrine()->getRepository(DocData::class);
        $docEntry = $repo->findAll();
        return $this->render("document_data/list_doc_data.html.twig", array("doc_entry" => $docEntry));
    }

    /**
     * @Route("/doc-data/{id}", name="edit_doc_data", requirements={"id"="^\d+"})
     */
    public function editDocData(int $id) {
        $repo = $this->getDoctrine()->getRepository(DocData::class);
        $docData = $repo->find($id);
        $form = $this->createForm(EditDocDataForm::class, $docData);
        return $this->render("document_data/edit_doc_data.html.twig", array(
            "doc_data" => $docData,
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/doc-data/delete/{id}", name="delete_doc_data", requirements={"id"="^\d+"})
     */
    public function deleteDocData(int $id) {
        $repo = $this->getDoctrine()->getRepository(DocData::class);
        $docData = $repo->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($docData);
        $entityManager->flush();
        return $this->redirectToRoute("list_doc_data");
    }

    /**
     * @Route("/doc-data/pdf/{id}", name="view_doc_pdf", requirements={"id"="^\d+"})
     */
    public function generatePdf(int $id) {
        $repo = $this->getDoctrine()->getRepository(DocData::class);
        /* @var DocData $docData */
        $docData = $repo->find($id);
        $templateName = strtolower($docData->getTemplateName());
        $html = $this->renderView("document/preset/$templateName.html.twig", array(
            "doc_data" => $docData->getJsonData(),
        ));
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    /**
     * @Route("/doc-data/pdf", name="download_all_pdf")
     */
    public function downloadAll() {
        $repo = $this->getDoctrine()->getRepository(DocData::class);
        /* @var DocData $docData */
        $docDataArr = $repo->findAll();
        $tmpName = tempnam(sys_get_temp_dir(), "ZIP_");
        $zip = new \ZipArchive();
        $zip->open($tmpName, \ZipArchive::CREATE);
        foreach ($docDataArr as $docData) {
            $templateName = strtolower($docData->getTemplateName());
            $html = $this->renderView("document/preset/$templateName.html.twig", array(
                "doc_data" => $docData->getJsonData(),
            ));
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            $str = $mpdf->Output(null, \Mpdf\Output\Destination::STRING_RETURN);
            $fileName = $docData->getDocId();
            $fileName = preg_replace("/[^\w_]+/", "_", $fileName).".pdf";
            $zip->addFromString($fileName, $str);
        }
        $zip->close();
        $fileStr = file_get_contents($tmpName);
        $response = new Response($fileStr);
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'certificate.zip'
        );
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    /**
     * @Route("/doc-data/upload", name="upload_doc_data")
     */
    public function uploadData(Request $request) {
        $form = $this->createFormBuilder()
            ->add("uploaded_file", FileType::class, ["label" => "Data Set:"])
            ->add("submit", SubmitType::class, [
                "attr" => ["class" => "btn-primary btn-sm"]
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /* @var \Symfony\Component\HttpFoundation\File\File $tmpFile */
            // Move the uploaded file to temp dir and read by PhpSpreadSheet
            $tmpFile = $form->getData()["uploaded_file"]->move(sys_get_temp_dir());
            $reader = IOFactory::createReader("Xlsx");
            $reader->setReadDataOnly(true);
            $excel = $reader->load($tmpFile);
            $sheet = $excel->getSheet(0);
            $header = [];
            $data = [];

            // Load data into array
            foreach ($sheet->getRowIterator() as $row) {
                if ($row->getRowIndex() == 1) {
                    foreach ($row->getCellIterator() as $cell) {
                        // Lower case and underscore
                        // TODO: regex whitelist \w
                        $value = str_replace(" ", "_", strtolower(trim($cell->getValue())));
                        $header[] = $value;
                    }
                } else {
                    $headerOffset = 0;
                    $tmpArr = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $value = $cell->getFormattedValue();
                        if (!empty($value)) {
                            $tmpArr[$header[$headerOffset]] = $value;
                        }
                        $headerOffset++;
                    }
                    if (!empty($tmpArr)) {
                        $data[] = $tmpArr;
                    }
                }
            }
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($data as $d) {
                $docEntry = new DocData();
                $docEntry->setJsonData($d);
                $docEntry->setTemplateName($d["templateno"]);
                $docEntry->setDocId($d["certno"]);
                $docEntry->setRecipientEmail($d["email"]);
                $name = "";
                if (!empty($d["prefix"])) {
                    $name .= $d["prefix"]." ";
                }
                if (!empty($d["first_name"])) {
                    $name .= $d["first_name"]." ";
                }
                if (!empty($d["last_name"])) {
                    $name .= $d["last_name"];
                }
                $name = trim($name);
                $docEntry->setRecipientName($name);
                $docEntry->setRecipientEmail($d["email"]);
                $docEntry->setCourseCode($d["coursecode"]);
                $entityManager->persist($docEntry);
            }
            $entityManager->flush();
            return $this->redirectToRoute("list_doc_data");
        }
        return $this->render("document_data/upload_doc_data.html.twig", ["form" => $form->createView()]);
    }
}