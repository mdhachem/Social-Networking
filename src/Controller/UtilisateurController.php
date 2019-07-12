<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @Route("/user")
 */
class UtilisateurController extends AbstractController
{

    /**
     * @Route("/{id}", name="user_show")
     */
    public function show(User $user, Request $request, ObjectManager $manager)
    {

        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        $userCurrent = $this->get('security.token_storage')->getToken()->getUser();

        if ($form->isSubmitted() && $form->isValid()) {


            $publication->setCreatedAt(new \DateTime())
                ->setUser($userCurrent);

            $manager->persist($publication);
            $manager->flush();

            return $this->redirectToRoute('user_show', array('id' => $userCurrent->getId()));
        }


        return $this->render('utilisateur/show.html.twig', [
            'user' => $user,
            'publicationForm' => $form->createView(),


        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user) : Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

}
