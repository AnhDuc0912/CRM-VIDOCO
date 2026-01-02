<?php

namespace Modules\Position\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Position\Models\Position;
use Modules\Level\Models\Level;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::latest()->paginate(10);
        return view('position::index', compact('positions'));
    }

    public function create()
    {
        return view('position::create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        Position::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('position.index')->with('success', 'Thêm position thành công');
    }

    public function edit(Position $position)
    {
        return view('position::edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
        ]);

        $position->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('position.index')->with('success', 'Cập nhật position thành công');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('position.index')->with('success', 'Xóa thành công');
    }

}
