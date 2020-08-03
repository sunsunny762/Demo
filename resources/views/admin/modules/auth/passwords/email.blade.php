@extends('admin.layouts.app')

@section('content')
<div class="loader" style="display: none;"></div>
<div class="d-flex justify-content-center align-items-center form-signin-box h-100">
    <form method="POST" action="{{ route('password.email') }}" class="form-signin container">
        <div class="login-content row justify-content-center">
            <div class="col-12 col-md-5 col-xl-4 my-5">
                <div class="login-logo text-center mb-3">
                    <img src="{{ asset('assets/admin/images/header-logo.png') }}" alt="Demo">
                </div>
                <h1 class="text-center mb-5">{{ __('Forgot Password') }}</h1>
                @csrf
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif
                @error('user_does_not_exists')
                <div class="alert alert-danger" role="alert">
                    <span>{{ $message }}</span>
                </div>
                @enderror
                <div class="form-group">
                    <label for="email">{{ __('Email') }}<span class="text-danger">*</span></label>
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ old('email') }}" autocomplete="email" autofocus data-validator="required|email">
                    <div class="errormessage" role="alert">
                    @error('email')<span> {{ $message }} </span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn  btn-large col s12 z-depth-0 btn-primary">
                        {{ __('Send Password Reset Link') }}
                    </button>
                </div>
                <div class="form-group text-center">
                    <a class="text-muted" href="{{ route('login') }}">
                        <small> {{ __('Back to Login') }}</small>
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
