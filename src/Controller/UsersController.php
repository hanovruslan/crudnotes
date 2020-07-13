<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    protected function getData(Request $request) : array {
        return \json_decode($request->getContent(), true);
    }

    /**
     * @Route("/users", methods={"GET"})
     * @param UsersService $userService
     * @return JsonResponse
     */
    public function list(UsersService $userService)
    {
        return $this->json(
            $userService->list()
        );
    }

    /**
     * @Route("/users", methods={"POST"})
     * @param UsersService $userService
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(UsersService $userService, Request $request) : RedirectResponse
    {
        try {
            $data = $this->getData($request);
            $user = $userService->create(
                $data['username'] ?? null,
                $data['fullname'] ?? null
            );
            return $this->redirectToRoute('user_read', [
                'id' => $user->getId(),
            ]);
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/users/{id}", methods={"GET"}, name="user_read", requirements={"id"="\d+"})
     * @param UsersService $userService
     * @param int $id
     * @return JsonResponse
     */
    public function read(UsersService $userService, int $id)
    {
        $user = $userService->read($id);
        if (!($user instanceof User)) {
            throw new NotFoundHttpException();
        }

        return $this->json([
            $user
        ]);
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     * @param UsersService $userService
     * @param int $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(UsersService $userService, int $id, Request $request) : RedirectResponse
    {
        try {
            $data = \json_decode($request->getContent(), true);
            $userService->update($id, $data['fullname'] ?? null);

            return $this->redirectToRoute('user_read', [
                'id' => $id,
            ]);
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     * @param UsersService $userService
     * @param int $id
     * @return JsonResponse
     */
    public function delete(UsersService $userService, int $id)
    {
        $userService->delete($id);

        return $this->json([]);
    }
}
