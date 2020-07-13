<?php

namespace App\Controller;

use App\Entity\Note;
use App\Service\NotesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class NotesController extends AbstractController
{
    protected function getData(Request $request) : array {
        return \json_decode($request->getContent(), true);
    }

    /**
     * @Route("/notes", methods={"GET"})
     * @param NotesService $noteService
     * @return JsonResponse
     */
    public function list(NotesService $noteService)
    {
        return $this->json(
            $noteService->list()
        );
    }

    /**
     * @Route("/notes", methods={"POST"})
     * @param NotesService $noteService
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(NotesService $noteService, Request $request) : RedirectResponse
    {
        try {
            $data = $this->getData($request);
            $note = $noteService->create(
                $data['username'] ?? null,
                $data['title'] ?? null,
                $data['body'] ?? null
            );
            return $this->redirectToRoute('note_read', [
                'id' => $note->getId(),
            ]);
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes/{id}", methods={"GET"}, name="note_read", requirements={"id"="\d+"})
     * @param NotesService $noteService
     * @param int $id
     * @return JsonResponse
     */
    public function read(NotesService $noteService, int $id)
    {
        $note = $noteService->read($id);
        if (!($note instanceof Note)) {
            throw new NotFoundHttpException();
        }

        return $this->json([
            $note
        ]);
    }

    /**
     * @Route("/notes/{id}", methods={"PUT"})
     * @param NotesService $noteService
     * @param int $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(NotesService $noteService, int $id, Request $request) : RedirectResponse
    {
        try {
            $data = \json_decode($request->getContent(), true);
            $noteService->update($id,
                $data['username'] ?? null,
                $data['title'] ?? null,
                $data['body'] ?? null
            );

            return $this->redirectToRoute('note_read', [
                'id' => $id,
            ]);
        } catch (\Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @Route("/notes/{username}/{id}", methods={"DELETE"})
     * @param NotesService $noteService
     * @param string $username
     * @param int $id
     * @return JsonResponse
     */
    public function delete(NotesService $noteService, string $username, int $id)
    {
        $noteService->deleteWithUsername($id, $username);

        return $this->json([]);
    }
}
