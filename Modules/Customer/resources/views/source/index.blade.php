@extends('core::layouts.app')
@use('Modules\Core\Enums\PermissionEnum')

@section('title', 'Quản lý nguồn khách hàng')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Quản Lý Nguồn Khách Hàng</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh Sách Nguồn</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a href="{{ route('customer-sources.create') }}" class="btn btn-secondary">
                            <i class="bx bx-cloud-upload me-1"></i>Thêm Nguồn
                        </a>
                    </div>
                </div>
            </div>
            <hr />
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Nguồn</th>
                            <th>Mô Tả</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sources as $source)
                            <tr>
                                <td>{{ $source->id }}</td>
                                <td>{{ $source->name }}</td>
                                <td>{{ $source->description ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $source->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $source->is_active ? 'Hoạt động' : 'Vô hiệu' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('customer-sources.edit', $source->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('customer-sources.destroy', $source->id) }}', 'Xóa nguồn này?')">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Chưa có nguồn nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $sources->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(url, message) {
            if (confirm(message)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endpush
