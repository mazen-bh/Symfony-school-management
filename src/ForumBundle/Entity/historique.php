<?php
namespace ForumBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * historique
 *
 * @ORM\Table(name="historique")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\historiqueRepository")
 */
class historique
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
     * @ORM\JoinColumn(name="idu", referencedColumnName="id")
     */
    private $idu;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
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
     * Set description
     *
     * @param string $description
     *
     * @return historique
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}