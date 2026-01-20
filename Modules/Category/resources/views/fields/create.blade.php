@extends('core::layouts.app')

@section('title', 'Thêm Lĩnh vực')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Thêm Lĩnh vực</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('fields.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Mã</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tên</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <button class="btn btn-primary">Lưu</button>
            </form>
        </div>
    </div>
@endsection
