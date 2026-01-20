@extends('core::layouts.app')

@section('title', 'Cập nhật Lĩnh vực')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Cập nhật Lĩnh vực</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('fields.update', $field->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Mã</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $field->code) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tên</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $field->name) }}" required>
                </div>
                <button class="btn btn-primary">Lưu</button>
            </form>
        </div>
    </div>
@endsection
