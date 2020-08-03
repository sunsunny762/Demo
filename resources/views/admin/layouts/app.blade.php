<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- Include Head -->
@include('admin.includes.head')

<body>
    <!-- Include Header -->
    @guest
    @else
    @include('admin.includes.header')
    @endguest
    @guest
    @else
    <!-- Include Menu -->
    @include('admin.includes.menu')
    <!-- Include Content -->
    <div class="page-content py-3 px-0 px-md-2" id="main">
        @endguest
        @if (Session::has('error'))
        <div class="alert text-center fade show">
            <span class="errormessage text-danger">{!! session('error') !!}</span>
        </div>
        @elseif (Session::has('info'))
        <div class="alert text-center fade show">
            <span class="infomessage text-info">{!! session('info') !!}</span>
        </div>
        @elseif (Session::has('success'))
        <div class="alert text-center fade show">
            <span class="successmessage text-success">{!! session('success') !!}</span>
        </div>
        @endif
        <div class="ajax_message d-none">
            <span></span>
        </div>
        @yield('content')
        @guest
        @else
    </div>
    @endguest
    <!-- Include Footer -->
    @include('admin.includes.footer')
</body>

</html>
