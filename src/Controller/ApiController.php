<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Api\Api;

class ApiController extends AbstractController
{
    /**
     * Matches /api/v1/endpoint/* exactly
     *
     * @Route("/api/v1/{endpoint}/{keyword}", name="api_v1_get")
     */
    public function score($endpoint, $keyword)
    {
        $api = new Api;
        
        $data = $api->client($endpoint, $keyword);

        return new Response(
            $data
        );
    }
}