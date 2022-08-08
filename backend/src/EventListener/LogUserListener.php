<?php 

namespace App\EventListener;

use App\Entity\LogUserInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class LogUserListener {
    /** @var Security $security */
    private $security;

    public function __construct(Security $security, UserRepository $userRespository)
    {
        $this->userRepository = $userRespository;
        $this->security = $security;
    }

    // add user Created after every persist event
    public function prePersist(LifecycleEventArgs $args) {
        $object = $args->getObject();
        if ($object instanceof LogUserInterface) {
            $userLoguer = $this->getUser()? : $this->getSuperAdminUser();
            if (!empty($userLoguer) && $userLoguer instanceof User) {
                $object->setUserCreated($userLoguer);
                $object->setUserUpdated($userLoguer);
            }
        }
    }
    // update userUpdated field after every update event
    public function preUpdate(LifecycleEventArgs $args) {
        $object = $args->getObject();
        if ($object instanceof LogUserInterface) {
            $userLoguer = $this->getUser()? : $this->getSuperAdminUser();
            if (!empty($userLoguer) && $userLoguer instanceof User) {
                $object->setUserUpdated($userLoguer);
            }
        }
    }

    public function getUser() {
        return $this->security->getUser();
    }

    public function getSuperAdminUser() {
        return $this->userRepository->findOneBy(['username' => 'superadmin']);
    }
}