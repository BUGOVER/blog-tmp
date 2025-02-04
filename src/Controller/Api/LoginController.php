<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    #[Route(name: 'api_login', path: '/api/auth/login', methods: ['POST'])]
    public function __invoke(#[CurrentUser] ?User $user): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to a system',
            'path' => 'src/Controller/Api/LoginController.php',
        ]);
    }
}
