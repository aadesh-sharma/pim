<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        //return parent::index();
        
        if ($this->getUser()) {
            $routeBuilder = $this->get(AdminUrlGenerator::class);

            return $this->redirect($routeBuilder->setController(CategoryCrudController::class)->generateUrl());
          }
        else{
            
            return $this->render('exception.html.twig', [
            'message'=>"access denied 401",
            ]);
        }

        
        // // redirect to some CRUD controller
         $routeBuilder = $this->get(AdminUrlGenerator::class);

         return $this->redirect($routeBuilder->setController(CategoryCrudController::class)->generateUrl());

        // // you can also redirect to different pages depending on the current user
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // // you can also render some template to display a proper Dashboard
        // // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        // return $this->render('some/path/my-dashboard.html.twig');


    }

    public function configureDashboard(): Dashboard
    {   if($this->getUser() && in_array('ROLE_MANAGER', $this->getUser()->getRoles())){
             return Dashboard::new()->setTitle('MANAGER DASHBOARD');
          }
        return Dashboard::new()
            ->setTitle('ADMIN DASHBOARD');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        dump($this->getUser()->getRoles());

        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
       
         
        if(in_array('ROLE_ADMIN', $this->getUser()->getRoles())){
            yield MenuItem::linkToCrud('Category', 'fa fa-tags', Category::class);
            yield MenuItem::linkToCrud('Product', 'fa fa-file-text', Product::class);
            yield MenuItem::linkToCrud('User', 'fas fa-user', User::class);
        }
        elseif(in_array('ROLE_MANAGER', $this->getUser()->getRoles())) {
            yield MenuItem::linkToCrud('Product', 'fa fa-file-text', Product::class);
            yield MenuItem::linkToCrud('Category', 'fa fa-tags', Category::class);
        }
        else{
            dump("Nothing to show to you ,contact admin");
        }
       
      

    }
}
