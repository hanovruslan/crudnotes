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
use function json_decode;

class NotesController extends AbstractController
{
    protected function getData(Request $request) : array {
        return json_decode($request->getContent(), true) ?? [];
    }

    /**
     * @Route("/notes", methods={"GET"})
     * @param Request $request
     * @param NotesService $notesService
     * @param UsersService $usersService
     * @return JsonResponse
     */
    public function list(
        Request $request,
        NotesService $notesService,
        UsersService $usersService
    ) : JsonResponse {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);

            return $this->json(
                $notesService->findByUser($iAm)
            );

        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes/available", methods={"GET"})
     * @param UsersService $usersService
     * @param NotesService $notesService
     * @param Request $request
     * @return JsonResponse
     */
    public function available(
        Request $request,
        UsersService $usersService,
        NotesService $notesService
    ) : JsonResponse {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);

            return $this->json(
                $notesService->findAvailableBy($iAm, $data['access'] ?? 'read')
            );
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes", methods={"POST"})
     * @param Request $request
     * @param NotesService $notesService
     * @param UsersService $usersService
     * @return RedirectResponse
     */
    public function create(
        Request $request,
        NotesService $notesService,
        UsersService $usersService
    ) : RedirectResponse {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = $notesService->create(
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
     * @param NotesService $notesService
     * @param UsersService $usersService
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function read(
        int $id,
        Request $request,
        NotesService $notesService,
        UsersService $usersService
    ) : JsonResponse {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = ($note = $notesService->findOneBy($iAm, $id))
                or ($note = $notesService->findOneAvailableBy($iAm, 'read', $id))
                or ($note = $notesService->findOneAvailableBy($iAm, 'write', $id))
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
     * @param int $id
     * @param Request $request
     * @param NotesService $notesService
     * @param UsersService $usersService
     * @return RedirectResponse
     */
    public function update(
        int $id,
        Request $request,
        NotesService $notesService,
        UsersService $usersService
    ) : RedirectResponse {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = ($note = $notesService->findOneBy($iAm, $id))
                or ($note = $notesService->findOneAvailableBy($iAm, 'write', $id))
                ? $note
                : null;
            if (!($note instanceof Note)) {
                throw new NotFoundHttpException();
            }

            $notesService->update(
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
     * @param int $id
     * @param Request $request
     * @param NotesService $notesService
     * @param UsersService $usersService
     * @return JsonResponse
     */
    public function delete(
        int $id,
        Request $request,
        NotesService $notesService,
        UsersService $usersService
    ) : JsonResponse {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = $notesService->findOneBy($iAm, $id);
            if ($note instanceof Note) {
                $notesService->delete($note);
            }

            return $this->json([]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes/{id}/share", methods={"PUT"})
     * @param int $id
     * @param Request $request
     * @param NotesService $notesService
     * @param UsersService $usersService
     * @param SharesService $sharesService
     * @return JsonResponse
     */
    public function share(
        int $id,
        Request $request,
        NotesService $notesService,
        UsersService $usersService,
        SharesService $sharesService
    ) : JsonResponse {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = $notesService->findOneBy($iAm, $id);
            $users = $usersService->findByUsernames($data['usernames'] ?? null);
            $sharesService->share($note, $data['access'] ?? 'read', $users);

            return $this->json([]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes/{id}/share", methods={"DELETE"})
     * @param int $id
     * @param Request $request
     * @param NotesService $notesService
     * @param UsersService $usersService
     * @param SharesService $sharesService
     * @return JsonResponse
     */
    public function deshare(
        int $id,
        Request $request,
        NotesService $notesService,
        UsersService $usersService,
        SharesService $sharesService
    ) : JsonResponse {
        try {
            $data = $this->getData($request);
            $iAm = $usersService->findOneByUsername($data['i_am'] ?? null);
            $note = $notesService->findOneBy($iAm, $id);
            $users = $usersService->findByUsernames($data['usernames'] ?? null);
            $sharesService->deshare($note, $data['access'] ?? 'read', $users);

            return $this->json([]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }
}
