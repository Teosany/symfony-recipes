<?php

namespace App\Controller;

use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageSizeController extends AbstractController
{
    #[Route('/image/{path}', name: 'app_image_size')]
    public function index(Request $request, string $path): Response
    {
        $projectDir = $this->getParameter('kernel.project_dir');

        $server = ServerFactory::create([
            'cache' => $projectDir . '/var/images',
            'source' => $projectDir . '/public/images/recipes/',
            'response' => new SymfonyResponseFactory($request),
            'base_url' => 'image',
        ]);
        return $server->getImageResponse($path, $request->query->all());
    }
}
