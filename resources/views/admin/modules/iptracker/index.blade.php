@extends('admin.layouts.app')
@section('content')
<div class="row mx-0 mb-3">
    <div class="col-6">
        <h1 class="page-title"><?php echo __('iptracker.pagetitle'); ?></h1>
    </div>
    <div class="top-btn-box col-6" id="normal_btns">
        <div class="top-btn-box d-flex justify-content-end align-items-center h-100">
            <a tabindex="1" href="javascript:void(0)" class="btn btn-primary mr-1 search-btn btn-sm show hide"
                id="search-btn">
                <i class="icon-search-icon top-icon"></i>
                <span class="btn-title">{{ __('common.search') }}</span>
            </a>
        </div>
    </div>
</div>

<div class="col-12 admin-holder">
    <div class="row">
        <div class="{{ Request::has('searchtextDate') && Request::has('search') ? 'show' : 'hide' }}" id="searchbox">
            <form name="frmsearch" id="frmsearch" action="{{ route('iptracker.index') }}" method="GET" class="col-12">
                {{-- {{ $iptrackers->appends(prepareInputRequestArray())->url(1) }} --}}
                @foreach (Request::all() as $key=>$value)
                @if (in_array($key,['search','status','btnsearch']))
                @continue
                @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
                @endforeach
                <div class="row">
                    <div class="form-group col-md-6 col-12">
                        <label>{{ __('iptracker.user') }} / {{ __('iptracker.ip_address') }}</label>
                        <input tabindex="3" name="search" id="search"
                            placeholder="{{ __('common.search') ." ". __('iptracker.pagetitle') }}" type="text"
                            class="form-control" value="{{ Request::get('search') }}">
                    </div>
                    <div class="form-group col-lg-4 col-md-6 col-12">
                        <label>{{ __('iptracker.search_login_logout') }}</label>
                        <!-- <input tabindex="3" name="searchtextDate" id="searchtextDate" type="text"
                            class="datepicker-datesonly form-control" readonly
                            value="{{ Request::get('searchtextDate') }}"> -->

                        <input tabindex="3" class="form-control datepicker" name="searchtextDate" id="searchtextDate"
                            type="text" value="{{ Request::get('searchtextDate') }}" readonly />
                    </div>
                    <div class="form-group col-12">
                        <button tabindex="5" type="submit" class="btn btn-primary submit-btn" id="btnsearch"
                            name="btnsearch">{{ __('common.search') }}</button>
                        <a href="{{ route('iptracker.index', ['search' => '', 'status' => '']) }}"
                            class="btn btn-primary reset-btn">{{ __('common.reset') }}</a>
                        <button tabindex="7" type="button" class="btn btn-dark close-btn"
                            id="search-btn-h">{{ __('common.close') }}</button>
                        <hr>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 photos-main">
            <section id="wrapper">
                <form name="frmlist" id="frmlist" method="POST">
                    @csrf
                    <input type="hidden" name="bulk-action" value="">
                    <table data-orders="0" data-target="1" defaultdir="desc" class="admintable table table-hover mb-0"
                        width="100%">
                        <thead>
                            <tr>
                                <th class="hide" scope="col">
                                    <!-- this blank column is for primary column -->
                                </th>
                                <th scope="col" class="control nosort">
                                    <!-- this blank column is responsive controll -->
                                </th>
                                <th scope="col">
                                    <div>
                                        <span>
                                            {{ __('iptracker.user') }}
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="nosort">
                                    <div>
                                        <span>
                                            {{ __('iptracker.ip_address') }}
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="nosort">
                                    <div>
                                        <span>
                                            {{ __('iptracker.login_time') }}
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="nosort">
                                    <div>
                                        <span>
                                            {{ __('iptracker.last_activity') }}
                                        </span>
                                    </div>
                                </th>
                                <th scope="col" class="nosort">
                                    <div>
                                        <span>
                                            {{ __('iptracker.logout_time') }}
                                        </span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($iptrackers as $iptracker)
                            <tr>
                                <td class="active-box hide">
                                    <i style="display:none">{{ $iptracker->login }}</i>
                                </td>
                                <td>

                                </td>
                                <td>
                                    {{ $iptracker->user }}
                                </td>
                                <td>
                                    <div class="email-add">
                                        {{ $iptracker->ip_address }}
                                    </div>
                                </td>
                                <td>
                                    @if(!empty($iptracker->login))
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',  $iptracker->login)->format(config('app.datetime_format')) }}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @if(!empty( $iptracker->lastactivity))
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',  $iptracker->lastactivity)->format(config('app.datetime_format')) }}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @if(!empty( $iptracker->logout))
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',  $iptracker->logout)->format(config('app.datetime_format')) }}
                                    @else

                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if (count($iptrackers) == 0)
                            <tr class="noreocrd">
                                <td colspan="10" class="text-center">
                                    {{ __('iptracker.no_result') }}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
