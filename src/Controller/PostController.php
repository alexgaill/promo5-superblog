<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostType;
use App\Services\File\SaveFile;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
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

    #[Route('/post', name: 'app_post', methods:['GET'])]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $posts = $this->repository->search($request->query->get('search'));
        $pagination = $paginator->paginate(
            $posts,
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('post/index.html.twig', [
            'pagination' => $pagination
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

            $pictureName = (new SaveFile)->saveUploadedFile($picture, $this->getParameter('upload_file'));
            // On enregistre en BDD le nom de l'image
            $post->setPicture($pictureName)
                ->setCreatedAt(new DateTime());

            $this->repository->add($post, true);
            
            $this->addFlash('success', "L'article ". $post->getTitle() ." a bien été ajouté.");
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('post/add.html.twig', [
            'form' => $form
        ]);

    }

    #[Route('/post/{id}/update', name: 'update_post', methods:['GET', 'POST'], requirements:['id' => "\d+"])]
    public function update(Post $post, Request $request): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si on a une ancienne image on la stocke
            $oldPicture = $post->getPicture() ? $post->getPicture() : null;
            // On stocke la nouvelle image reçue si on en a une
            $picture = $form->get('picture')->getData() ? $form->get('picture')->getData() : null;

            // Une ancienne image & pas de nouvelle
            // Une ancienne image et une nouvelle
            // Pas d'ancienne image et une nouvelle
            if ($picture) {
                $pictureName = (new SaveFile)->saveUploadedFile($picture, $this->getParameter('upload_file'));
                $post->setPicture($pictureName);
                if ($oldPicture) {
                    // On supprime l'ancienne image
                    try {
                        unlink($this->getParameter('upload_file').'/'. $oldPicture);
                    } catch (\Throwable $th) {
                        $this->addFlash('warning', "L'ancienne image '$oldPicture' n'a pas été supprimée!");
                    }
                }
            }
            //  elseif ($oldPicture) {
            //     $post->setPicture($oldPicture);
            // }
            $this->repository->add($post, true);
            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm('post/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/post/{id}/delete', name:'delete_post', methods:['GET'], requirements:['id' => "\d+"])]
    public function delete(Post $post):Response
    {
        $this->repository->remove($post, true);
        $this->addFlash('success', "L'article '". $post->getTitle() ."' a bien été supprimé");
        return $this->redirectToRoute('app_home');
    }
}
