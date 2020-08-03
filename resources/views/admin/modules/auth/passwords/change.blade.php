@extends('admin.layouts.app')
@section('content')
<form method="post" action="{{ route('user.change-password') }}" name="pwdform" id="pwdform" class="col s12">
    @csrf
    <div class="row mx-0 mb-3">
        <div class="col-sm-6">
            <h1 class="page-title">{{__('changepassword.pagetitle')}}</h1>
        </div>
        <div class="col-sm-6 d-flex justify-content-end align-items-center">
            <div class="top-btn-box">
                <div class="top-btn-box d-flex">
                    <a tabindex="5" href="{{ route('dashboard') }}" class="btn btn-sm btn btn-sm btn-dark mr-1"><i
                            class="icon-close-icon top-icon"></i> <span>{{ __('common.cancel') }}</span></a>
                    <button tabindex="9" type="submit" class="btn btn-sm btn-primary" id="btnsave" name="btnsave"><i
                            class="icon-save_icon top-icon"></i> <span>{{ __('common.save') }}</span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="px-3">
        <div class="row">
            <div class="form-group col-md-12 welcome-text">
                <div class="bg-white pt-2 pb-1 px-3">
                    <label>{{ __('changepassword.username') }}:</label>
                    <p class="active d-inline-block mb-0">
                        <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong>
                    </p>
                </div>
            </div>
            <div class="form-group col-xl-4 col-md-6">
                <label for="current-password">{{ __('changepassword.current_password') }}<span
                        class="text-danger">*</span></label>
                <input id="current-password" type="password"
                    class="form-control firstele @error('current-password') is-invalid @enderror"
                    name="current-password" value=""  data-validator="required">
                <div class="errormessage">@error('current-password') {{ $message }} @enderror</div>
            </div>
            <div class="form-group col-xl-4 col-md-6">
                <label for="new-password">{{ __('changepassword.new_password') }}<span
                        class="text-danger">*</span></label>
                <input id="new-password" type="password" class="form-control chg_password @error('password') is-invalid @enderror"
                    name="new-password" autocomplete="new-password" data-validator="6|required">
                <div class="errormessage">@error('new-password') {{ $message }} @enderror</div>
                <small id="passwordHelpBlock" class="form-text text-muted line1">
                    {!!__('user.password_rule_medium_msg',array('min' => 6))!!}
                </small>
                <small class="form-text text-muted line1 disclaimers">
                        <span class="EasyPassword passLength text-success" style="display: none;">{!!__('user.password_rule_length',array('min'=> 6)) !!}</span>
                        <span class="MediumPassword passUpperCase text-success" style="display: none;">{!!__('user.password_rule_upper_case')!!}</span>
                        <span class="MediumPassword passLowerCase text-success" style="display: none;">{!!__('user.password_rule_lower_case')!!}</span>
                        <span class="MediumPassword passDigit text-success" style="display: none;">{!!__('user.password_rule_digit')!!}</span>
                        <span class="StrongPassword passSpecialChar text-success" style="display: none;">{!!__('user.password_rule_special_character')!!}</span>
                </small>
            </div>
            <div class="form-group col-xl-4 col-md-6">
                <label for="password-confirm">{{ __('changepassword.confirm_password') }}<span
                        class="text-danger">*</span></label>
                <input id="password-confirm" type="password" class="form-control" name="password-confirm"
                    autocomplete="password-confirm" data-validator="required|same:new-password">
                <div class="errormessage">@error('password-confirm') {{ $message }} @enderror</div>
            </div>
        </div>
    </div>
</form>
@endsection
