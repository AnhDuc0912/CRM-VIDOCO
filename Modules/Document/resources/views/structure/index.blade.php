@extends('core::layouts.app')

@section('title', 'Cấu trúc văn bản')

@section('content')
<div class="container-fluid page-content">

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Quản lý Cấu Trúc</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cấu trúc văn bản</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('document.structure.create') }}" class="btn btn-secondary m-1">
                        <i class="bx bx-cloud-upload me-1"></i>Thêm Cấu Trúc
                    </a>
                </div>
            </div>
        </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">ID</th>
                        <th>Tên</th>
                        <th width="160">Loại</th>

                        <th width="140">Thao tác</th>
                    </tr>
                </thead>
                <tbody style="border-top:none!important;">
                    @forelse ($structures as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>
                                {{ $s->name }}
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $typeLabels[$s->type] ?? $s->type }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('document.structure.edit', $s->id) }}"
                                   class="btn btn-sm btn-warning">
                                         <i class="bx bx-edit"></i>
                                </a>

                                <form action="{{ route('document.structure.delete', $s->id) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Xóa cấu trúc này?')"
                                            class="btn btn-sm btn-danger">
                                             <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Chưa có cấu trúc
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
