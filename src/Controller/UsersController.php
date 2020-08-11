<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class UsersController extends AbstractController
{
    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return $this->json(
            $this->getUsersService()->list()
        );
    }

    /**
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
            return $this->redirectToRoute('users_read', [
                'id' => $user->getId(),
            ]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
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
     * @param int $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(int $id, Request $request) : RedirectResponse
    {
        try {
            $data = $this->getData($request);
            $this->getUsersService()->update($id, $data['fullname'] ?? null);

            return $this->redirectToRoute('users_read', [
                'id' => $id,
            ]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $this->getUsersService()->delete($id);

        return $this->json([]);
    }
}
