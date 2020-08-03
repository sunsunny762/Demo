@extends('admin.layouts.app')
@section('content')
<div class="container-fluid">

    <h1 class="page-title">{{__('dashboard.pagetitle')}}</h1>
    <div class="col-12 dashboard-holder">
        <div class="row">
            <div class="col-xl-2 col-lg-4 col-sm-6 col-12 item">
                <a href="{{ route('dashboard') }}"
                    class="dashboard-item d-flex flex-column justify-content-center align-items-center"
                    data-title="Dashboard">
                    <img class="svg" src="{{ asset('assets/admin/images/cms/dashboard.svg') }}" alt="Dashboard" />
                    <span class="dashborad-title">Dashboard</span>
                </a>
            </div>
            <div class="col-xl-2 col-lg-4 col-sm-6 col-12 item">
                <a href="{{ route('user.index') }}"
                    class="dashboard-item d-flex flex-column justify-content-center align-items-center"
                    data-title="Users">
                    <img class="svg" src="{{ asset('assets/admin/images/cms/user.svg') }}" alt="Users" />
                    <span class="dashborad-title">Users</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
