<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
   
    private $manager;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager )
    {
        parent::__construct($registry, Category::class);
        $this->manager = $manager;
    }

    // /**
    //  * @return Category[] Returns an array of Category objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Category
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function saveCategory($user,$name ,$description ,
                                $countryorigin ,$specialnotes ,$size ,
                                $popularity,$language ,$status ,
                                $att1 ,$att2)
    {
        $newCategory = new Category();
        $newCategory
            ->setUser($user)
            ->setName( $name)
            ->setDescription($description)
            ->setCountryOrigin($countryorigin)
            ->setSpecialNotes($specialnotes)
            ->setSize( $size)
            ->setPopularity($popularity)
            ->setLanguage($language)
            ->setStatus($status )
            ->setAtt1($att1)
            ->setAtt2($att2)
            ->setCreated(new \DateTime())
            ->setUpdated(new \DateTime());



        $this->manager->persist($newCategory);
        $this->manager->flush();
    }
    
    public function updateCategory(Category $category, $data)
    {
    
        $category->setUser($data['user']);
        $category->setName( $data['$name']);
        $category->setDescription($data['$description']);
        $category->setCountryOrigin($data['$countryorigin']);
        $category->setSpecialNotes($data['$specialnotes']);
        $category->setSize( $data['$size']);
        $category->setPopularity($data['$popularity']);
        $category->setLanguage($data['$language']);
        $category->setStatus($data['$status']);
        $category->setAtt1($data['$att1']);
        $category->setAtt2($data['$att2']);
        $category->setCreated(new \DateTime());
        $category->setUpdated(new \DateTime());

        $this->manager->flush();
    }

    public function removeCategory(Category $category)
    {
        $this->manager->remove($category);
        $this->manager->flush();
    }



    /**
     * @return array Returns an array
     */
    
    public function findDownloadableData()
    {
        return $this->createQueryBuilder('c')
            //->andWhere('c.post_status = :post_status')
            //->setParameter('post_status', 'active')
            ->getQuery()
            ->getArrayResult()
        ;

    }
}
