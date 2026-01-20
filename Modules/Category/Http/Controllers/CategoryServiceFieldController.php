<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Category\Repositories\CategoryServiceFieldRepository;
use Modules\Core\Enums\PermissionEnum;

class CategoryServiceFieldController extends Controller
{
    public function __construct(protected CategoryServiceFieldRepository $repository) {}

    public function index(Request $request)
    {
        can(PermissionEnum::SERVICE_VIEW);
        $fields = $this->repository->paginate(20);
        return view('category::fields.index', compact('fields'));
    }

    public function create()
    {
        can(PermissionEnum::SERVICE_CREATE);
        return view('category::fields.create');
    }

    public function store(Request $request)
    {
        can(PermissionEnum::SERVICE_CREATE);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        $this->repository->create($data);

        return redirect()->route('fields.index')->with('success', 'Lĩnh vực đã được tạo thành công');
    }

    public function edit($id)
    {
        can(PermissionEnum::SERVICE_UPDATE);
        $field = $this->repository->find($id);
        if (! $field) {
            return redirect()->route('fields.index')->with('error', 'Không tìm thấy Lĩnh vực');
        }
        return view('category::fields.edit', compact('field'));
    }

    public function update(Request $request, $id)
    {
        can(PermissionEnum::SERVICE_UPDATE);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
        ]);

        $this->repository->update($id, $data);

        return redirect()->route('fields.index')->with('success', 'Lĩnh vực đã được cập nhật');
    }

    public function destroy($id)
    {
        can(PermissionEnum::SERVICE_DELETE ?? PermissionEnum::SERVICE_UPDATE);
        $this->repository->delete($id);
        return redirect()->route('fields.index')->with('success', 'Lĩnh vực đã được xóa');
    }
}
