<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\InscritRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: InscritRepository::class)]
#[UniqueEntity(fields: ['eleve', 'cours'], message: 'Cet élève est déjà inscrit à ce cours')]
class Inscrit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'inscrits')]
    private ?Eleve $eleve = null;

    #[ORM\ManyToOne(inversedBy: 'inscrits')]
    private ?Cours $cours = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    //fonction pour afficher le libelle des cours sur les pages twig    
    public function getCoursLibelle(): string
    {
    return $this->cours ? $this->cours->getLibelle() : '';
    }

    //fonctions pour afficher le nombre max d'élèves d'un cours sur les pages twig 
    public function getCoursMaxEleves(): string
    {
    return $this->cours ? $this->cours->getMaxEleves() : '';
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
 }   