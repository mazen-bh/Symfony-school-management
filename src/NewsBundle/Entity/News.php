<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * News
 *
 * @ORM\Table(name="News")
 * @ORM\Entity
 */
class News
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id_Article", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idArticle;

    /**
     * @var integer
     *
     * @ORM\Column(name="Id_User", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom_Article", type="string", length=255, nullable=false)
     */
    private $nomArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="Contenu_Article", type="string", length=255, nullable=false)
     */
    private $contenuArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="Image_Article", type="string", length=255, nullable=false)
     */
    private $imageArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="Titre_Event", type="string", length=255, nullable=false)
     */
    private $titreEvent;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbrevue", type="integer", nullable=false)
     */
    private $nbrevue;

    /**
     * @var string
     *
     * @ORM\Column(name="Date_Article", type="string", length=255, nullable=false)
     */
    private $dateArticle;

    /**
     * @return int
     */
    public function getIdArticle()
    {
        return $this->idArticle;
    }

    /**
     * @param int $idArticle
     */
    public function setIdArticle($idArticle)
    {
        $this->idArticle = $idArticle;
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
     * @return string
     */
    public function getNomArticle()
    {
        return $this->nomArticle;
    }

    /**
     * @param string $nomArticle
     */
    public function setNomArticle($nomArticle)
    {
        $this->nomArticle = $nomArticle;
    }

    /**
     * @return string
     */
    public function getContenuArticle()
    {
        return $this->contenuArticle;
    }

    /**
     * @param string $contenuArticle
     */
    public function setContenuArticle($contenuArticle)
    {
        $this->contenuArticle = $contenuArticle;
    }

    /**
     * @return string
     */
    public function getImageArticle()
    {
        return $this->imageArticle;
    }

    /**
     * @param string $imageArticle
     */
    public function setImageArticle($imageArticle)
    {
        $this->imageArticle = $imageArticle;
    }

    /**
     * @return string
     */
    public function getTitreEvent()
    {
        return $this->titreEvent;
    }

    /**
     * @param string $titreEvent
     */
    public function setTitreEvent($titreEvent)
    {
        $this->titreEvent = $titreEvent;
    }

    /**
     * @return int
     */
    public function getNbrevue()
    {
        return $this->nbrevue;
    }

    /**
     * @param int $nbrevue
     */
    public function setNbrevue($nbrevue)
    {
        $this->nbrevue = $nbrevue;
    }

    /**
     * @return string
     */
    public function getDateArticle()
    {
        return $this->dateArticle;
    }

    /**
     * @param string $dateArticle
     */
    public function setDateArticle($dateArticle)
    {
        $this->dateArticle = $dateArticle;
    }




}

