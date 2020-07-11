<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user_list")
     */
    public function list(UserService $userService)
    {
        return $this->json(
            $userService->list()
        );
    }
}
