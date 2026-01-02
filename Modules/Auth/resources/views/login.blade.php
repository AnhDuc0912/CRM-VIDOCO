@extends('auth::layouts.auth')

@section('title', __('auth.login.title'))
@section('body-class', 'bg-login')

@section('content')
    <div
        class="section-authentication-login d-flex align-items-center justify-content-center mt-4">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card radius-15 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-xl-6">
                            <div class="card-body p-5">
                                <div class="text-center">
                                    <img src="{{ asset('assets/images/logo-icon.png') }}"
                                        width="200" alt="">
                                    <h3 class="mt-4 font-weight-bold">
                                        {{ __('auth.login.title') }} </h3>
                                </div>
                                <div class="">
                                    <div class="login-separater text-center mb-4">
                                        <span>{{ __('auth.login.subtitle') }}</span>
                                        <hr>
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3" method="POST"
                                            action="{{ route('login') }}">
                                            @csrf
                                            <div class="col-12">
                                                <label for="inputEmailAddress"
                                                    class="form-label">{{ __('auth.login.email') }}</label>
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    id="inputEmailAddress"
                                                    name="email"
                                                    value="{{ old('email') }}"
                                                    required autofocus
                                                    placeholder="x@vidoco.vn">
                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword"
                                                    class="form-label">{{ __('auth.login.password') }}</label>
                                                <div class="input-group"
                                                    id="show_hide_password">
                                                    <input type="password"
                                                        class="form-control border-end-0 @error('password') is-invalid @enderror"
                                                        id="inputChoosePassword"
                                                        name="password" required
                                                        placeholder="Enter Password">
                                                    <a href="javascript:;"
                                                        class="input-group-text bg-transparent"><i
                                                            class="bx bx-hide"></i></a>
                                                </div>
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input"
                                                        type="checkbox"
                                                        id="flexSwitchCheckChecked"
                                                        name="remember">
                                                    <label class="form-check-label"
                                                        for="flexSwitchCheckChecked">{{ __('auth.login.remember_me') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <a
                                                    href="{{ route('password.request') }}">{{ __('auth.login.forgot_password') }}</a>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit"
                                                        class="btn btn-info"><i
                                                            class="bx bxs-lock-open"></i>{{ __('auth.login.submit') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="col-xl-6 bg-login-color d-flex align-items-center justify-content-center">
                            <img src="{{ asset('assets/images/login-images/login-frent-img.jpg') }}"
                                class="img-fluid" alt="...">
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") ==
                    "text") {
                    $('#show_hide_password input').attr('type',
                        'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass(
                        "bx-show");
                } else if ($('#show_hide_password input').attr(
                        "type") == "password") {
                    $('#show_hide_password input').attr('type',
                        'text');
                    $('#show_hide_password i').removeClass(
                        "bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
@endsection
