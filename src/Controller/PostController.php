<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{

    private ?PostRepository $repository;

    public function __construct(ManagerRegistry $manager)
    {
        $this->repository = $manager->getRepository(Post::class);
    }

    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    #[Route('/post/add', name: 'add_post', methods:["GET", "POST"])]
    public function add(Request $request): Response
    {
        $post = new Post;
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // On récupère un objet UploadedFile du formulaire
            $picture = $form->get('picture')->getData();

            // On créé un nom unique pour chaque image en concaténant l'extension du fichier
            $pictureName = md5(uniqid()) . '.' . $picture->guessExtension();

            // On enregsitre le fichier d'image dans un dossier img
            $picture->move(
                $this->getParameter('upload_file'),// Le dossier dans lequel est déplacé le fichier
                $pictureName// Le nom du fichier
            );
            // On enregistre en BDD le nom de l'image
            $post->setPicture($pictureName)
                ->setCreatedAt(new DateTime());

            $this->repository->add($post, true);
            
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('post/add.html.twig', [
            'form' => $form
        ]);

    }

    // Ajouter un post

    // Modifier un post

    // Supprimmer un post
}
