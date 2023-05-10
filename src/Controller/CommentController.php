<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/comment')]

class CommentController extends AbstractController
{
    #[Route('/', name: 'app_comment')]
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [


        ]);
    }

    #[Route('/create/{id}', name: 'create_comment')]
    public function create(Post $post, Request $request, EntityManagerInterface $manager): response
    {

        $comment = new Comment();
        $formCom = $this->createForm(CommentType::class, $comment);
        $formCom->handleRequest($request);
        if ($formCom->isSubmitted() && $formCom->isValid()) {

            $comment->setAuthor($this->getUser());
            $comment->setPost($post);
            $comment->setCreatedAt(new \DateTime());
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute("show_post", ['id' => $comment->getPost()->getId()]);
        }

    }

    #[Route('/delete/{id}', name: 'delete_comment')]
    public function delete(Comment $comment, EntityManagerInterface $manager):response
    {

        if($comment){
            $manager->remove($comment);
            $manager->flush();
        }

        return $this->redirectToRoute("show_post", ['id' => $comment->getPost()->getId()]);
    }

    #[Route('/update/{id}', name: 'update_comment')]
    public function update(Comment $comment,  Request $request, EntityManagerInterface $manager): response
    {

        $formCom = $this->createForm(CommentType::class, $comment);
        $formCom->handleRequest($request);
        if ($formCom->isSubmitted() && $formCom->isValid()) {


            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute("show_post", ['id' => $comment->getPost()->getId()]);
        }
        return $this->renderForm('comment/update.html.twig', [
                'formCom'=>$formCom

        ]);
    }
}
