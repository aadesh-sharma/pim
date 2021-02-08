<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $post_thumbnail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="text")
     */
    private $longDescription;

    /**
     * @ORM\Column(type="float")
     */
    private $height;

    /**
     * @ORM\Column(type="float")
     */
    private $width;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $color;

    /**
     * @ORM\Column(type="string",columnDefinition="ENUM('draft', 'reviewed', 'published')")
     */
    private $status;

    /**
     * @ORM\Column(type="string",length=255)
     */
    private $brand;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $quality;

    /**
     * @ORM\Column(type="float")
     */
    private $tax;

    /**
     * @ORM\Column(type="float")
     */
    private $deliveryCharges;

    /**
     * @ORM\Column(type="float")
     */
    private $discount;

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
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getCateg(): ?Category
    // {
    //     return $this->categ;
    // }

    // public function setCateg(?Category $categ): self
    // {   
    //      $this->categ = $categ;
         
        

    //     return $this;
    // }

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

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): self
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuality(): ?string
    {
        return $this->quality;
    }

    public function setQuality(string $quality): self
    {
        $this->quality = $quality;

        return $this;
    }

    public function getTax(): ?float
    {
        return $this->tax;
    }

    public function setTax(float $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getDeliveryCharges(): ?float
    {
        return $this->deliveryCharges;
    }

    public function setDeliveryCharges(float $deliveryCharges): self
    {
        $this->deliveryCharges = $deliveryCharges;

        return $this;
    }

    // public function getDicount(): ?int
    // {
    //     return $this->dicount;
    // }

    // public function setDicount(int $dicount): self
    // {
    //     $this->dicount = $dicount;

    //     return $this;
    // }

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function getPostThumbnail(): ?string
    {
        return $this->post_thumbnail;
    }

    public function setPostThumbnail(?string $post_thumbnail): self
    {
        $this->post_thumbnail = $post_thumbnail;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setStatusValue () {
        $this->status = 'draft';
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue () {
        $this->created = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function setUpdatedValue () {
        $this->updated = new \DateTime();
    }

    public function __toString() {
        return $this->name;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
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


    public function toArray()
    {
    return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'shortdescription' => $this->getShortDescription(),
        'longdescription' => $this->getLongDescription(),
        'height' => $this->getHeight(),
        'width'=> $this->getWidth(),
        'color'=>$this->getColor(),
        'status'=>$this->getStatus(),
        'brand'=>$this->getBrand(),
        'price'=>$this->getPrice(),
        'quality'=>$this->getQuality(),
        'tax' => $this->getTax(),
        'deliverycharges' => $this->getDeliveryCharges(),
        'discount' => $this->getDiscount(),
        'created' => $this->getCreated(),
        'updated' => $this->getUpdated(),
        'image'=> $this->getImage(),
        'thumbnail' => $this->getPostThumbnail(),
        'category'=> $this->getCategory(),
        'user' => $this->getUser()


    ]; 
   }
}
