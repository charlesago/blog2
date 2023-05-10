<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/connect/profil', name: 'profil_user')]
    public function profil(Request $request, PostRepository $postRepository): Response
    {

        $posts= $postRepository->findAll();


        return $this->render('user/index.html.twig', [
            'posts'=>$posts,


        ]);
    }
}
