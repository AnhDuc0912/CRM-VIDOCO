<?php

namespace Modules\Department\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Department\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('department::index', compact('departments'));
    }

    public function create()
    {
        return view('department::create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        Department::create($request->all());
        return redirect()->route('department.index')->with('success', 'Thêm phòng ban thành công');
    }

    public function edit(Department $department)
    {
        return view('department::edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $department->update($request->all());
        return redirect()->route('department.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('department.index')->with('success', 'Xóa thành công');
    }
}
