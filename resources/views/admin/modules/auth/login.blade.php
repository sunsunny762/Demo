@extends('admin.layouts.app')
@section('content')
<div class="loader" style="display: none;"></div>
<div class="d-flex align-items-center form-signin-box h-100">

    <form method="POST" action="{{ route('login') }}" class="form-signin container">
        <div class="login-content row justify-content-center">
            <div class="col-12 col-md-5 col-xl-4 my-5">

                <div class="login-logo text-center mb-3">
                    <img src="{{ asset('assets/admin/images/header-logo.png') }}" alt="demo">
                </div>
                <h1 class="text-center mb-5">{{ __('Login') }}</h1>

                @csrf
                @error('auth_failed')
                <div class="alert alert-danger" role="alert">
                    <span>{{ $message }}</span>
                </div>
                @enderror
                @error('auth_throttle')
                <div class="alert alert-danger" role="alert">
                    <span>{{ $message }}</span>
                </div>
                @enderror
                <div class="form-group">

                    <label for="email">{{ __('Email') }}<span class="text-danger">*</span></label>
                    <input id="email" type="text" class="validate form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" autocomplete="email" autofocus data-validator="required|email">
                    <div class="errormessage" role="alert">
                    @error('email')<span>{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">{{ __('Password') }}<span class="text-danger">*</span></label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" autocomplete="current-password" data-validator="required">
                    <div class="errormessage" role="alert">
                    @error('password')<span>{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="remember">
                            <span>{{ __('Remember Me') }}</span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary col-12">
                        {{ __('Login') }}
                    </button>
                </div>
                @if (Route::has('password.request'))
                <div class="form-group text-center">
                    <a class="text-muted" href="{{ route('password.request') }}">
                        <small>
                            {{ __('Forgot Your Password?') }}
                        </small>
                    </a>
                </div>
                @endif
            </div>
    </form>

</div>
@endsection
