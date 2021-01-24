<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

     
    private $manager;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager )
    {
        parent::__construct($registry, Product::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function saveProduct(
              $user,
              $category,
              $name ,
              $shortdescription,
              $longdescription ,
              $height,
              $width ,
              $color ,
              $status,
              $brand ,
              $price,
              $quality,
              $tax ,
              $deliverycharges,
              $discount,
              $image ,
             $thumbnail
            )
    { 
        $newproduct= new Product();
        $newproduct
            ->setName($name)
            ->setShortDescription($shortdescription)
            ->setLongDescription($longdescription)
            ->setHeight($height)
            ->setWidth($width)
            ->setColor($color)
            ->setStatus($status)
            ->setBrand($brand)
            ->setPrice($price)
            ->setQuality($quality)
            ->setTax($tax)
            ->setDeliveryCharges($deliverycharges)
            ->setDiscount($discount)
            ->setImage($image)
            ->setPostThumbnail($thumbnail)
            ->setCategory($category)
            ->setUser($user)
            ->setCreated(new \DateTime())
            ->setUpdated(new \DateTime());



            $this->manager->persist($newproduct);
            $this->manager->flush();
    }

   public function updateProduct(Product $product, $data,$user,$category)
   {
        $product->setName($data['name']);
        $product->setShortDescription($data['shortdescription']);
        $product->setLongDescription($data['longdescription']);
        $product->setHeight($data['height']);
        $product->setWidth($data['width']);
        $product->setColor($data['color']);
        $product->setStatus("draft");
        $product->setBrand($data['brand']);
        $product->setPrice($data['price']);
        $product->setQuality($data['quality']);
        $product->setTax($data['tax']);
        $product->setDeliveryCharges($data['deliverycharges']);
        $product->setDiscount($data['discount']);
        $product->setImage("pic.jpg");
        $product->setPostThumbnail("pi17.jpeg");
        $product->setCategory($category);
        $product->setUser($user);
        $product->setCreated(new \DateTime());
        $product->setUpdated(new \DateTime());

        $this->manager->flush();
    }

    public function removeProduct(Product $product)
    {
        $this->manager->remove($product);
        $this->manager->flush();
    }


    public function publishedProduct()
    {

        $query = $this->manager->createQuery("SELECT p FROM App\Entity\Product p JOIN
         p.category c WHERE p.status = 'published' and c.status =true ");
        $products = $query->getResult();
        
        return $products; 
    }




     /**
     * @return array Returns an array
     */
    
    public function findDownloadableData()
    {
        return $this->createQueryBuilder('p')
            //->andWhere('p.status = :status')
            //->setParameter('status', 'published')
            ->getQuery()
            ->getArrayResult()
        ;

    }
}
