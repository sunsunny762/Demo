<div class="loader" style="display: none;"></div>
<header class="container-fluid sticky-top">
    <div class="row">
        <div class="d-flex justify-content-end align-items-center col-12 pl-0">
            <div class="d-flex justify-content-between align-items-center mr-auto">
                <button type="button" class="nav-btn menu-icon btn btn-primary rounded-0 p-3 mr-2 d-flex" id="nav-btn">
                    <img class="svg" src="{{ asset('assets/admin/images/cms/navbar.svg') }}"
                        alt="Demo" class="w-100">
                </button>
                <div class="header-logo">
                    <a target="_blank" href="/">
                        <img src="{{ asset('assets/admin/images/header-logo.png') }}"
                            alt="Demo" class="w-75">
                    </a>
                </div>
            </div>
            <div class="admin-profile">
                <div class="dropdown">
                    <button class="dropdown-toggle " type="button" id="dropdownMenu2" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="menu-title welcome-text">
                            <span class="d-none d-md-inline-block"><strong>Welcome: </strong>
                                {{ Auth::user()->first_name.' '.Auth::user()->last_name }}
                            </span>

                            @if(isset(Auth::user()->profile_image) && !@empty(Auth::user()->profile_image))
                            <span class="userimg"><img
                                    src="{{ asset('media/profile_image/fit-32x32-'.Auth::user()->profile_image) }}"
                                    alt="{{ Auth::user()->first_name.' '.Auth::user()->last_name }}"
                                    class="mw-100 rounded-circle" /></span>
                            @else
                            <span class="userimg"><img class="mw-100 rounded-circle"
                                    src="{{ asset('assets/admin/images/cms/icon_header_user.svg') }}"></span>
                            @endif
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right pt-0 pb-0" aria-labelledby="dropdownMenu2">
                        <div class="dropdown-item py-3 d-block d-md-none"><strong>Welcome: </strong>
                            {{ Auth::user()->first_name.' '.Auth::user()->last_name }}
                        </div>
                        <a href="{{ route('profile') }}" class="dropdown-item py-3">-
                            {{ __('user.profile_menu_title') }}</a>
                        <a href="{{ route('user.change-password') }}" class="dropdown-item py-3">-
                            {{ __('changepassword.pagetitle') }}</a>
                        <!--  Logout Menu -->
                        <a class="dropdown-item logout-link py-3 bg-dark" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <img class="svg"
                                src="{{ asset('assets/admin/images/cms/logout.svg') }}"
                                alt="Demo" class="w-100"> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
