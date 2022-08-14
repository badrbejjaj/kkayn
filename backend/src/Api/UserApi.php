<?php
namespace App\Api;

use KkaynApi\Api\UserApiInterface;
use App\Api\AbstractApi;
use App\Entity\User;
use App\Repository\UserRepository;
use KkaynApi\Models\ApiResponse;
use KkaynApi\Models\ApiTokenResponse;
use KkaynApi\Models\Filters;
use KkaynApi\Models\ListUserResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use KkaynApi\Models\User as UserModel;
use KkaynApi\Models\UserRegister;
use Symfony\Component\HttpFoundation\Response;

class UserApi extends AbstractApi implements UserApiInterface {

    public function __construct(
        UserRepository $repository,        
        UserPasswordHasherInterface  $passwordEncoder
    )
    {
        parent::__construct();

        $this->repository = $repository;
        $this->entityName = "User";
        $this->passwordEncoder = $passwordEncoder;

    }

    // Abstract Functions

    /**
     * Convert an entity to a model
     *
     * @param User $item an entity
     * @return object a model
     */
    protected function entityConvert($item) {

        $model = new UserModel();

        $model->setId($item->getId())
        ->setFirstName($item->getFirstName())
        ->setLastName($item->getLastName())
        ->setUsername($item->getUsername())
        ->setEmail($item->getEmail())
        ->setUpdateDate($item->getUpdatedAt()->format("d/m/Y H:i:s"))
        ->setCreationDate($item->getCreatedAt()->format("d/m/Y H:i:s"));

        return $model;
    }

    /**
     * Convert a model to an entity.
     *
     * @param UserModel | UserRegisterModel $item a model
     * @param User $entity an entity
     * @return User an entity
     */
    protected function modelConvert($item, $entity) {
        $entity
            ->setUsername($item->getUsername())
            ->setFirstName($item->getFirstName())
            ->setLastName($item->getLastName());

        return $entity;
    }

    /**
     * Convert a User Register Model to an entity.
     *
     * @param UserRegisterModel $item a model
     * @param User $entity an entity
     * @return User an entity
     */
    protected function UserRegisterModelConvert($item, $entity) {


        $entity
            ->setUsername('')
            ->setGender('')
            ->setRoles(['ROLE_USER'])
            ->setFirstName($item->getFirstName())
            ->setLastName($item->getLastName())
            ->setEmail($item->getEmail());

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
     * Convert
     *
     * @param SteelMaterialGrade[] $items
     * @return Option[]
     */
    protected function listConvertOption($items) {}

    /**
     * Operation callList
     *
     * Retourne une liste des utilisateurs
     *
     * @param  KkaynApi\Models\Filters $filters   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ListUserResponse
     *
     */
    public function callList(Filters $filters, &$responseCode, array &$responseHeaders) {
        return new ListUserResponse($this->listEntities($filters, $responseCode, $responseHeaders));
    }

    /**
     * Operation delete
     *
     * Supprime une utilisateur
     *
     * @param  int $id  Id utilisateur à supprimer (required)
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
     * Retourne un utilisteur
     *
     * @param  int $id   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\User
     *
     */
    public function getById(int $id, &$responseCode, array &$responseHeaders) {
        $user = $this->repository->find($id);
        if (!$user) {
            $responseCode = Response::HTTP_NOT_FOUND;
            return new ApiResponse(['message' => 'User Not Found']);
        }
        
        return $this->entityConvert($user);
    }

    /**
     * Operation refreshUserToken
     *
     * Refresh connected user token
     *
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiTokenResponse
     *
     */
    public function refreshUserToken(&$responseCode, array &$responseHeaders) {
        /** @var User */
        $user = $this->getLoggedInUser();

        if (!$user) {
            return new ApiResponse(['message' => 'Aucune utilisateur trouver']);
        }

        $newToken = $this->createNewToken($user);

        return new ApiTokenResponse(['token' => $newToken]);
    }

    /**
     * Operation update
     *
     * Met à jour un utilisateur
     *
     * @param  int $id  id utilisateur à mettre à jour (required)
     * @param  KkaynApi\Models\User $user   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiResponse
     *
     */
    public function update(int $id, UserModel $user, &$responseCode, array &$responseHeaders) {
        $isUnique = $this->repository->isFieldUnique("username", $user->getUsername(), [], $id);
        
        if (!$isUnique) {
            $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            return new ApiResponse(["message" => "User already exist"]);
        }

        return $this->updateEntity($id, $user, $responseCode, $responseHeaders);
    }

    /**
     * Operation create
     *
     * Ajouter un utilisateur
     *
     * @param  KkaynApi\Models\UserRegister $userRegister   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return KkaynApi\Models\ApiTokenResponse
     *
     */
    public function create(UserRegister $user, &$responseCode, array &$responseHeaders) {
        $isUnique = $this->repository->isFieldUnique("email", $user->getEmail(), []);

        if (!$isUnique) {
            $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            return new ApiResponse(["message" => "User already exist"]);
        }

        $userEntity = new User();
        $userEntity = $this->UserRegisterModelConvert($user, $userEntity);
        $hashedPassword = $this->hashPassword($userEntity, $user->getPassword());
        $userEntity->setPassword($hashedPassword);

        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        // generate token 
        $token = $this->createNewToken($userEntity);

        return new ApiTokenResponse(['message' => 'Inscription réussie', 'token' => $token]);
    }

    /**
     * Operation getCurrentUser
     *
     * retourn l'utilisateur connecté.
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return FsjesApi\Models\User[]
     *
     */
    public function getCurrentUser(&$responseCode, array &$responseHeaders) {
        $user = $this->getLoggedInUser();
        if(!$user) {
            throw new \Exception("No currrent user logged in");
        }

        return $this->entityConvert($user);
    }

    private function hashPassword($user, $password) {
        return $this->passwordEncoder->hashPassword($user, $password);
    }

    private function createNewToken($user) {
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');
        return $jwtManager->create($user);
    }
}