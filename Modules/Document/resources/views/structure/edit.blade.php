@extends('core::layouts.app')

@section('title', 'Sửa cấu trúc')

@section('content')
    <div class="container page-content">
 <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Cấu trúc văn bản</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Cập nhật cấu trúc văn bản</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="fw-bold mb-3">Sửa cấu trúc văn bản</h5>

                <form action="{{ route('document.structure.update', $item->id) }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Tên cấu trúc</label>
                        <input name="name" value="{{ $item->name }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Loại</label>
                        <select name="type" class="form-select" required>
                            @foreach ($typeLabels as $key => $t)
                                <option value="{{ $key }}" @selected($item->type == $key)>
                                    {{ $typeLabels[$t] ?? ucfirst($t) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thuộc (cha)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">— Không có —</option>
                            @foreach ($parents as $p)
                                <option value="{{ $p->id }}" @selected($item->parent_id == $p->id)>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                   
                    <div class="row g-3 mb-4 text-center">
                            <div class="col-12">
                                <button class="btn btn-primary" type="submit">Cập Nhật</button>
                            </div>
                        </div>

                </form>

            </div>
        </div>

    </div>
@endsection
