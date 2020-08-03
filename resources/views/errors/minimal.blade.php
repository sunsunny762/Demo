@extends('admin.layouts.app')
@section('content')
    <div class="flex-center position-ref full-height py-5">
        <div class="image text-center">
            <img src="@yield('image')" alt="@yield('code')" class="text-center justify-content-cnter mw-100">
        </div>
        @if(empty(Request::is('admin/*')))
            <div class="back-to-home-btn text-center">
                <a href="{{ app('router')->has('home') ? route('home') : url('/') }}">
                    <button class="btn btn-primary">
                        {{ __('Back to Home Page') }}
                    </button>
                </a>
            </div>
        @endif
    </div>
@endsection
    
