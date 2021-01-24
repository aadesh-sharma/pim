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
    // /**
    //  * @Route("/productapi", name="productapi")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('productapi/index.html.twig', [
    //         'controller_name' => 'ProductapiController',
    //     ]);
    // }

    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    
    /**
     * @Route("/add/", name="add_product", methods={"POST"})
     */
    public function add(Request $request,UserRepository $userRepository,CategoryRepository $categoryRepository): JsonResponse
    {   $user = $userRepository->findOneBy(['id'=>'1']);

        $data = json_decode($request->getContent(), true);
        //$user=   $this->getUser();
        $category= $categoryRepository->findOneBy(['id'=>'13']);
        $name = $data['name'];
        $shortdescription = $data['shortdescription'];
        $longdescription = $data['longdescription'];
        $height = $data['height'];
        $width = $data['width'];
        $color = $data['color'];
        $status= "draft";
        $brand = $data['brand'];
        $price=$data['price'];
        $quality = $data['quality'];
        $tax = $data['tax'];
        $deliverycharges = $data['deliverycharges'];
        $discount=$data['discount'];
        $image ='img.jpg';
        $thumbnail='pic19.jpeg';

        if (empty($user)||
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
               ) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
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
    public function updateProduct($id, Request $request): JsonResponse
    {
        $product = $this->ProductRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->findOneBy(['id'=>'1']);
        $category= $user;
        $this->productRepository->updateProduct($product, $data,$user,$category);

        return new JsonResponse(['status' => 'product updated!']);
    }
    
    /**
     * @Route("/delete/{id}", name="delete_category", methods={"DELETE"})
     */
    public function deleteProduct($id): JsonResponse
    {
        $product= $this->productRepository->findOneBy(['id' => $id]);

        $this->productRepository->removeProduct($product);

        return new JsonResponse(['status' => 'product deleted']);
    }




}
