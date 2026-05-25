<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\AdminData;
use App\Models\Admin;
use App\Http\Requests\Admin\Admin\StoreRequest;
use App\Http\Requests\Admin\Admin\EditRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class AdminController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('admin.admin.index')->with('title', 'Пользователи');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $options = Admin::$role_name;

        return view('admin.admin.create_edit', compact('options'))->with('title', 'Добавить пользователя');
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = AdminData::fromArray($request->validated());
        $payload = $data->toArray();
        $payload['password'] = Hash::make((string) $data->password);

        Admin::create($payload);

        return redirect()->route('admin.admin.index')->with('success', 'Информация успешно добавлена!');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = Admin::find($id);

        if (!$row) abort(404);

        $options = Admin::$role_name;

        return view('admin.admin.create_edit', compact('row', 'options'))->with('title', 'Редактировать пользователя');
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $data = AdminData::fromArray($request->validated());
        $admin = Admin::find($data->id);

        if (!$admin) abort(404);

        $payload = $data->toArray();

        if ($data->password !== null) {
            $payload['password'] = Hash::make($data->password);
        }

        $admin->fill($payload);
        $admin->save();

        return redirect()->route('admin.admin.index')->with('success', 'Данные успешно обновлены!');
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        if ($id === (int) Auth::id()) {
            return response()->json(
                ['message' => 'Нельзя удалить текущего пользователя.'],
                Response::HTTP_FORBIDDEN,
            );
        }

        $admin = Admin::find($id);

        if (!$admin) {
            return response()->json(['message' => 'Запись не найдена.'], Response::HTTP_NOT_FOUND);
        }

        $admin->delete();

        return response()->json(['message' => 'Данные успешно удалены.']);
    }
}
