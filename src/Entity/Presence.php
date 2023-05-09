<?php

namespace App\Entity;

use App\Repository\PresenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresenceRepository::class)]
class Presence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[ORM\Column]
    private ?int $validation_presence = null;

    #[ORM\ManyToOne(inversedBy: 'presences')]
    private ?Eleve $eleve = null;
   
    #[ORM\ManyToOne(inversedBy: 'presences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Seances $seances = null;

    public function getId(): ?int
    {
        return $this->id;
    }
   
    public function getValidationPresence(): ?int
    {
        return $this->validation_presence;
    }

    public function setValidationPresence(int $validation_presence): self
    {
        $this->validation_presence = $validation_presence;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }
   
    public function getSeances(): ?Seances
    {
        return $this->seances;
    }

    public function setSeances(?Seances $seances): self
    {
        $this->seances = $seances;

        return $this;
    }

    //fonctions pour afficher les noms et prénoms des élèves inscrit aux cours sur les pages twig 
    public function getCoursNomEleve(): string
    {
    return $this->eleve ? $this->eleve->getNomEleve() : '';
    }

    public function getCoursPrenomEleve(): string
    {
    return $this->eleve ? $this->eleve->getPrenomEleve() : '';
    } 

    //Fonction pour chercher la date de la séance
    public function getDateSeance(): ?\DateTimeInterface
    {
        return $this->seances ? $this->seances->getDate() : '';
    }
}
