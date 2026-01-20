<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\CategoryServiceField;

class CategoryServiceFieldRepository
{
    public function all($columns = ['*'])
    {
        return CategoryServiceField::orderBy('id', 'desc')->get($columns);
    }

    public function paginate($perPage = 15)
    {
        return CategoryServiceField::orderBy('id', 'desc')->paginate($perPage);
    }

    public function find($id)
    {
        return CategoryServiceField::find($id);
    }

    public function create(array $data)
    {
        return CategoryServiceField::create($data);
    }

    public function update($id, array $data)
    {
        $item = CategoryServiceField::findOrFail($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = CategoryServiceField::findOrFail($id);
        return $item->delete();
    }
}
