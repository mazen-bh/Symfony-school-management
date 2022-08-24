<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Query\Expr\Base;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\NotifiableInterface;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @Notifiable(name="fos_user")
 */
class User extends BaseUser implements NotifiableInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     */
    protected $prenom;

    /**
     * @var int
     *
     * @ORM\Column(name="num_tel", type="integer", length=20, nullable=false)
     */
    protected $num_tel;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    protected $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="cin",type="string",length=11,nullable=false,unique=true)
     */
    protected $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=10, nullable=true)
     */
    protected $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="status",type="string",length=10,nullable=true)
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="date_naissance", type="string",length=11, nullable=true)
     */
    protected $date_naissance;

    /**
     * @var string
     *
     * @ORM\Column(name="demande", type="string", length=10,options={"default": "not"} , nullable=true)

     */
    protected $demande;


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
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * @param string $cin
     */
    public function setCin($cin)
    {
        $this->cin = $cin;
    }

    /**
     * @return string
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }

    /**
     * @param string $date_naissance
     */
    public function setDateNaissance($date_naissance)
    {
        $this->date_naissance = $date_naissance;
    }

    /**
     * @return string
     */
    public function getDemande()
    {
        return $this->demande;
    }

    /**
     * @param string $demande
     */
    public function setDemande($demande)
    {
        $this->demande = $demande;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return int
     */
    public function getNumTel()
    {
        return $this->num_tel;
    }

    /**
     * @param int $num_tel
     */
    public function setNumTel($num_tel)
    {
        $this->num_tel = $num_tel;
    }

    /**
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * @param string $sexe
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }




}

