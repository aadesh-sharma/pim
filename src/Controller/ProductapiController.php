<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    /**
     * @Route("/apiproduct")
     */
class ProductapiController extends AbstractController
{
    

    private $productRepository;
    private $categoryRepository;
    private $userRepository;

    public function __construct(ProductRepository $productRepository,CategoryRepository $categoryRepository,UserRepository $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository= $categoryRepository;
        $this->userRepository= $userRepository;
    }

    
    /**
     * @Route("/add/", name="add_product", methods={"POST"})
     */
    public function add(Request $request,UserRepository $userRepository,CategoryRepository $categoryRepository): JsonResponse
    {   
        
        $data = json_decode($request->getContent(), true);
      
        
      ///////////////////////////////////////////////////////
      
        $user = $userRepository->findOneBy(['id'=>'1']);
        
        if(!empty($data['category']))
        {
          $category = $categoryRepository->findOneBy(['id'=>$data['category']]);
          if($category)
            {$category = $data['category'];}
          else{  $category = $categoryRepository->findOneBy(['id'=>'13']);  }
        }
        else{
          $category = $categoryRepository->findOneBy(['id'=>'13']);
        }
        
        if(!empty($data['name'])){
           $name = $data['name'];
          }
          else{
          $name ="";
        }
        
        if(!empty($data['shortdescription'])){
           $shortdescription = $data['shortdescription'];
        }
        else{
           $shortdescription ="";
        }
        
        if(!empty($data['longdescription'])){
           $longdescription = $data['longdescription'];
        }
        else{
           $longdescription ="";
        }
        
        if(!empty($data['height'])){
            $height = $data['height'];
        }
        else{
           $height ="";
        }
       
       if(!empty($data['width'])){
            $width = $data['width'];
        }
         else{
           $width="";
        }
        
        if(!empty($data['color'])){
            $color = $data['color'];
        }
        else{
           $color="";
        }
        
        if(!empty($data['status'])){
            $status= $data['status'];
         }
        else{
          $status= 'draft';
        }
        
        if(!empty($data['brand'])){
            $brand = $data['brand'];
        }
        else{
           $brand ="";
        }
        
        if(!empty($data['price'])){
           $price=$data['price'];
        }
        else{
           $price="";
        }
        
        if(!empty($data['tax'])){
           $tax = $data['tax'];
        }
        else{
           $tax="";
        }
         
        if(!empty($data['deliverycharges'])){
           $deliverycharges = $data['deliverycharges'];
        }
        else{
           $deliverycharges ="";
        }
         
        
        if(!empty($data['discount'])){
           $discount=$data['discount'];
        }
         else{
           $discount="";
        }
        
        if(!empty($data['quality'])){
          $quality=$data['quality'];
        }
        else{
           $quality="none";
        }
        
        if(!empty($data['image'])){
          $image=$data['image'];
        }
        else{
          $image='default.jpg';
        }
        
        if(!empty($data['thumbnail'])){
          $thumbnail=$data['thumbnail'];
        }
        else{
          $thumbnail='pic17.jpeg';
        }
        
        
 
        ////////////////////////////////

        if ((empty($user)||
            empty($category)||
            empty($name )||
            empty($shortdescription )||
            empty($longdescription )||
            empty($height )||
            empty($width )||
            empty($color )||
            empty($status)||
            empty($brand )||
            empty($price)||
            empty($quality)||
            empty( $tax )||
            empty($deliverycharges)||
            empty($discount)||
            empty( $image )||
            empty( $thumbnail)
               ) || count($data)>16)
           {
              $arr= array($name,$shortdescription, 
                       $longdescription,$height, $width,$color ,$brand,$price,
                        $tax ,$deliverycharges,$discount);
           $cols= array('name','shortdescription', 
                       'longdescription','height','width','color','brand','price',
                        'tax' ,'deliverycharges','discount');
          
           $missing=array();
           foreach($arr as $k=>$item){
              if(empty($item)){
                   array_push($missing,$cols[$k]);
                 }
               }
           
           $err="";
           if(count($missing)){
               $err="missing important values:  ".implode(",",$missing);
              }
           if(count($data)>16){
               $err=$err."and Extra parameters supplied";
             }
            
            return new JsonResponse(['status' => $err], Response::HTTP_CREATED);
            //throw new NotFoundHttpException($err);
        }

        $this->productRepository->saveProduct(
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
        );

        return new JsonResponse(['status' => 'product created!'], Response::HTTP_CREATED);
    }


    /**
     * @Route("/get/{id}", name="get_one_product", methods={"GET"})
    */
    public function getOneProduct($id): JsonResponse
    {      
        $product = $this->productRepository->findOneBy(['id' => $id]);
        if(empty($product)){
           return new JsonResponse(['status' => "does not exist"], Response::HTTP_OK);
        }
        $data = [
            'id' =>  $product->getId(),
            'name' =>  $product->getName(),
            'shortdescription' =>  $product->getShortDescription(),
            'longdescription' =>  $product->getLongDescription(),
            'height' =>  $product->getHeight(),
             'width'=>  $product->getWidth(),
            'color'=> $product->getColor(),
            'status'=> $product->getStatus(),
            'brand'=> $product->getBrand(),
            'price'=> $product->getPrice(),
            'quality'=> $product->getQuality(),
            'tax' =>  $product->getTax(),
            'deliverycharges' =>  $product->getDeliveryCharges(),
            'discount' =>  $product->getDiscount(),
            'created' =>  $product->getCreated(),
            'updated' =>  $product->getUpdated(),
            'image'=>  $product->getImage(),
            'thumbnail' =>  $product->getPostThumbnail(),
            'category'=>  $product->getCategory(),
            'user' =>  $product->getUser()
            
        ];

        return new JsonResponse(['product' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/get-all", name="get_all_product", methods={"GET"})
     */
    public function getAllProduct(): JsonResponse
    {
        $product= $this->productRepository->findAll();
         if(empty($product)){
           return new JsonResponse(['status' => "no record exist"], Response::HTTP_OK);
        }
        $data = [];

        foreach ($product as $products) {
            $data[] = [
            'id' =>  $products->getId(),
            'name' =>  $products->getName(),
            'shortdescription' =>  $products->getShortDescription(),
            'longdescription' =>  $products->getLongDescription(),
            'height' =>  $products->getHeight(),
            'width'=>  $products->getWidth(),
            'color'=> $products->getColor(),
            'status'=> $products->getStatus(),
            'brand'=> $products->getBrand(),
            'price'=> $products->getPrice(),
            'quality'=> $products->getQuality(),
            'tax' =>  $products->getTax(),
            'deliverycharges' =>  $products->getDeliveryCharges(),
            'discount' =>  $products->getDiscount(),
            'created' =>  $products->getCreated(),
            'updated' =>  $products->getUpdated(),
            'image'=>  $products->getImage(),
            'thumbnail' =>  $products->getPostThumbnail(),
            'category'=>  $products->getCategory(),
            'user' =>  $products->getUser()
                ];
        }

        return new JsonResponse(['products' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_product", methods={"PUT"})
     */
    public function updateProduct($id, Request $request,UserRepository $userRepository,CategoryRepository $categoryRepository): JsonResponse
    {    
        $product= $this->productRepository->findOneBy(['id' => $id]);
         if(empty($product)){
           return new JsonResponse(['status' => "no such data exist"], Response::HTTP_OK);
        }
      
        $data = json_decode($request->getContent(), true);
        


        ///////////////////////////////////////////////////////
      
        $user = $userRepository->findOneBy(['id'=>'1']);
        
        if(!empty($data['category']))
        {
          $category = $categoryRepository->findOneBy(['id'=>$data['category']]);
          if($category)
            {$category = $data['category'];}
          else{  $category = $categoryRepository->findOneBy(['id'=>'13']);  }
        }
        else{
          $category = $categoryRepository->findOneBy(['id'=>'13']);
        }
        
        if(!empty($data['name'])){
           $name = $data['name'];
          }
          else{
          $name ="";
        }
        
        if(!empty($data['shortdescription'])){
           $shortdescription = $data['shortdescription'];
        }
        else{
           $shortdescription ="";
        }
        
        if(!empty($data['longdescription'])){
           $longdescription = $data['longdescription'];
        }
        else{
           $longdescription ="";
        }
        
        if(!empty($data['height'])){
            $height = $data['height'];
        }
        else{
           $height ="";
        }
       
       if(!empty($data['width'])){
            $width = $data['width'];
        }
         else{
           $width="";
        }
        
        if(!empty($data['color'])){
            $color = $data['color'];
        }
        else{
           $color="";
        }
        
        if(!empty($data['status'])){
            $status= $data['status'];
         }
        else{
          $status= 'draft';
        }
        
        if(!empty($data['brand'])){
            $brand = $data['brand'];
        }
        else{
           $brand ="";
        }
        
        if(!empty($data['price'])){
           $price=$data['price'];
        }
        else{
           $price="";
        }
        
        if(!empty($data['tax'])){
           $tax = $data['tax'];
        }
        else{
           $tax="";
        }
         
        if(!empty($data['deliverycharges'])){
           $deliverycharges = $data['deliverycharges'];
        }
        else{
           $deliverycharges ="";
        }
         
        
        if(!empty($data['discount'])){
           $discount=$data['discount'];
        }
         else{
           $discount="";
        }
        
        if(!empty($data['quality'])){
          $quality=$data['quality'];
        }
        else{
           $quality="none";
        }
        
        if(!empty($data['image'])){
          $image=$data['image'];
        }
        else{
          $image='default.jpg';
        }
        
        if(!empty($data['thumbnail'])){
          $thumbnail=$data['thumbnail'];
        }
        else{
          $thumbnail='pic17.jpeg';
        }
        
        
 
        ////////////////////////////////

        if ((empty($user)||
            empty($category)||
            empty($name )||
            empty($shortdescription )||
            empty($longdescription )||
            empty($height )||
            empty($width )||
            empty($color )||
            empty($status)||
            empty($brand )||
            empty($price)||
            empty($quality)||
            empty( $tax )||
            empty($deliverycharges)||
            empty($discount)||
            empty( $image )||
            empty( $thumbnail)
               ) || count($data)>16)
           {
              $arr= array($name,$shortdescription, 
                       $longdescription,$height, $width,$color ,$brand,$price,
                        $tax ,$deliverycharges,$discount);
           $cols= array('name','shortdescription', 
                       'longdescription','height','width','color','brand','price',
                        'tax' ,'deliverycharges','discount');
          
           $missing=array();
           foreach($arr as $k=>$item){
              if(empty($item)){
                   array_push($missing,$cols[$k]);
                 }
               }
           
           $err="";
           if(count($missing)){
               $err="missing important values:  ".implode(",",$missing);
              }
           if(count($data)>16){
               $err=$err."and Extra parameters supplied";
             }
            
            return new JsonResponse(['status' => $err], Response::HTTP_CREATED);
            //throw new NotFoundHttpException($err);
        }

         $this->productRepository->updateProduct($product,
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
        );
        
       
        return new JsonResponse(['status' => 'product updated!']);
    }
    
    /**
     * @Route("/delete/{id}", name="delete_category", methods={"DELETE"})
     */
    public function deleteProduct($id): JsonResponse
    {
        $product= $this->productRepository->findOneBy(['id' => $id]);
         if(empty($product)){
           return new JsonResponse(['status' => "attempting to delete data that does not exist"], Response::HTTP_OK);
        }
        $this->productRepository->removeProduct($product);

        return new JsonResponse(['status' => 'product deleted']);
    }




}
