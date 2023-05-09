<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EleveRepository::class)]
class Eleve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_eleve = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_eleve = null;

    #[ORM\Column(length: 255)]
    private ?string $email_eleve = null;

    #[ORM\Column]
    private ?string $telephone_eleve = null;

    #[ORM\Column(length: 255)]
    private ?string $sexe_eleve = null;

    #[ORM\Column(length: 255)]
    private ?string $niveau_etude = null;

    #[ORM\Column(length: 255)]
    private ?string $metier_exerce = null;

    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: Presence::class)]
    private Collection $presences;

    #[ORM\OneToMany(mappedBy: 'eleve', targetEntity: Inscrit::class)]
    private Collection $inscrits;

    public function __construct()
    {
        $this->presences = new ArrayCollection();
        $this->inscrits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEleve(): ?string
    {
        return $this->nom_eleve;
    }

    public function setNomEleve(string $nom_eleve): self
    {
        $this->nom_eleve = $nom_eleve;

        return $this;
    }

    public function getPrenomEleve(): ?string
    {
        return $this->prenom_eleve;
    }

    public function setPrenomEleve(string $prenom_eleve): self
    {
        $this->prenom_eleve = $prenom_eleve;

        return $this;
    }

    //Fonction pour récuppérer le nom complet, utile pour les formulaires
    public function getNomComplet(): ?string
    {
        return $this->prenom_eleve . ' ' . $this->nom_eleve;
    }

    public function getEmailEleve(): ?string
    {
        return $this->email_eleve;
    }

    public function setEmailEleve(string $email_eleve): self
    {
        $this->email_eleve = $email_eleve;

        return $this;
    }

    public function getTelephoneEleve(): ?int
    {
        return $this->telephone_eleve;
    }

    public function setTelephoneEleve(int $telephone_eleve): self
    {
        $this->telephone_eleve = $telephone_eleve;

        return $this;
    }

    public function getSexeEleve(): ?string
    {
        return $this->sexe_eleve;
    }

    public function setSexeEleve(string $sexe_eleve): self
    {
        $this->sexe_eleve = $sexe_eleve;

        return $this;
    }

    public function getNiveauEtude(): ?string
    {
        return $this->niveau_etude;
    }

    public function setNiveauEtude(string $niveau_etude): self
    {
        $this->niveau_etude = $niveau_etude;

        return $this;
    }

    public function getMetierExerce(): ?string
    {
        return $this->metier_exerce;
    }

    public function setMetierExerce(string $metier_exerce): self
    {
        $this->metier_exerce = $metier_exerce;

        return $this;
    }

    /**
     * @return Collection<int, Presence>
     */
    public function getPresences(): Collection
    {
        return $this->presences;
    }

    public function addPresence(Presence $presence): self
    {
        if (!$this->presences->contains($presence)) {
            $this->presences->add($presence);
            $presence->setEleve($this);
        }

        return $this;
    }

    public function removePresence(Presence $presence): self
    {
        if ($this->presences->removeElement($presence)) {
            // set the owning side to null (unless already changed)
            if ($presence->getEleve() === $this) {
                $presence->setEleve(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Inscrit>
     */
    public function getInscrits(): Collection
    {
        return $this->inscrits;
    }

    public function addInscrit(Inscrit $inscrit): self
    {
        if (!$this->inscrits->contains($inscrit)) {
            $this->inscrits->add($inscrit);
            $inscrit->setEleve($this);
        }

        return $this;
    }

    public function removeInscrit(Inscrit $inscrit): self
    {
        if ($this->inscrits->removeElement($inscrit)) {
            // set the owning side to null (unless already changed)
            if ($inscrit->getEleve() === $this) {
                $inscrit->setEleve(null);
            }
        }

        return $this;
    }
}
