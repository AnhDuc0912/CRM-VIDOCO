<div class="tab-pane fade" id="Edit-Profile">
    <div class="card shadow-none border mb-0 radius-15">
        <div class="card-body">
            <div class="form-body">
                <div class="row">
                    <div class="col-12 col-lg-5 border-right">
                        <form class="row g-3" action="{{ route('employees.update-password', $employee->user?->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="col-12">
                                <label class="form-label">Mật khẩu cũ</label>
                                <input name="old_password" type="password" placeholder="********"
                                    class="form-control @error('old_password') is-invalid @enderror">
                                @error('old_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mt-1">
                                <label class="form-label">Mật khẩu mới</label>
                                <input name="new_password" type="password" placeholder="********"
                                    class="form-control @error('new_password') is-invalid @enderror">
                                @error('new_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mt-1">
                                <label class="form-label">Nhập lại mật
                                    khẩu</label>
                                <input name="new_password_confirmation" type="password" placeholder="********"
                                    class="form-control @error('new_password_confirmation') is-invalid @enderror">
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-info">Thay
                                    Đổi Mật Khẩu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
