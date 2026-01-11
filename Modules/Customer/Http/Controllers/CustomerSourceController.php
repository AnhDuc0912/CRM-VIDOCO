<?php

namespace Modules\Customer\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Modules\Customer\Models\CustomerSource;
use Illuminate\Http\Request;

class CustomerSourceController extends Controller
{
    public function index()
    {
        $sources = CustomerSource::orderBy('name')->paginate(20);
        return view('customer::source.index', compact('sources'));
    }

    public function create()
    {
        return view('customer::source.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:customer_sources',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        CustomerSource::create($request->all());

        return redirect()->route('customer-sources.index')
            ->with('success', 'Nguồn khách hàng đã được tạo thành công');
    }

    public function edit($id)
    {
        $source = CustomerSource::findOrFail($id);
        return view('customer::source.edit', compact('source'));
    }

    public function update(Request $request, $id)
    {
        $source = CustomerSource::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:customer_sources,name,' . $id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $source->update($request->all());

        return redirect()->route('customer-sources.index')
            ->with('success', 'Nguồn khách hàng đã được cập nhật thành công');
    }

    public function destroy($id)
    {
        $source = CustomerSource::findOrFail($id);
        $source->delete();

        return redirect()->route('customer-sources.index')
            ->with('success', 'Nguồn khách hàng đã được xóa thành công');
    }
}
