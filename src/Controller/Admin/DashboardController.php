<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\ArticleCrudController;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\Menu;
use App\Entity\Option;
use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Component\DomCrawler\Link;

class DashboardController extends AbstractDashboardController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
        ->setController(ArticleCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();

                return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MYBLOG');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('home' , 'fa fa-undo' , 'app_home');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Comments' ,'fas fa-comment', Comment::class);
        if($this->isGranted('ROLE_AUTHOR')){

            yield MenuItem::subMenu('Articles', 'fas fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('All the articles', 'fas fa-newspaper', Article::class),
                MenuItem::linkToCrud('Add', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class),
    
            ]);
        }

        yield MenuItem::subMenu('Media', 'fas fa-photo-video')->setSubItems([
            MenuItem::linkToCrud('Mediatic', 'fas fa-photo-video',  Media::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus',  Media::class)->setAction(Crud::PAGE_NEW)
        ]);
       

        if($this->isGranted('ROLE_ADMIN')){

            yield MenuItem::subMenu('Menus', 'fas fa-list')->setSubItems([
                MenuItem::linkToCrud('Pages', 'fas fa-file',  Menu::class),
                MenuItem::linkToCrud('Articles', 'fas fa-newspaper',  Menu::class),
                MenuItem::linkToCrud('liens personnalisés', 'fas fa-link',  Menu::class),
                MenuItem::linkToCrud('Catégories', 'fab fa-delicious',  Menu::class),
    
            ]);

        }
      
        if($this->isGranted('ROLE_ADMIN')){
        yield MenuItem::subMenu('accounts', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('all the accounts', 'fas fa-user-friends',  User::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus',  User::class)->setAction(Crud::PAGE_NEW)



        ]);

        }

        yield MenuItem::subMenu('Fix', 'fas fa-cog')->setSubItems([
            MenuItem::linkToCrud('General', 'fas fa-cog',  Option::class)
        ]);
  
      
    }

}
