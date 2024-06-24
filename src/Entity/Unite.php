<?php

namespace App\Entity;

use App\Repository\UniteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniteRepository::class)]
class Unite
{
    #[ORM\Id]
    #[ORM\Column(name: "unitereference", type: "integer")]
    private ?int $unitereference = null;

    #[ORM\Column(name: "unitelibelle", length: 30)]
    private ?string $unitelibelle = null;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'unitereference', orphanRemoval: true)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getUnitereference(): ?int
    {
        return $this->unitereference;
    }

    public function setUnitereference(int $unitereference): static
    {
        $this->unitereference = $unitereference;

        return $this;
    }

    public function getUnitelibelle(): ?string
    {
        return $this->unitelibelle;
    }

    public function setUnitelibelle(string $unitelibelle): static
    {
        $this->unitelibelle = $unitelibelle;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setUnitereference($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUnitereference() === $this) {
                $produit->setUnitereference(null);
            }
        }

        return $this;
    }
}
