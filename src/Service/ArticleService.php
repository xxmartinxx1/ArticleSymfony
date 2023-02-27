<?php
namespace App\Service;

use App\Repository\ArticleRepository;

class ArticleService
{
        private $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function findLatestArticle(): object
    {
        return  $this->articleRepository->findOneBy([], ['id' => 'DESC']);
    }
    public function findLastFourArticlesExceptLatest(int $latestArticleId): array
    {
        return $this->articleRepository->findLastFourArticlesExceptLatest($latestArticleId);
    }

    public function findArticlesByAuthor(int $id, int $perPage, int $page)
    {
        return $this->articleRepository->findArticlesByAuthor($id, $perPage, $page);
    }

    public function findLastThreeTopWriter(): array
    {
        return $this->articleRepository->findLastThreeTopWriter();
    }

    public function countArticlesByAuthor($authorId): int
    {
        return $this->articleRepository->count([
            'Relation' => $authorId
        ]);
    }







}