<?php

namespace App\Http\Controllers\Api;

use App\DTO\Notes\NoteData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Notes\StoreRequest;
use App\Http\Requests\Api\Notes\UpdateRequest;
use App\Models\Notes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class NoteController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function index(): JsonResponse
    {
        $notes = Notes::where('user_id', Auth::id())->get();

        return response()->json($notes);
    }


    public function show(int $id): JsonResponse
    {
        $note = Notes::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$note) {
            return response()->json(['message' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($note);
    }


    public function store(StoreRequest $request): JsonResponse
    {
        $data = NoteData::fromArray($request->validated());
        $note = Notes::create(array_merge($data->toArray(), ['user_id' => Auth::id()]));

        return response()->json($note, Response::HTTP_CREATED);
    }


    public function update(int $id, UpdateRequest $request): JsonResponse
    {
        $data = NoteData::fromArray($request->validated(), $id);
        $note = Notes::where('id', $data->id())->where('user_id', Auth::id())->first();

        if (!$note) {
            return response()->json(['message' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }

        $note->fill($data->toArray());
        $note->save();

        return response()->json($note);
    }


    public function destroy(int $id): JsonResponse
    {
        $deleted = Notes::where('id', $id)->where('user_id', Auth::id())->delete();

        if (!$deleted) {
            return response()->json(['message' => 'Note not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['message' => 'Note deleted']);
    }
}
