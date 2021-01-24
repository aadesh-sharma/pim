<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/pubished", name="published_products")
     */
    public function show(ProductRepository $productRepository,CategoryRepository $categoryRepository): Response
    {
    
            //$category =$categoryRepository->findBy(['status'=>'true']);
            //dump($product);
            //dump($product.getId());
            //$product = $productRepository->findBy(['status'=>'published']);
            //$product = $productRepository->findBy(['id'=>$product],['status'=>'published']);
            $product = $productRepository->publishedProduct();
            return $this->render('home/list.html.twig', [
                'product' => $product
            ]);
    }
    
}
