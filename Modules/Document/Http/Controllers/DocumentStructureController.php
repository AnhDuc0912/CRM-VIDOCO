<?php

namespace Modules\Document\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Document\Models\DocumentStructure;

class DocumentStructureController extends Controller
{
     public function index()
    {
        $structures = DocumentStructure::orderBy('type')
            ->orderBy('parent_id')
            ->get();

        $typeLabels = DocumentStructure::TYPE_LABELS;

        return view('document::structure.index', compact(
            'structures',
            'typeLabels'
        ));
    }

    public function create()
    {
        return view('document::structure.create', [
            'parents' => DocumentStructure::orderBy('name')->get(),
            'typeLabels' => DocumentStructure::TYPE_LABELS
        ]);
    }

    public function edit($id)
    {
        return view('document::structure.edit', [
            'item' => DocumentStructure::findOrFail($id),
            'parents' => DocumentStructure::where('id', '!=', $id)->get(),
            'typeLabels' => DocumentStructure::TYPE_LABELS
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'parent_id' => 'nullable|integer'
        ]);

        DocumentStructure::create($validated);

        return redirect()->route('document.structure.index')
            ->with('success', 'Tạo thành công');
    }


    public function update(Request $request, $id)
    {
        $item = DocumentStructure::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'parent_id' => 'nullable|integer',
        ]);

        $item->update($validated);

        return redirect()->route('document.structure.index')
            ->with('success', 'Cập nhật thành công');
    }

    public function delete($id)
    {
        $hasChild = DocumentStructure::where('parent_id', $id)->count();
        if ($hasChild > 0) {
            return back()->with('error', 'Không thể xóa vì có cấp con!');
        }

        DocumentStructure::findOrFail($id)->delete();

        return back()->with('success', 'Xóa thành công');
    }

    public function children(Request $request)
    {
        return DocumentStructure::where('type', $request->type)
            ->where('parent_id', $request->parent_id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }
}
