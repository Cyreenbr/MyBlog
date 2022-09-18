<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;


class MediaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Media::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
       $mediaDir = $this->getParameter('medias_directory');
       $uploadDir = $this->getParameter('uploads_directory');

       yield TextField::new('name');

       yield TextField::new('altTtext', 'alternative text');

       $imageField = ImageField::new('filename')
         ->setBasePath($uploadDir)
         ->setUploadDir($mediaDir)
         ->setUploadedFileNamePattern('[slug]-[uuid].[extension]');


         if ( Crud::PAGE_EDIT == $pageName){
            $imageField->setRequired(false);
         }

         yield $imageField ;
    }
   
}
