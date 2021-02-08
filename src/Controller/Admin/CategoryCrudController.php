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
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
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
        return Category::class;
    }
      
    public function configureActions(Actions $actions): Actions
    {   
        $importCategoryButton = Action::new('importCategory', 'Import')->setCssClass('btn btn-default')->createAsGlobalAction()->linkToCrudAction('importCategory');
        $exportCategoryButton = Action::new('exportCategory', 'Export')->setCssClass('btn btn-default')->createAsGlobalAction()->linkToCrudAction('exportCategory');
    
        return $actions
              ->setPermission(Action::NEW, 'ROLE_ADMIN')
            //  ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            //  ->setPermission(Action::EDIT, 'ROLE_MANAGER')
             ->add(Crud::PAGE_INDEX, $exportCategoryButton)
             ->add(Crud::PAGE_INDEX, $importCategoryButton)
             ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
             
    }

    public function configureCrud( Crud $crud): Crud{
        return $crud->setEntityPermission('ROLE_ADMIN')
        ->setSearchFields(['name','id','popularity','language','countryOrigin','size','status'])
        ->setDefaultSort(['name'=>'ASC'])
        ->setPaginatorPageSize(5);
    }
    public function configureFilters(Filters $filters):Filters
    {
        return $filters
        ->add('name')
        ->add('countryOrigin')
        ->add('status')
        ->add('description')
        ->add('specialNotes');


    }
    public function configureFields(string $pageName): iterable
    {    
        return [
            IdField::new('id')->hideOnForm(),
             AssociationField::new('user'),
             //->formatValue(function ($value, $entity) {
            //    // return $entity->isPublished() ? $value : 'Coming soon...';
               
            //    $product = $userRepository->findOneBy([
            //     'email' => $value,
            //     ]);
            //     dump($product);
            //     if (in_array('ROLE_MANAGER', $product->getRoles())){
            //        return $value;
            //     }
            //     return;
            // }),
            TextField::new('name'),
            TextField::new('description'),
            TextField::new('countryOrigin'),
            ChoiceField::new('size')->setChoices([
                'Small' => 'small',
                'Medium' => 'medium',
                'Large' => 'large'
            ]),
            ChoiceField::new('popularity')->setChoices([
                'Low' => 'low',
                'Medium' => 'medium',
                'High' => 'high'
            ]),
            TextField::new('language'),
            TextField::new('specialNotes'),
            BooleanField::new('status'),
            TextField::new('att1'),
            TextField::new('att2'),
           // DateTimeField::new('created')->hideOnForm(),
            //DateTimeField::new('updated')->hideOnForm(),  


        ];

    }

    public function importCategory(Request $request)
    {   
        $err="";
        $category = new Category();
        $form = $this->createForm(ImportType::class, $category);        
        $form->handleRequest($request);

        $importedFile = $form->get('import_file')->getData();
        if ($form->isSubmitted() && $importedFile) {
           $originalname= $importedFile->getClientOriginalName(); 
              $ext = substr(strrchr($originalname, '.'), 1);
              
          if ($ext=="json")
            {
            $jsonData = file_get_contents($importedFile);
            $entityManager = $this->getDoctrine()->getManager();
            
            try{
                $postData = json_decode($jsonData);
                $this->logger->info('coverted to json.');
                
                $row=1;
                foreach ($postData as $catItem) {
                    
                    $err.="ROW NUMBER $row=>";
                    
                    $newCategory = new Category();
                    

                    $newCategory->setUser($this->getUser());
                    
                   
                    if(empty($catItem->name)){
                         $err.="name missing,";
                    	  $this->logger->info('name missing,');
                    }
                    else{
                       $newCategory->setName($catItem->name);
                    }
                    
                    
                   
                    if(empty($catItem->description)){
                         $err.="description missing,";
                    	  $this->logger->info('description missing,');
                    }
                    else{
                         $newCategory->setDescription($catItem->description);
                    }
                    
                   
                   if(empty($catItem->countryorigin)){
                         $err.="countryorigin missing,";
                    	  $this->logger->info('countryorigin missing,');
                    }
                    else{
                        $newCategory->setCountryOrigin($catItem->countryorigin);
                    }
    
                    
                    if(empty($catItem->size)){
                         $err.="size missing,";
                    	  $this->logger->info('size missing,');
                    }
                    else{
                        $newCategory->setSize($catItem->size);
                    }
                    
                   
                    $newCategory->setPopularity($catItem->popularity);
                    if(empty($catItem->popularity)){
                         $err.="popularity missing,";
                    	  $this->logger->info('popularity missing,');
                    }
                    
                    
                    if(empty($catItem->language)){
                         $err.="language missing,";
                    	  $this->logger->info('language missing,');
                    }
                    else{
                         $newCategory->setLanguage($catItem->language);
                      }
                   
                    
                    if(empty($catItem->specialnotes)){
                         $err.="specialnotes missing,";
                    	  $this->logger->info('specialnotes missing,');
                    }
                    else{
                        $newCategory->setSpecialNotes($catItem->specialnotes);
                    }
                    
                    
                    if(empty($catItem->specialnotes)){
                         $newCategory->setAtt1("other");
                    }
                    else{
                    	  $newCategory->setAtt1($catItem->att1);
                    }
                    
                    
                    if(empty($catItem->att2)){
                         $newCategory->setAtt2("other");
                    }
                    else{
                         $newCategory->setAtt2($catItem->att2);
                    }
                    
                    if(empty($catItem->created)){
                          $newCategory->setCreated(new \DateTime());
                    }
                    else{
                        $newCategory->setCreated($catItem->created);
                    }
                    
                    if(empty($catItem->updated)){
                          $newCategory->setUpdated(new \DateTime());
                    }
                    else{
                        $newCategory->setUpdated($catItem->updated);
                    }
        
                    
                   
                    if(empty($catItem->status)){
                        $newCategory->setStatus('1');
                    }
                    else{
                        $newCategory->setStatus($catItem->status);
                    }

                    $entityManager->persist($newCategory);
                    $entityManager->flush();
                    
                     $err.="imported successfully<br>";
                     $row++;
                    }

                  $this->addFlash('success', 'category data imported successfully');
                  $this->logger->info('Data imported', $postData);
                 } catch (\Exception $e){
                         if($err){
                         
                          $this->logger->error("Unable to import data correctly."."$err");
                          $this->addFlash('error',"Unable to import data correctly some values are missing please check logs");
                          return $this->render('product/log.html.twig', [
                                  'page_title' => 'Import logs',
                                  'back_link' => $this->adminUrlGenerator->setController(CategoryCrudController::class)->setAction(Action::INDEX)->generateUrl(),
                        'err' => $err
                         ]);
                     
                          }
                     
                    }
              }
            else{
              $this->addFlash('error', "expecting json file but .'".$ext."' given");
              $this->logger->error('wrong extension given');
            }
        }else{
            $this->logger->error('File was not uploaded');
        }

        return $this->render('category/import.html.twig', [
            'page_title' => 'Import Category',
            'back_link' => $this->adminUrlGenerator->setController(CategoryCrudController::class)->setAction(Action::INDEX)->generateUrl(),
            'form' => $form->createView()
        ]);
    }

    public function exportCategory()
    {
        try {
            $items = $this->CategoryRepository->findDownloadableData();
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
        
        return $this->redirect($this->adminUrlGenerator->setController(CategoryCrudController::class)->setAction(Action::INDEX)->generateUrl());
    }

    
}
