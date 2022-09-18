<?php

namespace App\Service ;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Category;

class ArticleService {

    public function __construct(
        private RequestStack $requestStack,
        private ArticleRepository $articleRepo,
        private PaginatorInterface $paginator

    )
    {
        
    }

    public function getPaginatedArticles(?Category $category = null){

        $request = $this->requestStack->getMainRequest();
        $page = $request->query->getInt('page', 1);
        $limit = 2 ;

        $articlesQuery = $this->articleRepo->findForPagination($category);

        return $this->paginator->paginate($articlesQuery, $page, $limit);
    }
}