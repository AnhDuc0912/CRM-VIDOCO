@extends('auth::layouts.auth')

@section('title', 'Khôi Phục Mật Khẩu')
@section('body-class', 'bg-reset')

@section('content')
    <div
        class="authentication-reset-password d-flex align-items-center justify-content-center">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="card radius-15">
                    <div class="row g-0">
                        <div class="col-lg-5">
                            <div class="card-body p-md-5">
                                <div class="text-left">
                                    <img src="{{ asset('assets/images/logo-img.png') }}"
                                        width="180" alt="">
                                </div>
                                <h4 class="mt-5 font-weight-bold">
                                    {{ __('auth.reset_password.title') }}</h4>
                                <p class="text-muted">
                                    {{ __('auth.reset_password.subtitle') }}</p>

                                <form method="POST"
                                    action="{{ route('password.update') }}">
                                    @csrf

                                    <!-- Password Reset Token -->
                                    <input type="hidden" name="token"
                                        value="{{ $token }}">

                                    <!-- Email Address -->
                                    <div class="mb-3">
                                        <input id="email" class="form-control"
                                            type="hidden" name="email"
                                            value="{{ old('email', $email) }}"
                                            required autofocus />
                                    </div>

                                    <div class="mb-3 mt-5">
                                        <label
                                            class="form-label">{{ __('auth.reset_password.password') }}</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Enter new password"
                                            required />
                                        @error('password')
                                            <span class="invalid-feedback"
                                                role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label
                                            class="form-label">{{ __('auth.reset_password.password_confirmation') }}</label>
                                        <input type="password"
                                            name="password_confirmation"
                                            class="form-control"
                                            placeholder="Confirm password"
                                            required />
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit"
                                            class="btn btn-info">{{ __('auth.reset_password.submit') }}</button>
                                        <a href="{{ route('login') }}"
                                            class="btn btn-light"><i
                                                class='bx bx-arrow-back mr-1'></i>{{ __('auth.login.submit') }}</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <img src="{{ asset('assets/images/login-images/forgot-password-frent-img.jpg') }}"
                                class="card-img login-img h-100" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
