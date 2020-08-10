<?php

namespace App\Controller;

use App\Service\UsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\Request;
use JsonException;
use function json_decode;

abstract class AbstractController extends BaseAbstractController
{
    /**
     * @var UsersService|null
     */
    protected ?UsersService $usersService;

    /**
     * @return UsersService
     */
    public function getUsersService(): ?UsersService
    {
        return $this->usersService;
    }

    /**
     * @param null|UsersService $usersService
     * @return static
     */
    public function setUsersService(UsersService $usersService = null)
    {
        $this->usersService = $usersService;
        return $this;
    }

    protected function getData(Request $request) : array {
        $result = [];
        try {
            $result = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            // do nothing
        }

        return $result;
    }
}
