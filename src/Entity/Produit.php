<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "produitreference")]
    #[Groups(['produit:read', 'produit:write'])]
    private ?int $produitreference = null;

    #[ORM\Column(name: "produitlibelle", length: 100)]
    #[Groups(['produit:read', 'produit:write'])]
    private ?string $produitlibelle = null;

    #[ORM\Column(name: "produitdescription", type: Types::TEXT, nullable: true)]
    #[Groups(['produit:read', 'produit:write'])]
    private ?string $produitdescription = null;
    
    #[ORM\ManyToOne(targetEntity: Unite::class, inversedBy: 'produits')]
    #[ORM\JoinColumn(name: "unitereference", referencedColumnName: "unitereference")]
    #[Groups(['produit:read', 'produit:write'])]
    private ?Unite $unitereference = null;

    public function getProduitreference(): ?int
    {
        return $this->produitreference;
    }

    public function getProduitlibelle(): ?string
    {
        return $this->produitlibelle;
    }

    public function setProduitlibelle(string $produitlibelle): static
    {
        $this->produitlibelle = $produitlibelle;

        return $this;
    }

    public function getProduitdescription(): ?string
    {
        return $this->produitdescription;
    }

    public function setProduitdescription(?string $produitdescription): static
    {
        $this->produitdescription = $produitdescription;

        return $this;
    }

    public function getUnitereference(): ?Unite
    {
        return $this->unitereference;
    }

    public function setUnitereference(?Unite $unitereference): static
    {
        $this->unitereference = $unitereference;

        return $this;
    }
}
