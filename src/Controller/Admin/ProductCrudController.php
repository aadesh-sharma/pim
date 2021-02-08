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
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
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
                  ->add(Crud::PAGE_INDEX, Action::DETAIL)
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
            NumberField::new('height')->setLabel('height in cm'),
            NumberField::new('width')->setLabel('width in cm'),
            //DateTimeField::new('created')->hideOnForm(),
            //DateTimeField::new('updated')->hideOnForm(),    
        ];

    
    }
    public function configureCrud( Crud $crud): Crud{
        return $crud->setEntityPermission('ROLE_ADMIN')
        ->setSearchFields(['name','color','brand','quality'])
        ->setDefaultSort(['name'=>'DESC'])
        ->setPaginatorPageSize(5);
    }
    public function configureFilters(Filters $filters):Filters
    {
        return $filters
        ->add('name')
        ->add('category')
        ->add('user')
        ->add('quality')
        ->add('brand')
        ->add('price')
        ->add('tax')
        ->add('deliveryCharges')
        ->add('discount')
        ->add('color')
        ->add('status')
        ->add('created')
        ->add('updated')
        ;

    }

    public function importProduct(Request $request)
    {   
        $err="";
        
        $products = new Product();
        $form = $this->createForm(ImportType::class, $products);        
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
            
                $row=1;
                
                foreach ($postData as $prodItem) {
                    $newprod= new Product();
                    
                  
                    //$err="";
                    $err.="ROW NUMBER $row=>";
                    
                    $newprod->setUser($this->getUser());
                    
                    
                    if(empty($prodItem->category_id)){
                        $newprod->setCategory('13');
                    }
                    else{
                        $category = $this->CategoryRepository->find($prodItem->category_id);
                        if($category)
                        { $newprod->setCategory($category);}
                        else{ $newprod->setCategory('13'); }
                    }
                    
                    
                    
                    
                    if(empty($prodItem->name)){
                    	$err.=" name missing,";
                    	$this->logger->info('name missing,');
                    }
                    else{
                       $newprod->setName($prodItem->name);
                    }
                    
                    
                    if(empty($prodItem->shortdescription)){
                    	$err.=" shortdescription missing,";
                    	$this->logger->info('shortdescription missing.');
                    }
                    else{
                      $newprod->setShortDescription($prodItem->shortdescription);
                    }
                    
                  
                    
                    if(empty($prodItem->longdescription)){
                    	$err.=" longdescription missing,";
                    	$this->logger->info('long description missing.');
                    }
                    else{
                       $newprod->setLongDescription($prodItem->longdescription);
                    }
                    
		   
		     
                    if(empty($prodItem->height)){
                    	$err.=" height missing,";
                    	$this->logger->info('height missing.');
                    }
                    else{
                    	$newprod->setHeight($prodItem->height);
                    }
                    
		     
		    
		    if(empty($prodItem->width)){
                    	$err.=" width missing,";
                    	$this->logger->info('width missing,');
                    }
                    else{
                       $newprod->setWidth($prodItem->width);
                    }
                   
                   
                   
                    if(empty($prodItem->color)){
                    	$err.=" color missing,";
                    	$this->logger->info('color missing,');
                    }
                    else{
                       $newprod->setColor($prodItem->color);
                    }
                   
		     
		     if(empty($prodItem->status)){
                    	$newprod->setStatus('1');
                    }
                    else{
                       $newprod->setStatus($prodItem->status);
                    }
		     
		    
		     
		     if(empty($prodItem->brand)){
                    	$err.=" brand missing,";
                    	$this->logger->info('brand missing');
                    }
                    else{
                       $newprod->setBrand($prodItem->brand);
                    }
                    
		     
		     
		     if(empty($prodItem->deliverycharges)){
                    	$err.=" delivery charges missing,";
                    	$this->logger->info('delivery charges missing,');
                    }
                    else{
                      $newprod->setDeliveryCharges($prodItem->deliverycharges);
                    }
                    
		      
		     
		     if(empty($prodItem->discount)){
                    	$err.=" discount missing,";
                    	$this->logger->info('discount missing,');
                    }
                    else{
                       $newprod->setDiscount($prodItem->discount);
                    }
                    
                    
		     
                     
                    
                    if(empty($prodItem->thumbnail)){
                    	$newprod->setPostThumbnail('pic17.jpeg');
                    }
                    else{
                       $newprod->setPostThumbnail($prodItem->thumbnail);
                    }
                    
                    
                    if(empty($prodItem->image)){
                    	$newprod->setImage('image.jpg');
                    }
                    else{
                       $newprod->setImage($prodItem->image);
                    }
                    
		      
		     
		    if(empty($prodItem->price)){
                    	$err.=" price missing,";
                       $this->logger->info('price missing,');
                    }
                    else{
                       $newprod->setPrice($prodItem->price);
                    }
            
            
		   if(empty($prodItem->quality)){
                    	$newprod->setQuality("none");
                    }
                   else{
                       $newprod->setQuality($prodItem->quality);
                   } 
                   
                  
                    
		   if(empty($prodItem->tax)){
                    	$err.=" tax missing,";
                    	$this->logger->info('tax missing.');
                    }
                   else{
                       $newprod->setTax($prodItem->tax); 
                    }
                    
                     
                     
                    if(empty($prodItem->created)){
                          $newprod->setCreated(new \DateTime());
                     }
                    else{
                        $newprod->setCreated($catItem->created);
                    }
                    
                     
                    
                    if(empty($prodItem->updated)){
                          $newprod->setUpdated(new \DateTime());
                    }
                    else{
                        $newprod->setUpdated($catItem->updated);
                    }
                    
                   
                   
                    $entityManager->persist($newprod);
                    $entityManager->flush();
                    
                    $err.="imported successfully<br>";
                    $row++;
                    
                    
                  }
                  
                  

                  $this->addFlash('success', 'product data imported successfully');
                  $this->logger->info('Data imported', $postData);
                } catch (\Exception $e){
                   
                    
                     if($err){
                         
                          $this->logger->error("Unable to import data correctly."."$err");
                          $this->addFlash('error',"Unable to import data correctly some values are missing please check logs");
                          return $this->render('product/log.html.twig', [
                                  'page_title' => 'Import logs',
                                  'back_link' => $this->adminUrlGenerator->setController(ProductCrudController::class)->setAction(Action::INDEX)->generateUrl(),
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



