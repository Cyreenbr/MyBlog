<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use  App\Entity\Category ;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'category_show')]
    public function show(Category $category): Response
    {
        if(!$category){
            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/show.html.twig', [
            'category' => $category ,
        ]);
    }
}
