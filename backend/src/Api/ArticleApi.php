<?php
namespace App\Api;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use KkaynApi\Api\ArticleApiInterface;
use KkaynApi\Models\ApiResponse;
use KkaynApi\Models\Article as ArticleModel;
use KkaynApi\Models\Filters;
use KkaynApi\Models\ListArticleResponse;
use Symfony\Component\HttpFoundation\Response;

class ArticleApi extends AbstractApi implements ArticleApiInterface {

    public function __construct(
        ArticleRepository $repository,
        CategorieRepository $categorieRepo
    )
    {
        parent::__construct();

        $this->repository = $repository;
        $this->entityName = "Article";
        $this->categorieRepo = $categorieRepo;
    }

 // Abstract Functions

    /**
     * Convert an entity to a model
     *
     * @param Article $item an entity
     * @return object a model
     */
    protected function entityConvert($item) {

        $model = new ArticleModel();

        $model->setId($item->getId())
        ->setTitle($item->getTitle())
        ->setContent($item->getContent())
        ->setReadTime($item->getReadTime())
        ->setActive($item->isActive());

        if ($item->getCategorie()) {
            $categorieModel = CategorieApi::entityStaticConvert($item->getCategorie());
            $model->setCategorie($categorieModel);
        }

        return $model;
    }

    /**
     * Convert a model to an entity.
     *
     * @param ArticleModel $item a model
     * @param Article $entity an entity
     * @return Article an entity
     */
    protected function modelConvert($item, $entity) {
        $entity
            ->setTitle($item->getTitle())
            ->setContent($item->getContent())
            ->setReadTime($item->getReadTime())
            ->setActive($item->isActive() !== null ? $item->isActive() : true);

        if ($item->getCategorie()) {
            $categorieId = $item->getCategorie()->getId();
            $entity->setCategorie($categorieId ? $this->categorieRepo->find($categorieId) : null);
        }

        return $entity;
    }

    /**
     * Convert a list of entities to a list of list model
     *
     * @param array $items a list of entities
     * @return array a list of converted models
     * 
     */
    protected function listConvert($items)
    {
      $listItems = [];
      foreach ($items as $item) {
          $listItems[] = $this->entityConvert($item);
      }
        return $listItems;
     }
    

    /**
     * Operation callList
     *
     * Retourne une liste des articles
     *
     * @param  Filters $filters   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ListArticleResponse
     *
     */
    public function callList(Filters $filters, &$responseCode, array &$responseHeaders) {
        return new ListArticleResponse($this->listEntities($filters, $responseCode, $responseHeaders));
    }

    /**
     * Operation create
     *
     * Ajouter un article
     *
     * @param  ArticleModel $article   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiResponse
     *
     */
    public function create(ArticleModel $article, &$responseCode, array &$responseHeaders) {

        $articleEntity = new Article();
        $articleEntity = $this->modelConvert($article, $articleEntity);

        $this->entityManager->persist($articleEntity);
        $this->entityManager->flush();


        return new ApiResponse(['message' => 'New Article Created Successfully']);
    }

    /**
     * Operation delete
     *
     * Supprime un article
     *
     * @param  int $id  Id article à supprimer (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiResponse
     *
     */
    public function delete(int $id, &$responseCode, array &$responseHeaders) {
        return $this->deleteEntity($id, $responseCode, $responseHeaders);
    }

    /**
     * Operation getById
     *
     * Retourne un article
     *
     * @param  int $id   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\Article
     *
     */
    public function getById(int $id, &$responseCode, array &$responseHeaders) {
        $article = $this->repository->find($id);

        if (!$article) {
            $responseCode = Response::HTTP_NOT_FOUND;
            return new ApiResponse(['message' => 'Article Not Found']);
        }

        return $this->entityConvert($article);
    }

    /**
     * Operation update
     *
     * Met à jour un article
     *
     * @param  int $id  id article à mettre à jour (required)
     * @param  KkaynApi\Models\Article $article   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiResponse
     *
     */
    public function update(int $id, ArticleModel $article, &$responseCode, array &$responseHeaders) {
        return $this->updateEntity($id, $article, $responseCode, $responseHeaders);
    }

}