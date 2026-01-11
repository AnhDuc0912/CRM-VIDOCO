@extends('core::layouts.app')

@section('title', 'Sửa nguồn khách hàng')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản Lý Nguồn Khách Hàng</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('customer-sources.index') }}">Danh Sách Nguồn</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sửa Nguồn</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <hr />

            <form action="{{ route('customer-sources.update', $source->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label required">Tên Nguồn</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" 
                            value="{{ old('name') ?? $source->name }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mô Tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description') ?? $source->description }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $source->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Hoạt động</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Cập Nhật</button>
                        <a href="{{ route('customer-sources.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
