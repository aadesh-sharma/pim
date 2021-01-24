<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
    /**
     * @Route("/apicategory")
     */
class CategoryapiController extends AbstractController
{
    // /**
    //  * @Route("/categoryapi", name="categoryapi")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('categoryapi/index.html.twig', [
    //         'controller_name' => 'CategoryapiController',
    //     ]);
    // }

    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    
    /**
     * @Route("/add/", name="add_category", methods={"POST"})
     */
    public function add(Request $request,UserRepository $userRepository ): JsonResponse
    {   $user = $userRepository->findOneBy(['id'=>'1']);
        $data = json_decode($request->getContent(), true);
        //dump($data);
        //$user=  $security->getUser();
        $name = $data['name'];
        $description = $data['description'];
        $countryorigin = $data['countryorigin'];
        $specialnotes = $data['specialnotes'];
        $size = $data['size'];
        $popularity= $data['popularity'];
        $language = $data['language'];
        $status = true;
        $att1 =$data['att1'];
        $att2 = $data['att2'];
       

        if (empty($user)||
        empty($name)|| 
        empty($description) ||
        empty($countryorigin) ||
        empty($specialnotes) ||
        empty($size )||
        empty($popularity) ||
        empty($language ) ||
        empty($att1 ) ||
        empty($att2 )
               ) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->categoryRepository->saveCategory(
            $user,
            $name ,
            $description ,
            $countryorigin ,
            $specialnotes ,
            $size ,
            $popularity,
            $language ,
            $att1 ,
            $att2 ,
            $status 
        );

        return new JsonResponse(['status' => 'Category created!'], Response::HTTP_CREATED);
    }


    /**
     * @Route("/get/{id}", name="get_one_category", methods={"GET"})
     */
    public function getOneCategory($id): JsonResponse
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);

        $data = [
            'id' =>  $category->getId(),
            'name' =>  $category->getName(),
            'description' =>  $category->getDescription(),
            'countryorigin' =>  $category->getCountryOrigin(),
            'specialnotes'=>  $category->getSpecialNotes(),
            'size'=> $category->getSize(),
            'popularity'=> $category->getPopularity(),
            'language'=> $category->getLanguage(),
            'status'=> $category->getStatus(),
            'products'=> $category->getProducts(),
            'att1' =>  $category->getAtt1(),
            'att2' =>  $category->getAtt2(),
        ];

        return new JsonResponse(['category' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/get-all", name="get_all_category", methods={"GET"})
     */
    public function getAllCustomers(): JsonResponse
    {
        $category= $this->categoryRepository->findAll();
        $data = [];

        foreach ($category as $categories) {
            $data[] = [
                'id' =>  $categories->getId(),
                'name' =>  $categories->getName(),
                'description' =>  $categories->getDescription(),
                'countryorigin' =>  $categories->getCountryOrigin(),
                'specialnotes'=>  $categories->getSpecialNotes(),
                'size'=> $categories->getSize(),
                'popularity'=> $categories->getPopularity(),
                'language'=> $categories->getLanguage(),
                'status'=> $categories->getStatus(),
                'products'=> $categories->getProducts(),
                'att1' => $categories->getAtt1(),
                'att2' => $categories->getAtt2(),
                ];
        }

        return new JsonResponse(['categories' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_category", methods={"PUT"})
     */
    public function updateCategory($id, Request $request): JsonResponse
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        $this->categoryRepository->updateCategory($category, $data);

        return new JsonResponse(['status' => 'category updated!']);
    }

    /**
     * @Route("/delete/{id}", name="delete_category", methods={"DELETE"})
     */
    public function deleteCategory($id): JsonResponse
    {
        $category= $this->categoryRepository->findOneBy(['id' => $id]);

        $this->categoryRepository->removeCategory($category);

        return new JsonResponse(['status' => 'category deleted']);
    }







}
