<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository ;
use App\Repository\CategoryRepository ;
use App\Service\ArticleService;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleService $articleService , CategoryRepository $CategoryRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'articles' => $articleService->getPaginatedArticles() ,
            'categories' => $CategoryRepo->findAll()
        ]);
    }
}
