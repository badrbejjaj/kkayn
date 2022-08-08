<?php

namespace App\Api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KkaynApi\Models\ApiResponse;
use KkaynApi\Models\Filters;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

abstract class AbstractApi
{
    /**
     * Concerned entity name.
     *
     * @var string
     */
    protected $entityName;

    protected $listModelName;

    /**
     * @var string
     */
    protected $slug;

    protected $repository;
    protected $entityManager;
    protected $security;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @internal
     * @required
     */
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    /**
     * Define if list filters are stored in memcached.
     *
     * @var boolean
     */
    protected $storeSearches = false;

    public function __construct() {}


    /**
     * @required
     * @param EntityManagerInterface $entityManager
     */

    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @required
     * @param Security $security
     */

    public function setSecurity(Security $security)
    {
        $this->security = $security;
    }

    /**
     *
     * @param  int $id  Id de l'entité à retourner
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return object
     *
     */
    protected function getEntity(int $id, &$responseCode, array &$responseHeaders)
    {
        $item = $this->repository->find($id);

        if (!isset($item)) {
            $responseCode = Response::HTTP_NOT_FOUND;
            return new ApiResponse(["code" => $responseCode, "message" => "Element not found"]);
        }

        return $this->entityConvert($item);
    }

    /**
     * Operation getById
     *
     * Trouve une entité par son Id
     *
     * @param  int $id  Id de l'entité à retourner (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     *
     */
    public function getById(int $id, &$responseCode, array &$responseHeaders)
    {
        return $this->getEntity($id, $responseCode, $responseHeaders);
    }

    /**
     *
     * @param  Kkayn\Models\Filters $filters   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return []
     *
     */
    protected function listEntities(Filters $filters, &$responseCode, array &$responseHeaders)
    {
        $items = $this->getEntitiesForFilters($filters);
        $items['items'] = $this->listConvert($items['items']);

        return $items;
    }

    /**
     *
     * @param  Kkayn\Models\Filters $filters   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return []
     *
     */
    protected function listEntitiesOptions(Filters $filters, &$responseCode, array &$responseHeaders)
    {
        $items = $this->getEntitiesForFilters($filters);
        $items['items'] = $this->listOptionsConvert($items['items']);
        return $items;
    }

    private function getEntitiesForFilters(Filters $filters)
    {
        if ($filters->getPage() !== null) {
            $num_items_page = $filters->getPage()->getItemsPerPage();
            $offset = $filters->getPage()->getOffset();
        } else {
            $num_items_page = null;
            $offset = null;
        }

        if ($filters->getSearch() !== null && $filters->getSearch()->getFields() !== null && $filters->getSearch()->getValues() != null) {
            $search = array_combine($filters->getSearch()->getFields(), $filters->getSearch()->getValues());
        } else {
            $search = null;
        }

        if ($filters->getSort() !== null && $filters->getSort()->getFields() !== null && $filters->getSort()->getValues() !== null) {
            $sort = array_combine($filters->getSort()->getFields(), $filters->getSort()->getValues());
        } else {
            $sort = null;
        }

        $allItems = $this->repository->getAll($num_items_page, $offset, $search, $sort);
        $totalCount = $this->repository->getCountAll($search);

        return [
            'items' => $allItems,
            'itemsCount' => $totalCount,
        ];
    }

    /**
     *
     * @param integer $id
     * @param object $model
     * @param int $responseCode
     * @param array $responseHeaders
     * @return void
     */
    protected function updateEntity(int $id, $model, &$responseCode, array &$responseHeaders)
    {
        $item = $this->repository->find($id);

        if (!$item) {
            $responseCode = Response::HTTP_NOT_FOUND;
            return new ApiResponse(["message" => "Cet élément n'existe pas."]);
        }

        $this->updateEntityWithModel($item, $model);

        return new ApiResponse(["message" => "Élément mis à jour."]);
    }

    /**
     *
     * @param object $model
     * @param int $responseCode
     * @param array $responseHeaders
     * @return void
     */
    protected function createEntity($model, &$responseCode, array &$responseHeaders)
    {
        $className = $this->getEntityClassName();
        $responseCode = Response::HTTP_CREATED;
        $entity = $this->updateEntityWithModel(new $className(), $model);

        return $this->entityConvert($entity);
    }



    /**
     *
     * @param integer $id
     * @param int $responseCode
     * @param array $responseHeaders
     * @return void
     */
    protected function deleteEntity(int $id, &$responseCode, array &$responseHeaders)
    {
        $item = $this->repository->find($id);

        if (!$item) {
            $responseCode = Response::HTTP_NOT_FOUND;
            return new ApiResponse(["message" => "Cet élément n'existe pas."]);
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();

        return new ApiResponse(["message" => "Élément supprimé."]);
    }

    /**
     *
     * @param object $entity
     * @param object $model
     * @return object
     */
    protected function updateEntityWithModel($entity, $model)
    {
        $entity = $this->modelConvert($model, $entity);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }

    /**
     * Operation getCurrentUser
     *
     * Retourne l'utilisateur connecté.
     */
    public function getLoggedInUser(): User
    {
        $user = $this->security->getUser();
        if (empty($user)) {
            throw new \Exception("No current user logged in");
        }

        return $user;
    }

    /**
     * Convert an entity to a model
     *
     * @param object $item an entity
     * @return object a model
     */
    abstract protected function entityConvert($item);

    /**
     * Convert a model to an entity.
     *
     * @param object $item a model
     * @param object $entity an entity
     * @return object a model
     */
    abstract protected function modelConvert($item, $entity);

    /**
     * Convert a list of entities to a list of list model
     *
     * @param array $items a list of entities
     * @return array a list of converted models
     * 
     * TEMPLATE
     * protected function listConvert($items)
     * {
     *   $listItems = [];
     *   foreach ($items as $item) {
     *       $listItems[] = [];
     *   }
     *
     *   return $listItems;
     *  } 
     */
    abstract protected function listConvert($items);

    

    /**
     * Convert
     *
     * @param SteelMaterialGrade[] $items
     * @return Option[]
     */
    protected function listConvertOption($items) {}

    /**
     * Get model className
     *
     * @return string
     */
    protected function getModelClassName(): string
    {
        return '\\Kkayn\\Models\\' . $this->entityName;
    }

    /**
     * Get list model className
     *
     * @return string
     */
    protected function getListModelClassName(): string
    {
        return '\\Kkayn\\Models\\' . (empty($this->listModelName) ? $this->entityName : $this->listModelName);
    }

    /**
     * Get main Model
     *
     * @return string
     */
    protected function getEntityClassName(): string
    {
        return '\\App\\Entity\\' . $this->entityName;
    }

    /**
     * Get slugified entity name.
     *
     * @return string
     */
    protected function getSlug(): string
    {
        return $this->slug ?: strtolower($this->entityName);
    }

}
