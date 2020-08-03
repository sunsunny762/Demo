@extends('admin.layouts.app')
@section('content')

<form method="post" action="{{ route('profile.update')  }}" name="frmaddedit" id="frmaddedit">
    <div class="row mx-0 mb-3">
        <div class="col-lg-6">
            <h1 class="page-title">My Profile</h1>
        </div>
        <div class="col-lg-6 d-flex justify-content-lg-end justify-content-center align-items-center">
            <div class="top-btn-box">
                <div class="top-btn-box d-flex">
                    <a tabindex="5" href="{{ route('dashboard') }}" class="btn btn-dark mr-1 btn-sm"><i class="icon-close-icon top-icon"></i> <span>{{ __('common.cancel') }}</span></a>
                    <button tabindex="9" type="submit" class="btn btn-primary mr-1 btn-sm" id="btnsave" name="btnsave" value='save'><i class="icon-save_icon top-icon"></i> <span>{{ __('common.save') }}</span></button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="form-group col-xl-6">
                        <label for="first_name">{{ __('user.first_name') }}<span class="text-danger">*</span></label>
                        <input id="first_name" type="text" class="form-control firstele @error('first_name') is-invalid @enderror" name="first_name" value="{{$user->first_name}}" data-validator="required">
                        <div class="errormessage">@error('first_name') {{ $message }} @enderror</div>
                    </div>
                    <div class="form-group col-xl-6">
                        <label for="last_name">{{ __('user.last_name') }}</label>
                        <input id="last_name" type="text" class="form-control firstele @error('last_name') is-invalid @enderror" name="last_name" value="{{$user->last_name}}">
                        @error('last_name')
                        <div class="errormessage">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-xl-12">
                        <label for="email">{{ __('user.email') }}</label>
                        <input id="email" type="text" class="form-control firstele @error('email') is-invalid @enderror" name="email" value="{{old('email',$user->email)}}" @if (isset($user->id) ) disabled @endif >
                        @error('email')
                        <div class="errormessage">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12 banner-section">
                        <div class="form-group">
                            <label for="temp_profile_image">{{ __('user.profile_image') }}</label>
                            <input type="file" data-size="{{ getImageUploadSizeInMB('profile_image') }}" class="dropify image-upload" name="temp_profile_image" data-folder="profile_image" {{ old('profile_image',$user->profile_image) != '' ? 'data-default-file='.getImageUrl(old('profile_image',$user->profile_image),'profile_image') : '' }}>
                            <div><small>{{ getImageRecommendedSize('profile_image') }}</small></div>
                            <input name="profile_image" type="hidden" value="{{old('profile_image',$user->profile_image)}}">
                            <div id="error_profile_image" class="errormessage  @error('profile_image') @else{{  'd-none'  }} @enderror">@error('profile_image'){{ $message }}@enderror</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection