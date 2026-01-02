@extends('auth::layouts.auth')

@section('title', 'Quên Mật Khẩu')
@section('body-class', 'bg-forgot')

@section('content')
    <div
        class="authentication-forgot d-flex align-items-center justify-content-center">
        <div class="card shadow-lg forgot-box">
            <div class="card-body p-md-5">
                <div class="text-center">
                    <img src="{{ asset('assets/images/icons/forgot-2.png') }}"
                        width="140" alt="" />
                </div>
                <h4 class="mt-5 font-weight-bold">
                    {{ __('auth.forgot_password.title') }}?</h4>
                <p class="text-muted">{{ __('auth.forgot_password.subtitle') }}</p>

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="mb-3 mt-4">
                        <label class="form-label"
                            for="email">{{ __('auth.forgot_password.email') }}
                            ID</label>
                        <input type="email" id="email" name="email"
                            class="form-control form-control-lg radius-30 @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" required autofocus
                            placeholder="example@vidoco.vn" />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit"
                            class="btn btn-info btn-lg radius-30">{{ __('auth.forgot_password.submit') }}</button>
                        <a href="{{ route('login') }}"
                            class="btn btn-light radius-30"><i
                                class='bx bx-arrow-back mr-1'></i>{{ __('auth.login.submit') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
