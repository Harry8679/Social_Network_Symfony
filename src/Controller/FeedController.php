<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FeedController extends AbstractController
{
    #[Route('/feed', name: 'app_feed')]
public function index(EntityManagerInterface $em): Response
{
    $this->denyAccessUnlessGranted('ROLE_USER');

    $posts = $em->getRepository(Post::class)
        ->findBy([], ['createdAt' => 'DESC']);

    $form = $this->createForm(PostType::class);

    return $this->render('feed/index.html.twig', [
        'posts' => $posts,
        'postForm' => $form->createView(),
    ]);
}

}