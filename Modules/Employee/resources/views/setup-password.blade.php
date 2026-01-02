@extends('auth::layouts.auth')

@section('title', 'Thiết lập mật khẩu')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-none border mb-0 radius-15">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h4>Thiết lập mật khẩu tài khoản</h4>
                            <p class="text-muted">Xin chào <strong>{{ $employee->full_name }}</strong></p>
                        </div>

                        <form method="POST" action="{{ route('employees.setup-password') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label required">Mật khẩu mới</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Nhập mật khẩu mới (tối thiểu 8 ký tự)">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label required">Xác nhận mật khẩu</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        placeholder="Nhập lại mật khẩu mới">
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle me-2"></i>
                                        <strong>Lưu ý:</strong> Mật khẩu phải có ít nhất 8 ký tự và nên bao gồm chữ hoa, chữ
                                        thường, số và ký tự đặc biệt để bảo mật tốt hơn.
                                    </div>
                                </div>

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-info">
                                        <i class="bx bx-check me-1"></i>
                                        Tạo mật khẩu
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
