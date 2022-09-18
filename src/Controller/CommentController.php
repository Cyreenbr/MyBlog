<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManager;

/**
 * @method User getUser()
 */
class CommentController extends AbstractController
{
    #[Route('/ajax/comments', name: 'comment_add')]
    public function add(Request $request, ArticleRepository $articleRepo, CommentRepository $commentRepo, UserRepository $userRepo, EntityManager $em): Response
    {
        $CommentData = $request->request->all('comment');

         if (!$this->isCsrfTokenValid('comment-add', $CommentData['_token'])) {
            return $this->json([
                'code' => 'INVALID_CSRF_TOKEN'
            ], Response::HTTP_BAD_REQUEST);
         }

         $article = $articleRepo->findOneBy(['id' => $CommentData['article'] ]);
         
         if(!$article){
            return $this->json([
                'code' => 'ARTICLE_NOT_FOUND'
            ], Response::HTTP_BAD_REQUEST
        );
         }

        $user = $this->getUser();

        if(!$user){
           return $this->json([
              'code' => 'USER_NOT_AUTHENTICATED_FULLY'
           ], Response::HTTP_BAD_REQUEST);
        }

        $comment = new Comment($article);
        $comment->setContent($CommentData['content']);
        $comment->setUser($user);
        $comment->setCreatedAt(new \DateTime());

        $em->persist($comment);
        $em->flush();

        $html = $this->$this->renderView('comment/index.html.twig',[
            'comment' => $comment 
        ]);

        return $this->json( [
            'code' => 'COMMENT_ADDED_SUCCESSFULLY',
            'message' => $html ,
            'numberOfComments' => $commentRepo->count(['article => $article'])
        ]);
    }
}
