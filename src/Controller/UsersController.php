<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", methods={"GET"})
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return $this->json(
            $this->getUsersService()->list()
        );
    }

    /**
     * @Route("/users", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     * @throws BadRequestHttpException
     */
    public function create(Request $request) : RedirectResponse
    {
        try {
            $data = $this->getData($request);
            $user = $this->getUsersService()->create(
                $data['username'] ?? null,
                $data['fullname'] ?? null
            );
            return $this->redirectToRoute('user_read', [
                'id' => $user->getId(),
            ]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/users/{id}", methods={"GET"}, name="user_read", requirements={"id"="\d+"})
     * @param int $id
     * @return JsonResponse
     * @throws NotFoundHttpException
     */
    public function read(int $id) : JsonResponse
    {
        $user = $this->getUsersService()->read($id);
        if (!($user instanceof User)) {
            throw new NotFoundHttpException();
        }

        return $this->json([
            $user
        ]);
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(int $id, Request $request) : RedirectResponse
    {
        try {
            $data = $this->getData($request);
            $this->getUsersService()->update($id, $data['fullname'] ?? null);

            return $this->redirectToRoute('user_read', [
                'id' => $id,
            ]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $this->getUsersService()->delete($id);

        return $this->json([]);
    }
}
