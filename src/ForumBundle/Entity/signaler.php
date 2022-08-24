<?php
namespace ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * signaler
 *
 * @ORM\Table(name="signaler")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\signalerRepository")
 */
class signaler
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $idu;
    /**
     * @ORM\ManyToOne(targetEntity="ForumBundle\Entity\Sujet")
     * @ORM\JoinColumn(name="idsujet", referencedColumnName="id_f")
     */
    private $idsujet;
    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255)
     */
    private $reason;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set reason
     *
     * @param string $reason
     *
     * @return signaler
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }
    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }
    /**
     * @return mixed
     */
    public function getIdu()
    {
        return $this->idu;
    }
    /**
     * @param mixed $idu
     */
    public function setIdu($idu)
    {
        $this->idu = $idu;
    }
    /**
     * @return mixed
     */
    public function getIdsujet()
    {
        return $this->idsujet;
    }
    /**
     * @param mixed $idsujet
     */
    public function setIdsujet($idsujet)
    {
        $this->idsujet = $idsujet;
    }
}