<?php

namespace Modules\Level\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Level\Models\Level;
use Modules\Position\Models\Position;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::latest()->paginate(10);
        return view('level::index', compact('levels'));
    }

    public function create()
    {
        return view('level::create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $level = Level::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('level.index')->with('success', 'Thêm level thành công');
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        $level->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('level.index')->with('success', 'Cập nhật level thành công');
    }


    public function edit(Level $level)
    {
        return view('level::edit', compact('level'));
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return redirect()->route('level.index')->with('success', 'Xóa thành công');
    }
}
