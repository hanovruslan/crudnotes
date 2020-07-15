<?php

namespace App\Controller;

use App\Entity\Note;
use App\Service\NotesService;
use App\Service\SharesService;
use App\Service\UsersService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class NotesController extends AbstractController
{
    protected function getData(Request $request) : array {
        return \json_decode($request->getContent(), true);
    }

    /**
     * @Route("/notes", methods={"GET"})
     * @param NotesService $noteService
     * @param UsersService $usersService
     * @param Request $request
     * @return JsonResponse
     */
    public function list(NotesService $noteService, UsersService $usersService, Request $request)
    {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);

            return $this->json(
                $noteService->listByUser($iAm)
            );

        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes/available", methods={"GET"})
     * @param SharesService $sharesService
     * @param UsersService $usersService
     * @param Request $request
     * @return JsonResponse
     */
    public function available(
        SharesService $sharesService,
        UsersService $usersService,
        Request $request
    ) {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);

            return $this->json(
                $sharesService->findByUserAndAccess($iAm, $data['access'] ?? 'read')
            );
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes", methods={"POST"})
     * @param NotesService $noteService
     * @param UsersService $usersService
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(NotesService $noteService, UsersService $usersService, Request $request) : RedirectResponse
    {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = $noteService->create(
                $data['title'] ?? null,
                $data['body'] ?? null,
                $iAm
            );
            return $this->redirectToRoute('note_read', [
                'id' => $note->getId(),
            ]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes/{id}", methods={"GET"}, name="note_read", requirements={"id"="\d+"})
     * @param NotesService $noteService
     * @param UsersService $usersService
     * @param SharesService $sharesService
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function read(
        NotesService $noteService,
        UsersService $usersService,
        SharesService $sharesService,
        Request $request,
        int $id
    ) {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = (($note = $noteService->findOneBy($id, $iAm)) instanceof Note)
                or ($note = $sharesService->findOneByUserAndAccess($id, $iAm))
                ? $note
                : null;
            if (!($note instanceof Note)) {
                throw new NotFoundHttpException();
            }

            return $this->json([
                $note
            ]);
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

    }

    /**
     * @Route("/notes/{id}", methods={"PUT"})
     * @param NotesService $noteService
     * @param UsersService $usersService
     * @param SharesService $sharesService
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(
        NotesService $noteService,
        UsersService $usersService,
        SharesService $sharesService,
        Request $request,
        int $id
    ) : RedirectResponse {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = (($note = $noteService->findOneBy($id, $iAm)) instanceof Note)
            or ($note = $sharesService->findOneByUserAndAccess($id, $iAm, 'write'))
                ? $note
                : null;
            if (!($note instanceof Note)) {
                throw new NotFoundHttpException();
            }
            $noteService->update(
                $note,
                $data['title'] ?? null,
                $data['body'] ?? null
            );

            return $this->redirectToRoute('note_read', [
                'id' => $id,
            ]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes/{id}", methods={"DELETE"})
     * @param NotesService $noteService
     * @param UsersService $usersService
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(NotesService $noteService, UsersService $usersService, int $id, Request $request)
    {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = $noteService->findOneBy($id, $iAm);
            if ($note instanceof Note) {
                $noteService->delete($note);
            }


            return $this->json([]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes/{id}/share", methods={"POST"})
     * @param int $id
     * @return JsonResponse
     */
    public function share(int $id)
    {
        try {
            return $this->json([]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }
    /**
     * @Route("/notes/{id}/share", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function deshare(int $id)
    {
        try {
            return $this->json([]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }
}
