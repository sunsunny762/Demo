@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-center align-items-center form-signin-box h-100">
    <form method="POST" action="{{ route('password.update') }}" class="form-signin container">
        <div class="login-content row justify-content-center">
            <div class="col-12 col-md-5 col-xl-4 my-5">
                @if ($is_valid)
                <div class="login-logo text-center mb-3">
                    <img src="{{ asset('assets/admin/images/header-logo.png') }}" alt="Demo">
                </div>
                <h1 class="text-center mb-5">
                        @if($user->email_verified_at == NULL)
                        {{ __('Account Activation') }}
                        @else
                        {{ __('Reset Password') }}
                        @endif
                </h1>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group">
                    <label for="email">{{ __('Email') }}<span class="text-danger">*</span></label>
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ $email ?? old('email') }}" autocomplete="email" readonly data-validator="required|email">
                    <div class="errormessage" role="alert">
                    @error('email')<span> {{ $message }} </span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">{{ __('Password') }}<span class="text-danger">*</span></label>
                    <input id="password" type="password" class="form-control chg_password @error('password') is-invalid @enderror"
                        name="password" autocomplete="new-password" autofocus data-validator="Medium|required">
                    <div class="errormessage" role="alert">
                    @error('password')<span> {{ $message }} </span>@enderror
                    </div>
                    <p id="passwordHelpBlock" class="form-text text-muted">
                        <small class="line1">
                            {!!__('user.password_rule_medium_msg',array('min'=>6))!!}
                        </small>
                    </p>
                    <small class="form-text text-muted line1 disclaimers">
                        <span class="EasyPassword passLength text-success" style="display: none;">{!!__('user.password_rule_length',array('min'=>6)) !!}</span>
                        <span class="MediumPassword passUpperCase text-success" style="display: none;">{!!__('user.password_rule_upper_case')!!}</span>
                        <span class="MediumPassword passLowerCase text-success" style="display: none;">{!!__('user.password_rule_lower_case')!!}</span>
                        <span class="MediumPassword passDigit text-success" style="display: none;">{!!__('user.password_rule_digit')!!}</span>
                        <span class="StrongPassword passSpecialChar text-success" style="display: none;">{!!__('user.password_rule_special_character')!!}</span>
                    </small>
                </div>
                <div class="form-group">
                    <label for="password-confirm">{{ __('Confirm Password') }}<span class="text-danger">*</span></label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                        autocomplete="new-password" data-validator="required|same:password">
                    <div class="errormessage" role="alert">
                    @error('password_confirmation')<span> {{ $message }} </span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary col-12">
                        @if($user->email_verified_at == NULL)
                        {{ __('Set Password') }}
                        @else
                        {{ __('Reset Password') }}
                        @endif
                    </button>
                </div>
                <div class="form-group text-center">
                    <a class="text-muted" href="{{ route('login') }}">
                        <small>{{ __('Back to Login') }}</small>
                    </a>
                </div>
                @else
                <div class="row mb-4 mt-2">
                    <div class="col-md-12">
                        <center class="error">
                            <div class="errormessage">{{ __('passwords.token') }}</div>
                        </center>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>
@endsection
