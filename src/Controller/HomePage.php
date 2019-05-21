<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePage extends AbstractController
{
    /**
     * Matches / exactly
     *
     * @Route("/", name="homepage_show")
     */
    public function default()
    {
        return $this->render('homepage.html.twig', [
            'message' => 'Hello!'
        ]);
    }
}