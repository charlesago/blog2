<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/connect/post')]
class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(PostRepository $postRepository): Response
    {

        $posts = $postRepository->findAll();

        return $this->render('post/index.html.twig', [
            'posts'=>$posts
        ]);
    }
        #[Route('/show/{id}', name: 'show_post')]
        public function show(Post $post):response
        {

            $comment= new Comment();
            $formCom = $this->createForm(CommentType::class, $comment);
            return $this->renderForm('post/show.html.twig', [
                'post'=>$post,
                'formCom'=>$formCom
            ]);

        }
    #[Route('/delete/{id}', name: 'delete_post')]
    public function delete(Post $post, EntityManagerInterface $manager):response
    {

        if ($post){
            $manager->remove($post);
            $manager->flush();
        }



        return $this->redirectToRoute('app_post');

    }

    #[Route('/create/', name: 'create_post')]
    #[Route('/update/{id}', name: 'update_post')]
    public function create( Post $post=null,  Request $request, EntityManagerInterface $manager, ):response
    {
        $edit = false;

        if ($post){
            $edit=true;
        }
        if (!$edit){
            $post = new Post();
        }

        $formCreate = $this->createForm(PostType::class, $post);
        $formCreate->handleRequest($request);
        if ($formCreate->isSubmitted() && $formCreate->isValid()){

            if (!$edit){



            $post->setCreatedAt(new \DateTime());
                $post->setAuthor($this->getUser());

            }else{
                if ($post->getAuthor() !==$this->getUser()){
                    return $this->redirectToRoute('app_post');
                }
            }
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('app_post');
        }
        return $this->renderForm('post/create.html.twig', [
            'formCreate'=>$formCreate
        ]);

    }
    }
