<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\User;
use App\Entity\Category;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Form\CategoryType;
use App\Form\ImportType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{   
    public function __construct(AdminUrlGenerator $adminUrlGenerator, 
                                CategoryRepository $CategoryRepository, 
                                ProductRepository $ProductRepository, 
                                LoggerInterface $logger) 
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->CategoryRepository = $CategoryRepository;
        $this->ProductRepository = $ProductRepository;
        $this->logger = $logger;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $importProductButton = Action::new('importProduct', 'Import')->setCssClass('btn btn-default')->createAsGlobalAction()->linkToCrudAction('importProduct');
        $exportProductButton = Action::new('exportProduct', 'Export')->setCssClass('btn btn-default')->createAsGlobalAction()->linkToCrudAction('exportProduct');
     
        return $actions
             ->setPermission(Action::NEW, 'ROLE_ADMIN')
                 //  ->setPermission(Action::NEW, 'ROLE_ADMIN')
                 //  ->setPermission(Action::DELETE, 'ROLE_ADMIN')
                 //  ->setPermission(Action::EDIT, 'ROLE_MANAGER')
                  ->add(Crud::PAGE_INDEX, $exportProductButton)
                  ->add(Crud::PAGE_INDEX, $importProductButton)
                 ;
             
    }
    public function configureFields(string $pageName): iterable
    {   //dump($this->getUser()->getRoles());
        $uploadPath = $this->getParameter('products');
        return [

            IdField::new('id')->hideOnForm(),
            AssociationField::new('category')->setPermission('ROLE_ADMIN'),
            AssociationField::new('user')->setPermission('ROLE_ADMIN'),
            TextField::new('name'),
            TextField::new('shortDescription'),
            TextField::new('longDescription'),
            ImageField::new('post_thumbnail')->setLabel('Image')->setBasePath($uploadPath['uploads']['url_prefix'])->setUploadDir($uploadPath['uploads']['url_path'])->setRequired(false),
            NumberField::new('height')->setLabel('height in cm'),
            NumberField::new('width')->setLabel('width in cm'),
            TextField::new('color'),
            TextField::new('brand'),
            NumberField::new('price')->setLabel('price in rs.'),
            ChoiceField::new('quality')->setChoices([
                'Low' => 'low',
                'Average' => 'average',
                'High' => 'high'
            ]),
            NumberField::new('tax')->setLabel('tax in %.'),
            NumberField::new('deliveryCharges')->setLabel('delivery charges in rs.'),
            NumberField::new('discount')->setLabel('discount in rs.'),
            ChoiceField::new('status')->setChoices([
                'Draft' => 'draft',
                'Reviewed' => 'reviewed',
                'Published' => 'published'
            ])->setRequired('False'),
            
            //DateTimeField::new('created')->hideOnForm(),
            //DateTimeField::new('updated')->hideOnForm(),    
        ];

    
    }

    public function importProduct(Request $request)
    {
        $products = new Product();
        $form = $this->createForm(ImportType::class, $products);        
        $form->handleRequest($request);

        $importedFile = $form->get('import_file')->getData();
        if ($form->isSubmitted() && $importedFile) {
            $jsonData = file_get_contents($importedFile);
            $entityManager = $this->getDoctrine()->getManager();
            
            try{
                $postData = json_decode($jsonData);
                $this->logger->info('file imported.');
                dump($postData);
                foreach ($postData as $prodItem) {
                    $newprod= new Product();

                    $category = $this->CategoryRepository->find($prodItem->category_id);
                
                    $newprod->setUser($this->getUser());
                    $this->logger->info('after manager.');
     
                    if(!empty($category)){
                        $newprod->setCategory($category);
                    }
                    $this->logger->info('after category.');
                    $newprod->setName($prodItem->name);

                    $this->logger->info('after name.');
                    $newprod->setShortDescription($prodItem->shortdescription);

                    $this->logger->info('after shortdesc.');
                    $newprod->setLongDescription($prodItem->longdescription);

                    $this->logger->info('after long desc.');
                    $newprod->setHeight($prodItem->height);

                    $this->logger->info('after height.');
                    $newprod->setWidth($prodItem->width);

                    $this->logger->info('after width.');
                    $newprod->setColor($prodItem->color);

                    $this->logger->info('after color.');
                    $newprod->setStatus($prodItem->status);

                    $this->logger->info('after status.');
                    $newprod->setBrand($prodItem->brand);

                    $this->logger->info('after brand.');
                    $newprod->setDeliveryCharges($prodItem->deliverycharges);

                    $this->logger->info('after del cahrg.');
                    $newprod->setDiscount($prodItem->discount);

                    $this->logger->info('after discount.');
                    $newprod->setCreated(new \DateTime());

                    $this->logger->info('after created.');
                    $newprod->setUpdated(new \DateTime());

                    $this->logger->info('after update');
                    $newprod->setPostThumbnail($prodItem->thumbnail);

                    $this->logger->info('after thumnail.');
                    $newprod->setImage('image.jpg');

                    $this->logger->info('after image');
                    $newprod->setPrice($prodItem->price);

                    $this->logger->info('after price.');
                    $newprod->setQuality($prodItem->quality);

                    $this->logger->info('after quality');
                    
                    $newprod->setTax($prodItem->tax);
                    $this->logger->info('after tax.');

                    $entityManager->persist($newprod);
                    $entityManager->flush();
                }

                $this->addFlash('success', 'product data imported successfully');
                $this->logger->info('Data imported', $postData);
            } catch (\Exception $e){
                $this->addFlash('error', 'Unable to import data correctly.');
                $this->logger->error('Unable to import data correctly.');
            }
        }else{
            $this->logger->error('File was not uploaded');
        }

        return $this->render('product/import.html.twig', [
            'page_title' => 'Import product',
            'back_link' => $this->adminUrlGenerator->setController(ProductCrudController::class)->setAction(Action::INDEX)->generateUrl(),
            'form' => $form->createView()
        ]);
    }

    public function exportProduct()
    {
        try {
            $items = $this->ProductRepository->findDownloadableData();
            $filename = sprintf("%s_%s.json", 'EXPORT_FILE_POST',microtime(true));
            if(empty($items)){
                $this->addFlash('error', "There are no category available in the list.");
            }else{
                $response = new Response(json_encode($items)); 
                $disposition = HeaderUtils::makeDisposition(
                    HeaderUtils::DISPOSITION_ATTACHMENT,
                    $filename
                );
                $response->headers->set('Content-Disposition', $disposition);

                return $response;
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Something wrong!! Try to find the perfect exception.");
        }
        
        return $this->redirect($this->adminUrlGenerator->setController(ProductCrudController::class)->setAction(Action::INDEX)->generateUrl());
    }


    
    
}
