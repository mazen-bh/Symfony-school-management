<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\CommentaireRepository")
 */
class Commentaire
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_com", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCom;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_f", type="integer", nullable=false)
     */
    private $idF;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $idUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_com", type="date", nullable=false)
     */
    private $dateCom;

    /**
     * @var string
     *
     * @ORM\Column(name="description_com", type="string", length=255, nullable=false)
     */
    private $descriptionCom;

    /**
     * @return int
     */
    public function getIdCom()
    {
        return $this->idCom;
    }

    /**
     * @param int $idCom
     */
    public function setIdCom($idCom)
    {
        $this->idCom = $idCom;
    }

    /**
     * @return int
     */
    public function getIdF()
    {
        return $this->idF;
    }

    /**
     * @param int $idF
     */
    public function setIdF($idF)
    {
        $this->idF = $idF;
    }

    /**
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param int $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }

    /**
     * @return \DateTime
     */
    public function getDateCom()
    {
        return $this->dateCom;
    }

    /**
     * @param \DateTime $dateCom
     */
    public function setDateCom($dateCom)
    {
        $this->dateCom = $dateCom;
    }

    /**
     * @return string
     */
    public function getDescriptionCom()
    {
        return $this->descriptionCom;
    }

    /**
     * @param string $descriptionCom
     */
    public function setDescriptionCom($descriptionCom)
    {
        $this->descriptionCom = $descriptionCom;
    }




}

