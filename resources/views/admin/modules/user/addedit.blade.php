@extends('admin.layouts.app')
@section('content')

<form method="post" action="{{ (isset($user->id) ? route('user.update',$user->id) : route('user.store'))  }}"
    name="frmaddedit" id="frmaddedit">
    <div class="row mx-0 mb-3">
        <div class="col-lg-6">
            <h1 class="page-title">{{ (isset($user->id) ? __('user.editpagetitle') : __('user.addpagetitle'))  }}</h1>
        </div>
        <div class="col-lg-6 d-flex justify-content-lg-end justify-content-center align-items-center">
            <div class="top-btn-box">
                <div class="top-btn-box d-flex">
                    <a tabindex="5" href="{{ route('user.index') }}" class="btn btn-dark mr-1 btn-sm"><i
                            class="icon-close-icon top-icon"></i> <span>{{ __('common.cancel') }}</span></a>
                    <button tabindex="9" type="submit" class="btn btn-primary mr-1 btn-sm" id="btnsave" name="btnsave"
                        value='save'><i class="icon-save_icon top-icon"></i>
                        <span>{{ __('common.save') }}</span></button>
                    <button tabindex="9" type="submit" class="btn btn-primary btn-sm" id="btnsave" name="btnsave"
                        value='savecontinue'><i class="icon-save_icon top-icon"></i>
                        <span>{{ __('common.savecontinue') }}</span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        @csrf
        @if(isset($user->id)) @method('PUT') @endif
        <div class="row">
            <div class="form-group col-md-6">
                <label for="first_name">{{ __('user.first_name') }}<span class="text-danger">*</span></label>
                <input id="first_name" type="text"
                    class="form-control firstele @error('first_name') is-invalid @enderror" name="first_name"
                    value="{{old('first_name',$user->first_name)}}" data-validator="required">
                <div class="errormessage">@error('first_name') {{ $message }} @enderror</div>
            </div>
            <div class="form-group col-md-6">
                <label for="last_name">{{ __('user.last_name') }}</label>
                <input id="last_name" type="text" class="form-control firstele @error('last_name') is-invalid @enderror"
                    name="last_name" value="{{old('last_name',$user->last_name)}}">
                <div class="errormessage"> @error('last_name') {{ $message }} @enderror</div>
            </div>
            <div class="form-group col-md-6">
                <label for="email">{{ __('user.email') }}<span class="text-danger">*</span></label>
                <input id="email" type="text" class="form-control firstele @error('email') is-invalid @enderror"
                    name="email" value="{{old('email',$user->email)}}" @if (isset($user->id) ) readonly @endif
                data-validator="required|email">
                <div class="errormessage">@error('email'){{ $message }} @enderror</div>
            </div>
            @if (isset($user->id) && $user->email_verified_at != NULL )
            <div class="form-group col-md-6">
                <label for="status">{{ __('user.status') }}</label>
                <select class="form-control" id="status" name="status"
                    {{Auth::user()->id == $user->id || $user->user_type_id == 1 ? 'disabled' : ''}}>
                    @foreach (config('status') as $value => $label)
                    <option value="{{ $value }}" @if( old('status', $user->status) == $value ) selected
                        @endif>{{ $label }}</option>
                    @endforeach
                </select>

                <div class="errormessage"> @error('status') {{ $message }} @enderror</div>

            </div>
            @endif
        </div>
    </div>
</form>
@endsection
