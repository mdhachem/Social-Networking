<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\PublicationRepository;
use App\Repository\CommentRepository;
use App\Repository\PostLikeRepository;
use App\Repository\MessageRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $repoU, PublicationRepository $repoP, CommentRepository $repoC, PostLikeRepository $repoL, MessageRepository $repoM)
    {
        return $this->render('admin/index.html.twig', [
            'nb_u' => count($repoU->findAll()),
            'nb_p' => count($repoP->findAll()),
            'nb_co' => count($repoC->findAll()),
            'nb_like' => count($repoL->findAll()),
            'nb_m' => count($repoM->findAll())
        ]);
    }

    /**
     * @Route("/admin/showPub", name="admin_show_pub")
     */
    public function showPublication(PublicationRepository $repoP)
    {
        return $this->render('admin/pub.html.twig', [
            'publication' => $repoP->findAll()
        ]);
    }

    /**
     * @Route("/admin/showMessages", name="admin_show_message")
     */
    public function showMessage(MessageRepository $repoM)
    {
        return $this->render('admin/message.html.twig', [
            'message' => $repoM->findAll()
        ]);
    }

    /**
     * @Route("/admin/showLikes", name="admin_show_likes")
     */
    public function showLikes(PostLikeRepository $repoL)
    {
        return $this->render('admin/likes.html.twig', [
            'likes' => $repoL->findAll()
        ]);
    }



}
