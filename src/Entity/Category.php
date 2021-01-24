<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $countryOrigin;

    /**
     * @ORM\Column(type="string",columnDefinition="ENUM('small', 'medium', 'large')")
     */
    private $size;

    /**
     * @ORM\Column(type="string",columnDefinition="ENUM('low', 'medium', 'high')")
     */
    private $popularity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $specialNotes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $att1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $att2;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="categ")
     */
    private $products;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getManager(): ?User
    // {
    //     return $this->manager;
    // }

    // public function setManager(?User $manager): self
    // {
    //     $this->manager = $manager;

    //     return $this;
    // }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCountryOrigin(): ?string
    {
        return $this->countryOrigin;
    }

    public function setCountryOrigin(string $countryOrigin): self
    {
        $this->countryOrigin = $countryOrigin;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getPopularity(): ?string
    {
        return $this->popularity;
    }

    public function setPopularity(string $popularity): self
    {
        $this->popularity = $popularity;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getSpecialNotes(): ?string
    {
        return $this->specialNotes;
    }

    public function setSpecialNotes(string $specialNotes): self
    {
        $this->specialNotes = $specialNotes;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getAtt1(): ?string
    {
        return $this->att1;
    }

    public function setAtt1(?string $att1): self
    {
        $this->att1 = $att1;

        return $this;
    }

    public function getAtt2(): ?string
    {
        return $this->att2;
    }

    public function setAtt2(?string $att2): self
    {
        $this->att2 = $att2;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCateg($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCateg() === $this) {
                $product->setCateg(null);
            }
        }

        return $this;
    }




    
    
    public function __toString() {
        return $this->name;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function toArray()
    {
    return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'description' => $this->getDescription(),
        'countryorigin' => $this->getCountryOrigin(),
        'specialnotes'=> $this->getSpecialNotes(),
        'size'=>$this->getSize(),
        'popularity'=>$this->getPopularity(),
        'language'=>$this->getLanguage(),
        'status'=>$this->getStatus(),
        'products'=>$this->getProducts(),
        'att1' => $this->getAtt1(),
        'att2' => $this->getAtt2(),

    ]; 
   }
}
