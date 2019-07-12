<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Follow;
use App\Repository\FollowRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FollowController extends AbstractController
{
    /**
     * @Route("/follow/{id}", name="follow")
     */
    public function index(User $user, ObjectManager $manager, FollowRepository $repo)
    {
        $userCurrent = $this->getUser();

        if (!$userCurrent) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if ($userCurrent->isFollowByUser($user)) {
            $follow = $repo->findOneBy([
                'user' => $userCurrent,
                'followed' => $user
            ]);
            $manager->remove($follow);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Follow bien supprimé',
            ], 200);
        }

        $follow = new Follow();
        $follow->setUser($userCurrent)
            ->setFollowed($user);

        $manager->persist($follow);
        $manager->flush();


        return $this->json([
            'code' => 200,
            'message' => 'Follow bien ajoute',
        ], 200);

    }
}
