<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\FollowRepository;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index(FollowRepository $follow_repo)
    {
        $user = $this->getUser();

        $following = $follow_repo->findBy(array('user' => $user));

        $following_array = array();
        foreach ($following as $follow) {
            $following_array[] = $follow->getFollowed();
        }


        return $this->render('message/index.html.twig', [
            'followed' => $following_array
        ]);
    }


    /**
     * @Route("/message/{id}", name="message_show")
     */
    public function msgShow(User $user, Request $request, MessageRepository $repo, FollowRepository $follow_repo, ObjectManager $manager)
    {
        $userCurrent = $this->getUser();

        // chercher les Amis
        $following = $follow_repo->findBy(array('user' => $userCurrent));

        $following_array = array();
        foreach ($following as $follow) {
            $following_array[] = $follow->getFollowed();
        }

        $msg_array = $repo->findMessage($userCurrent, $user->getId());

        $msg = new Message();
        $form = $this->createForm(MessageType::class, $msg);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $msg->setCreatedAt(new \DateTime());
            $msg->setEmitter($userCurrent);
            $msg->setReceivere($user);

            $manager->persist($msg);
            $manager->flush();

            return $this->redirectToRoute('message_show', array('id' => $user->getId()));

        }



        return $this->render('message/show.html.twig', [
            'form' => $form->createView(),
            'followed' => $following_array,
            'messages' => $msg_array
        ]);
    }
}
