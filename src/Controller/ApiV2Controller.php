<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\ApiV2\GithubApiV2;

class ApiV2Controller extends AbstractController
{
    /**
     * Matches /api/v2/endpoint/* exactly
     *
     * @Route("/api/v2/{endpoint}/{keyword}", name="api_v2_get")
     */
    public function handle($endpoint, $keyword)
    {
        $api = new GithubApiV2;
        
        $data = $api->client($endpoint, $keyword);

        return new Response(
            $data['content'],
            $data['status'],
            $data['headers']
        );
    }
}