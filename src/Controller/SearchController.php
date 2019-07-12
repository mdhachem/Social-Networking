<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="ajax_search")
     */
    public function searchBar(Request $request, UserRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();

        $requestString = $request->get('q');
        $entities = $repo->findEntitiesByString($requestString);



        return $this->render('search/search.html.twig', [
            'res' => $entities
        ]);
        //return new Response(json_encode($result));
    }


}
