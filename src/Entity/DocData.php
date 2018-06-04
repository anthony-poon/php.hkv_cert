<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="doc_data")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class DocData {
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *      pattern="/^[\w_\-]+$/",
     *      message="Invalid Template Name"
     * )
     */
    private $templateName;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, unique=true, name="doc_id")
     * @Assert\NotBlank()
     */
    private $docId;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, name="recipient_name")
     * @Assert\NotBlank()
     */
    private $recipientName;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, name="recipient_email")
     * @Assert\Email()
     */
    private $recipientEmail;

    /**
     * @var string
     * @ORM\Column(type="string", length=64, name="course_code")
     * @Assert\NotBlank()
     */
    private $courseCode;

    /**
     * @ORM\Column(type="json")
     * @Assert\NotBlank()
     */
    private $jsonData;

    /**
     * @var \DateTime
     * @ORM\Column(name="create_date", type="datetime", nullable=true)
     */
    private $createDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="last_modified_date", type="datetime", nullable=true)
     */
    private $lastModifiedDate;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     * @return DocData
     */
    public function setId(int $id): DocData {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getDocId(): string {
        return $this->docId;
    }

    /**
     * @param string $docId
     * @return DocData
     */
    public function setDocId(string $docId): DocData {
        $this->docId = $docId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipientName(): string {
        return $this->recipientName;
    }

    /**
     * @param string $recipientName
     */
    public function setRecipientName(string $recipientName): void {
        $this->recipientName = $recipientName;
    }

    /**
     * @return string
     */
    public function getRecipientEmail(): string {
        return $this->recipientEmail;
    }

    /**
     * @param string $recipientEmail
     */
    public function setRecipientEmail(string $recipientEmail): void {
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * @return string
     */
    public function getCourseCode(): string {
        return $this->courseCode;
    }

    /**
     * @param string $courseCode
     */
    public function setCourseCode(string $courseCode): void {
        $this->courseCode = $courseCode;
    }

    /**
     * @return string
     */
    public function getTemplateName(): string {
        return $this->templateName;
    }

    /**
     * @param string $templateName
     * @return DocData
     */
    public function setTemplateName(string $templateName): DocData {
        $this->templateName = $templateName;
        return $this;
    }

    /**
     * @return array
     */
    public function getJsonData(): array {
        return $this->jsonData;
    }

    /**
     * @param array $jsonData
     * @return DocData
     */
    public function setJsonData(array $jsonData): DocData {
        $this->jsonData = $jsonData;
        return $this;
    }

    /**
     * Triggered on insert
     * @ORM\PrePersist
     */
    public function onPrePersist() {
        $this->createDate = new \DateTime("now");
        $this->lastModifiedDate = new \DateTime("now");
    }

    /**
     * Triggered on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate() {
        $this->lastModifiedDate = new \DateTime("now");
    }

    /**
     * @return \DateTime
     */
    public function getCreateDate(): \DateTime {
        return $this->createDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastModifiedDate(): \DateTime {
        return $this->lastModifiedDate;
    }
}