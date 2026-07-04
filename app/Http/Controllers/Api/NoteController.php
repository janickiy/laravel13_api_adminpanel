<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Notes\DeleteRequest;
use App\Http\Requests\Api\Notes\StoreRequest;
use App\Http\Requests\Api\Notes\UpdateRequest;
use App\Repositories\NoteRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
class NoteController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(
        private NoteRepository $noteRepository,
    ) {
        $this->middleware('auth:api');
    }


    #[OA\Get(
        path: '/api/v1/notes',
        summary: 'Список заметок текущего пользователя',
        description: 'Возвращает все заметки, принадлежащие авторизованному пользователю.',
        security: [['bearerAuth' => []]],
        tags: ['Notes'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Список заметок',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'user_id', type: 'integer', example: 10),
                            new OA\Property(property: 'title', type: 'string', example: 'План на день'),
                            new OA\Property(property: 'content', type: 'string', example: 'Подготовить отчет и проверить задачи.'),
                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-10T13:50:52.000000Z'),
                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-10T13:50:52.000000Z'),
                        ],
                        type: 'object'
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Пользователь не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $notes = $this->noteRepository->allForUser((int) Auth::id());

        return response()->json($notes);
    }


    #[OA\Get(
        path: '/api/v1/notes/{id}',
        summary: 'Просмотр заметки',
        description: 'Возвращает одну заметку текущего пользователя по идентификатору.',
        security: [['bearerAuth' => []]],
        tags: ['Notes'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Идентификатор заметки',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Заметка найдена',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'user_id', type: 'integer', example: 10),
                        new OA\Property(property: 'title', type: 'string', example: 'План на день'),
                        new OA\Property(property: 'content', type: 'string', example: 'Подготовить отчет и проверить задачи.'),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-10T13:50:52.000000Z'),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-10T13:50:52.000000Z'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Пользователь не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Заметка не найдена',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Note not found'),
                    ]
                )
            ),
        ]
    )]
    public function show(int $id): JsonResponse
    {
        $note = $this->noteRepository->findForUser($id, (int) Auth::id());

        if (!$note) {
            return response()->json(['message' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($note);
    }


    #[OA\Post(
        path: '/api/v1/notes/store',
        summary: 'Создание заметки',
        description: 'Создает новую заметку для авторизованного пользователя.',
        security: [['bearerAuth' => []]],
        tags: ['Notes'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'content'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'План на день'),
                    new OA\Property(property: 'content', type: 'string', example: 'Подготовить отчет и проверить задачи.'),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Заметка создана',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'user_id', type: 'integer', example: 10),
                        new OA\Property(property: 'title', type: 'string', example: 'План на день'),
                        new OA\Property(property: 'content', type: 'string', example: 'Подготовить отчет и проверить задачи.'),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-10T13:50:52.000000Z'),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-10T13:50:52.000000Z'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Пользователь не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The title field is required.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function store(StoreRequest $request): JsonResponse
    {
        $note = $this->noteRepository->createForUserFromArray($request->validated(), (int) Auth::id());

        return response()->json($note, Response::HTTP_CREATED);
    }


    #[OA\Put(
        path: '/api/v1/notes/update/{id}',
        summary: 'Обновление заметки',
        description: 'Обновляет заголовок и содержимое заметки текущего пользователя.',
        security: [['bearerAuth' => []]],
        tags: ['Notes'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Идентификатор заметки',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'content', 'id'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer', description: 'Идентификатор заметки. Сейчас требуется валидатором запроса.', example: 1),
                    new OA\Property(property: 'title', type: 'string', maxLength: 255, example: 'Обновленный план'),
                    new OA\Property(property: 'content', type: 'string', example: 'Обновить отчет и отправить результат.'),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Заметка обновлена',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'user_id', type: 'integer', example: 10),
                        new OA\Property(property: 'title', type: 'string', example: 'Обновленный план'),
                        new OA\Property(property: 'content', type: 'string', example: 'Обновить отчет и отправить результат.'),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-02-10T13:50:52.000000Z'),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-02-10T14:10:52.000000Z'),
                    ],
                    type: 'object',
                    nullable: true
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Пользователь не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The id field is required.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function update(int $id, UpdateRequest $request): JsonResponse
    {
        $note = $this->noteRepository->updateForUserFromArray($request->validated(), $id, (int) Auth::id());

        return response()->json($note);
    }


    #[OA\Delete(
        path: '/api/v1/notes/delete/{id}',
        summary: 'Удаление заметки',
        description: 'Удаляет заметку текущего пользователя по идентификатору.',
        security: [['bearerAuth' => []]],
        tags: ['Notes'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                description: 'Идентификатор заметки',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Заметка удалена',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Note deleted'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Пользователь не авторизован',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The id field is required.'),
                        new OA\Property(property: 'errors', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function destroy(DeleteRequest $request): JsonResponse
    {
        $this->noteRepository->deleteForUser($request->id, Auth::id());

        return response()->json(['message' => 'Note deleted']);
    }
}
