<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PhpParser\Node\Expr\Cast\String_;

class MenuCrudController extends AbstractCrudController
{
     const MENU_PAGES = 0 ;
     const MENU_ARTICLES = 1 ;
     const MENU_LINKS = 2 ;
     const MENU_CATEGORIES = 3 ;

     public function __construct(
        private requestStack $requestStack,
        private MenuRepository $menuRepo
     )
     {
        
     }


    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $subMenuIndex = $this->getSubMenuIndex();
 
        return $this->menuRepo->getIndexQueryBuilder($this->getFieldNameFromSubMenuIndex($subMenuIndex));

    }

    public function configureCrud(Crud $crud): Crud
    {
        $subMenuIndex = $this->getSubMenuIndex();

        $entityLabelInSingular = 'un menu';

        $entityLabelInPlural = match ($subMenuIndex) {
            self::MENU_ARTICLES => 'Articles',
            self::MENU_CATEGORIES => 'Catégories',
            self::MENU_LINKS => 'Liens personnalisés',
            default => 'Pages'
        };

        return $crud
            ->setEntityLabelInSingular($entityLabelInSingular)
            ->setEntityLabelInPlural($entityLabelInPlural);
    }


    private function getSubMEnuIndex(): int {
        return $this->requestStack->getMainRequest()->query->getInt('submenuIndex');
    }

    
    public function configureFields(string $pageName): iterable
    {
       $subMenuIndex = $this->getSubMEnuIndex();

        yield TextField::new('name', 'titre de la navigation' );
        yield NumberField::new('menuOrder', 'Ordre');
        yield BooleanField::new('isVisible', 'Visible');
        yield AssociationField::new('subMenu', 'Sous-éléments');
        yield $this->getFieldFromSubMenuIndex($subMenuIndex)->setRequired(true);
    }

    private function getFieldNameFromSubMenuIndex(int $subMenuIndex):string
    {
         return $fieldname = match ($subMenuIndex) {
            self::MENU_ARTICLES => 'article',
            self::MENU_CATEGORIES => 'category',
            self::MENU_LINKS => 'link',
            default => 'page'
        };
    }

    private function getFieldFromSubMenuIndex(int $subMenuIndex): AssociationField|TextField{
        $fieldname = $this->getFieldNameFromSubMenuIndex($subMenuIndex);

        return ($fieldname == 'link')? TextField::new($fieldname) : AssociationField::new($fieldname);


    }
    
}
