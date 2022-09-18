<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\type\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'article_show')]
    public function show(?Article $article): Response
    {   
        if(!$article){
            return $this->redirectToRoute('app_home');
        }
 
        $Comment = new Comment($article) ;
        
        $commentForm = $this->createForm(CommentType::class, $Comment);

        return $this->renderForm('article/show.html.twig', [
            'article' => $article ,
             'CommentForm' => $commentForm
         ]);
    }
}
