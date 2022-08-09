<?php
namespace App\Api;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use KkaynApi\Api\CategorieApiInterface;
use KkaynApi\Models\ApiResponse;
use KkaynApi\Models\Categorie as CategorieModel;
use KkaynApi\Models\Filters;
use KkaynApi\Models\ListCategorieResponse;
use Symfony\Component\HttpFoundation\Response;

class CategorieApi extends AbstractApi implements CategorieApiInterface {

    public function __construct(
        CategorieRepository $repository
    )
    {
        parent::__construct();

        $this->repository = $repository;
        $this->entityName = "Categorie";
    }

 // Abstract Functions

    /**
     * Convert an entity to a model
     *
     * @param Categorie $item an entity
     * @return object a model
     */
    protected function entityConvert($item) {

        $model = new CategorieModel();

        $model->setId($item->getId())
        ->setName($item->getName())
        ->setActive($item->isActive());

        return $model;
    }

    /**
     * Convert a model to an entity.
     *
     * @param CategorieModel $item a model
     * @param Categorie $entity an entity
     * @return Categorie an entity
     */
    protected function modelConvert($item, $entity) {
        $entity
            ->setName($item->getName())
            ->setActive($item->isActive() !== null ? $item->isActive() : true);

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
     * Retourne une liste des categories
     *
     * @param  Filters $filters   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ListCategorieResponse
     *
     */
    public function callList(Filters $filters, &$responseCode, array &$responseHeaders) {
        return new ListCategorieResponse($this->listEntities($filters, $responseCode, $responseHeaders));
    }

    /**
     * Operation create
     *
     * Ajouter un categorie
     *
     * @param  CategorieModel $categorie   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiResponse
     *
     */
    public function create(CategorieModel $categorie, &$responseCode, array &$responseHeaders) {

        $categorieEntity = new Categorie();
        $categorieEntity = $this->modelConvert($categorie, $categorieEntity);

        $this->entityManager->persist($categorieEntity);
        $this->entityManager->flush();


        return new ApiResponse(['message' => 'New Categorie Created Successfully']);
    }

    /**
     * Operation delete
     *
     * Supprime un categorie
     *
     * @param  int $id  Id categorie à supprimer (required)
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
     * Operation update
     *
     * Met à jour un categorie
     *
     * @param  int $id  id categorie à mettre à jour (required)
     * @param  KkaynApi\Models\Categorie $categorie   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiResponse
     *
     */
    public function update(int $id, CategorieModel $categorie, &$responseCode, array &$responseHeaders) {
        return $this->updateEntity($id, $categorie, $responseCode, $responseHeaders);
    }

}