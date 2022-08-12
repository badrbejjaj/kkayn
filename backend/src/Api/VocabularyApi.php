<?php
namespace App\Api;

use App\Entity\Vocabulary;
use App\Repository\VocabularyRepository;
use KkaynApi\Api\VocabularyApiInterface;
use KkaynApi\Models\ApiResponse;
use KkaynApi\Models\Vocabulary as VocabularyModel;
use KkaynApi\Models\Filters;
use KkaynApi\Models\ListVocabularyResponse;
use Symfony\Component\HttpFoundation\Response;

class VocabularyApi extends AbstractApi implements VocabularyApiInterface {

    public function __construct(
        VocabularyRepository $repository
    )
    {
        parent::__construct();

        $this->repository = $repository;
        $this->entityName = "Vocabulary";
    }

 // Abstract Functions

    /**
     * Convert an entity to a model
     *
     * @param Vocabulary $item an entity
     * @return object a model
     */
    protected function entityConvert($item) {

        $model = new VocabularyModel();

        $model->setId($item->getId())
        ->setName($item->getName())
        ->setTitle($item->getTitle())
        ->setDescription($item->getDescription())
        ->setActive($item->isActive());

        return $model;
    }

    /**
     * Convert a model to an entity.
     *
     * @param VocabularyModel $item a model
     * @param Vocabulary $entity an entity
     * @return Vocabulary an entity
     */
    protected function modelConvert($item, $entity) {
        $entity
            ->setName($item->getName())
            ->setTitle($item->getTitle())
            ->setDescription($item->getDescription())
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
     * Retourne une liste des vocabulaire
     *
     * @param  Filters $filters   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ListVocabularyResponse
     *
     */
    public function callList(Filters $filters, &$responseCode, array &$responseHeaders) {
        return new ListVocabularyResponse($this->listEntities($filters, $responseCode, $responseHeaders));
    }

    /**
     * Operation create
     *
     * Ajouter un vocabulary
     *
     * @param  VocabularyModel $vocabulary   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiResponse
     *
     */
    public function create(VocabularyModel $vocabulary, &$responseCode, array &$responseHeaders) {

        $vocabularyEntity = new Vocabulary();
        $vocabularyEntity = $this->modelConvert($vocabulary, $vocabularyEntity);

        $this->entityManager->persist($vocabularyEntity);
        $this->entityManager->flush();


        return new ApiResponse(['message' => 'New Vocabulary Created Successfully']);
    }

    /**
     * Operation delete
     *
     * Supprime un vocabulary
     *
     * @param  int $id  Id vocabulary à supprimer (required)
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
     * Met à jour un vocabulary
     *
     * @param  int $id  id vocabulary à mettre à jour (required)
     * @param  KkaynApi\Models\Vocabulary $vocabulary   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiResponse
     *
     */
    public function update(int $id, VocabularyModel $vocabulary, &$responseCode, array &$responseHeaders) {
        return $this->updateEntity($id, $vocabulary, $responseCode, $responseHeaders);
    }

}