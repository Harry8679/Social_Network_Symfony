<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/post/create', name: 'app_post_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        FileUploader $fileUploader
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $post = new Post();
        $post->setAuthor($this->getUser());

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $filename = $fileUploader->upload($imageFile);
                $post->setImage($filename);
            }

            $em->persist($post);
            $em->flush();
        }

        return $this->redirectToRoute('app_feed');
    }
}
