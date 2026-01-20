@extends('core::layouts.app')

@section('title', 'Lĩnh vực')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title">Lĩnh vực</h4>
            <a href="{{ route('fields.create') }}" class="btn btn-primary">Thêm mới</a>
        </div>
        <div class="card-body">
            @if($fields->count())
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã</th>
                            <th>Tên</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fields as $field)
                            <tr>
                                <td>{{ $field->id }}</td>
                                <td>{{ $field->code }}</td>
                                <td>{{ $field->name }}</td>
                                <td>
                                    <a href="{{ route('fields.edit', $field->id) }}" class="btn btn-sm btn-secondary">Sửa</a>
                                    <form action="{{ route('fields.destroy', $field->id) }}" method="POST" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $fields->links() }}
            @else
                <p>Chưa có Lĩnh vực nào.</p>
            @endif
        </div>
    </div>
@endsection
