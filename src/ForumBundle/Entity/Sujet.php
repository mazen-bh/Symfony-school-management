<?php

namespace ForumBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\NotifiableInterface;

/**
 * Sujet
 *
 * @ORM\Table(name="sujet")
 * @ORM\Entity(repositoryClass="ForumBundle\Repository\SujetRepository")
 */
class Sujet
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_f", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idF;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     */
    private $idUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="Etat", type="integer", nullable=false)
     */
    private $etat;

    /**
     * @var integer
     *
     * @ORM\Column(name="strike", type="integer", options={"default": "0"}, nullable=false)
     */
    private $strike;

    /**
     * @var string
     *
     * @ORM\Column(name="description_f", type="string", length=255, nullable=false)
     */
    private $descriptionF;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbre_jaime", type="integer", nullable=true)
     */
    private $nbreJaime;

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
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    }



    /**
     * @return int
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param int $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return string
     */
    public function getDescriptionF()
    {
        return $this->descriptionF;
    }

    /**
     * @param string $descriptionF
     */
    public function setDescriptionF($descriptionF)
    {
        $this->descriptionF = $descriptionF;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getNbreJaime()
    {
        return $this->nbreJaime;
    }

    /**
     * @param int $nbreJaime
     */
    public function setNbreJaime($nbreJaime)
    {
        $this->nbreJaime = $nbreJaime;
    }

    /**
     * @return int
     */
    public function getStrike()
    {
        return $this->strike;
    }

    /**
     * @param int $strike
     */
    public function setStrike($strike)
    {
        $this->strike = $strike;
    }






}