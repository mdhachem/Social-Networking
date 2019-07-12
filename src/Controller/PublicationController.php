<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\PostLike;
use App\Form\CommentType;
use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\PostLikeRepository;
use App\Repository\PublicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\FollowRepository;

class PublicationController extends AbstractController
{
    /**
     * @Route("/", name="accueil" )
     */
    public function accueil(Request $request, ObjectManager $manager, PublicationRepository $repo, FollowRepository $repo_follow)
    {

        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);
        
        //Id de l'utilisateur
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userCurrent = $this->getUser();
        $articles = $repo->findPubForUser($user, $repo_follow, $userCurrent);




        if ($form->isSubmitted() && $form->isValid()) {


            $publication->setCreatedAt(new \DateTime())
                ->setUser($user);

            $manager->persist($publication);
            $manager->flush();

            return $this->redirectToRoute('accueil');
        }



        return $this->render('publication/accueil.html.twig', [
            'publicationForm' => $form->createView(),
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/publication/{id}",name ="publication_show")
     */

    public function show(Publication $publication, Request $request, ObjectManager $manager)
    {



        $comment = new Comment();
        $formComment = $this->createForm(CommentType::class, $comment);
        $formComment->handleRequest($request);


        //Id de l'utilisateur
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setUser($user)
                ->setPublication($publication);


            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('publication_show', ['id' => $publication->getId()]);
        }


        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
            'formComment' => $formComment->createView()
        ]);


    }


    /**
     * Permet de liker ou unlike un article
     *@Route("/post/{id}/like", name="post_like")
     * @param Post $post
     * @param ObjectManager $manager
     * @param PostLikeRepository $likerepo
     * @return Response
     */
    public function like(Publication $post, ObjectManager $manager, PostLikeRepository $likerepo) : Response
    {
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if ($post->isLikedByUser($user)) {
            $like = $likerepo->findOneBy([
                'post' => $post,
                'user' => $user
            ]);
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'likes' => $likerepo->count(['post' => $post])
            ], 200);
        }

        $like = new PostLike();
        $like->setPost($post)
            ->setUser($user);

        $manager->persist($like);
        $manager->flush();


        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajoute',
            'likes' => $likerepo->count(['post' => $post])
        ], 200);



    }


}
