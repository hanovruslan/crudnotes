<?php

namespace App\Controller;

use App\Entity\Note;
use App\Service\NotesService;
use App\Service\SharesService;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class NotesController extends AbstractController
{
    /**
     * @var NotesService|null
     */
    protected ?NotesService $notesService;

    /**
     * @var SharesService|null
     */
    protected ?SharesService $sharesService;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(
        Request $request
    ) : JsonResponse
    {
        try {
            $data = $this->getData($request);
            return $this->json(
                $this->getNotesService()->findByUsername($data['i_am'] ?? null)
            );
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function available(
        Request $request
    ) : JsonResponse {
        try {
            $data = $this->getData($request);

            return $this->json(
                $this->getNotesService()->findAvailableBy($data['i_am'] ?? null, $data['access'] ?? 'read')
            );
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function create(
        Request $request
    ) : RedirectResponse {
        try {
            $data = $this->getData($request);
            $iAm = $this->getUsersService()->findOneByUsername($data['i_am'] ?? null);
            $note = $this->getNotesService()->create(
                $data['title'] ?? null,
                $data['body'] ?? null,
                $iAm
            );
            return $this->redirectToRoute('notes_read', [
                'id' => $note->getId(),
            ]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function read(
        int $id,
        Request $request
    ) : JsonResponse {
        try {
            $data = $this->getData($request);
            $note = $this->getNotesService()->findOneByIdAndUsernameAndAccess($id, $data['i_am'] ?? null);
            if (!($note instanceof Note)) {
                throw new NotFoundHttpException();
            }

            return $this->json([
                $note
            ]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(
        int $id,
        Request $request
    ) : RedirectResponse {
        try {
            $data = $this->getData($request);
            $note = $this->getNotesService()->findOneByIdAndUsernameAndAccess($id, $data['i_am'] ?? null, 'write');
            if (!($note instanceof Note)) {
                throw new NotFoundHttpException();
            }
            $this->getNotesService()->update(
                $note,
                $data['title'] ?? null,
                $data['body'] ?? null
            );

            return $this->redirectToRoute('notes_read', [
                'id' => $id,
            ]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(
        int $id,
        Request $request
    ) : JsonResponse {
        $result = $this->json([]);
        try {
            $data = $this->getData($request);
            $note = $this->getNotesService()->findOneByIdAndUsername($id, $data['i_am'] ?? null);
            if ($note instanceof Note) {
                $this->getNotesService()->delete($note);
            }
        } catch (NoResultException $exception) {
            // do nothing
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return $result;
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function share(
        int $id,
        Request $request
    ) : JsonResponse {
        try {
            [$access, $usernames] = $this->prepareShareable($request, $id);
            $this->getSharesService()->share($id, $usernames, $access);

            return $this->json([]);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function deshare(
        int $id,
        Request $request
    ) : JsonResponse {
        $result = $this->json([]);
        try {
            [$access, $usernames] = $this->prepareShareable($request, $id);
            $this->getSharesService()->deshare($id, $usernames, $access);
        } catch (Throwable $exception) {
            throw new BadRequestHttpException($exception->getMessage());
        }

        return $result;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    protected function prepareShareable(
        Request $request,
        int $id
    ): array {
        $data = $this->getData($request);
        if (!$this->getNotesService()->hasOneByIdAndUsername($id, $data['i_am'] ?? null)) {
            throw new NotFoundHttpException();
        }
        $data['usernames'] = array_diff($data['usernames'], [$data['i_am']]);
        $usernames = $this->getUsersService()->filterUsernames($data['usernames'] ?? null);
        return [
            $data['access'] ?? 'read',
            $usernames,
        ];
    }


    /**
     * @return NotesService|null
     */
    public function getNotesService(): ?NotesService
    {
        return $this->notesService;
    }

    /**
     * @param NotesService|null $notesService
     * @return static
     */
    public function setNotesService(?NotesService $notesService = null)
    {
        $this->notesService = $notesService;
        return $this;
    }

    /**
     * @return SharesService|null
     */
    public function getSharesService(): ?SharesService
    {
        return $this->sharesService;
    }

    /**
     * @param SharesService|null $sharesService
     * @return static
     */
    public function setSharesService(?SharesService $sharesService = null)
    {
        $this->sharesService = $sharesService;
        return $this;
    }
}
