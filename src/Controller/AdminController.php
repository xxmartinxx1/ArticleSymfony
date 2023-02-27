<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleAddType;
use App\Form\ArticleEditType;
use App\Service\ArticleService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;





class AdminController extends AbstractController
{


    /* To jest konstruktor klasy. Przyjmuje obiekt EntityManagerInterface jako argument i przypisuje go do
      prywatnej zmiennej $em dostępnej z poziomu obiektu $this->em->  */
    private $em;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $globalParameters)
    {
        $this->em = $em;
        $this->globalParameters = $globalParameters;


    }

    #[Route('/', name: 'app_admin_panel')]
    public function index(ArticleService $articleService): Response
    {
            //Pobieranie ostatni dodany Artykułu z Service
            $latestArticle = $articleService->findLatestArticle();
            $latestArticleIdOrNull = $latestArticle ? $latestArticle->getId() : null;
            $lastFourArticles = $articleService->findLastFourArticlesExceptLatest($latestArticleIdOrNull);
            $lastThreeTopWriter = $articleService->findLastThreeTopWriter();

            //Top 3 autorów w ostatnim tygodniu
        return $this->render('admin/index.html.twig', [
            'lastArticle' => $latestArticle,
            'lastFourArticles' => $lastFourArticles,
            'lastThreeArticles' => $lastThreeTopWriter
        ]);
    }

    #[Route('/admin/show/article/{id}', name: 'app_admin_show_article')]
    public function showArticle(Article $article): Response
    {

        return $this->render('admin/article_show.html.twig', [
            'article' => $article,
        ]);
    }



    #[Route('/admin/add/article', name: 'app_admin_add_article', methods: ['POST', 'GET'])]
    public function addArticle(Request $request): Response
    {
        //Tworzymy nowy pusty obiekt
        $article = new Article();

        //Tworzymy formularz API Endpoint
        $form = $this->createForm(ArticleAddType::class, $article);

        //Tworzymy metody które są używane do przetwarzania danych formularza
        $form->handleRequest($request);

        //Automatyczna weryfikacja tokenu CSRF jest wykonywana przez HttpFoundation->RequestHandler */
        if ($form->isSubmitted() && $form->isValid()) {

            //Zapisujemy obiekt do bazy
            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash('success', 'Artykuł został dodany');
            return $this->redirectToRoute('app_admin_panel', ['id' => $article->getId()]);
        }

        return $this->render('admin/article_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/edit/article/{id}', name: 'app_admin_edit_article', methods: ['POST', 'GET'])]
    public function editArticle(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleEditType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($article);
            $this->em->flush();
            $this->addFlash('success', 'Artykuł został edytowany.');
            return $this->redirectToRoute('app_admin_panel', ['id' => $article->getId()]);
        }

        return $this->render('admin/article_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/show/author/article/{id}', name: 'app_author_articles', methods: ['GET'])]
    public function listArticlesByAuthor(ArticleService $articleService, User $author, Request $request, $page = 1, $perPage = 2): Response
    {
        $page = $request->query->getInt('page', 1);
        (int) $perPage = $this->globalParameters->get('NUMBER_OF_RECORDS_AUTHOR_PAGE'); //I Never know if it's necessary, but it will never be bad Idea

        //Wysyłam id authora  Serwisu który nastepnie wywołuje metodę i pobiera dane z bazy dane
        $lastFourArticleByAuthor = $articleService->findArticlesByAuthor($author->getId(), $perPage, ($page - 1) * $perPage);
        $allNumberOfArticles = $author->getArtList()->count(); //Pobieramy z encji liczbe wszystkich publikacji aby wyznaczyc maxPage
        $maxPages = ceil($allNumberOfArticles / $perPage); //Ilosc rekordów przez perpage z globala


        return $this->render('article_list_by_author.html.twig', [
            'lastArticleByAuthor' => $lastFourArticleByAuthor,
            'user' => $author,
            'maxPages' => $maxPages-1,
            'page' => $page,


        ]);
    }




}
