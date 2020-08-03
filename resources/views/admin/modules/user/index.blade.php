@extends('admin.layouts.app')
@section('content')
<div class="row mx-0 mb-3">
    <div class="col-6">
        <h1 class="page-title"><?php echo __('user.pagetitle'); ?></h1>
    </div>
    <div class="top-btn-box col-6" id="normal_btns">
        <div class="top-btn-box d-flex justify-content-end align-items-center h-100">
            <a tabindex="1" href="javascript:void(0)" class="btn btn-primary mr-1 search-btn btn-sm show hide"
                id="search-btn">
                <i class="icon-search-icon top-icon"></i>
                <span class="btn-title">{{ __('common.search') }}</span>
            </a>
            <a tabindex="2" href="{{route('user.create')}}" class="btn btn-primary addnew-btn btn-sm" id="add-btn">
                <i class="icon-addnew top-icon"></i>
                <span class="btn-title">{{ __('common.add') }}</span>
            </a>
        </div>
    </div>

    <div class="top-btn-box hide col-6" id="action_btns">
        <div class="top-btn-box d-flex justify-content-end align-items-center h-100 ">
            <a href="javascript:void(0)" class="btn btn-primary mr-1 btn-sm active-btn"
                onclick="submitactionform('active');">
                <i class="icon-radiobutton_checked top-icon"></i>
                <span class="btn-title">{{ __('common.active') }}</span>
            </a>
            <a href="javascript:void(0)" class="btn btn-primary mr-1 btn-sm inactive-btn"
                onclick="submitactionform('inactive');">
                <i class="icon-radio_button_unchecked top-icon"></i>
                <span class="btn-title">{{ __('common.inactive') }}</span>
            </a>
            <a href="javascript:void(0)" class="btn btn-dark btn-sm delete-btn" onclick="submitactionform('delete');">
                <i class="icon-close-icon top-icon"></i>
                <span class="btn-title">{{ __('common.delete') }}</span>
            </a>
        </div>

    </div>
</div>
@if ($errors->has('email'))
<div class="row mb-4 mt-2">
    <div class="col-md-12">
        <center class="error">
            <div class="errormessage">{{ $errors->first('email') }}</div>
        </center>
    </div>
</div>
@endif
<div class="col-12 admin-holder">
    <div class="row">
        <div class="{{ Request::has('status') && Request::has('search') ? 'show' : 'hide' }}" id="searchbox">
            <form name="frmsearch" id="frmsearch" action="{{ $users->appends(prepareInputRequestArray())->url(1) }}"
                method="GET" class="col-12">
                @foreach (Request::all() as $key=>$value)
                @if (in_array($key,['search','status','btnsearch']))
                @continue
                @else
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
                @endforeach
                <div class="row">
                    <div class="form-group col-md-6 col-12">
                        <label>{{ __('user.name') }} / {{ __('user.email') }}</label>
                        <input tabindex="3" name="search" id="search"
                            placeholder="{{ __('common.search') ." ". __('user.pagetitle') }}" type="text"
                            class="form-control" value="{{ Request::get('search') }}">
                    </div>
                    <div class="form-group col-xl-2 col-lg-4 col-md-5 col-12">
                        <label>{{ __('user.status') }}</label>
                        <select tabindex="4" name="status" id="status" class="form-control">
                            <option value="">{{ __('common.select_status') }}</option>
                            @foreach (config('status') as $value => $label)
                            <option value="{{$value}}"
                                {{ Request::get('status') != "" && intval(Request::get('status')) === $value ? 'selected' : '' }}>
                                {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12">
                        <button tabindex="5" type="submit" class="btn btn-primary submit-btn" id="btnsearch"
                            name="btnsearch">{{ __('common.search') }}</button>
                        <a href="{{ route('user.index', ['search' => '', 'status' => '']) }}"
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
                <form name="frmlist" id="frmlist" action="{{ route('user.bulkaction') }}" method="POST">
                    @csrf
                    <input type="hidden" name="bulk-action" value="">
                    <table data-orders="4" data-target="3" class="admintable table table-hover mb-0" width="100%">
                        <thead>
                            <tr>
                                <th class="hide" scope="col">
                                    <!-- this blank column is for primary column -->

                                </th>
                                <th class="active-box status-column" scope="col">
                                    <i class="sort"></i>
                                </th>
                                <th class="check-box nosort" scope="col">
                                    <div class="custom-control custom-checkbox text-center">
                                        <input type="checkbox" class="custom-control-input" name="selectAll"
                                            id="selectAll" onclick="checkAll();">
                                        <label class="custom-control-label" for="selectAll">&nbsp;</label>
                                    </div>
                                </th>
                                <th scope="col" class="control nosort">
                                    <!-- this blank column is responsive controll -->

                                </th>
                                <th scope="col">
                                    <div>
                                        <span>
                                            {{ __('user.name') }}
                                        </span>
                                    </div>
                                </th>
                                <th scope="col">
                                    <div>
                                        <span>
                                            {{ __('user.email') }}
                                        </span>
                                    </div>
                                </th>
                                <th scope="col">
                                    <div>
                                        <span>
                                            {{ __('user.last_login_at') }}
                                        </span>
                                    </div>
                                </th>
                                <th scope="col">
                                    <div>
                                        <span>
                                            {{ __('user.created_at') }}
                                        </span>
                                    </div>
                                </th>
                                <th class="nosort" scope="col">{{__('user.reset_password')}}</th>
                                <th class="text-right nosort" scope="col">{{__('common.edit')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td class="active-box hide">
                                    <i style="display:none">{{ $user->fullname }}</i>
                                </td>
                                <td class="active-box">
                                    <i style="display:none">{{$user->status==2?0:$user->status}}</i>
                                    @if (Auth::user()->id != $user->id && $user->user_type_id != 1)
                                    <a href="{{ route('user.changestatus', ['id' => $user->id]) }} ">
                                        @endif

                                        <span class="sort {{ $user->status == 1 ? 'active' : 'inactive' }} "></span>
                                        @if (Auth::user()->id != $user->id && $user->user_type_id != 1)
                                    </a>
                                    @endif
                                </td>
                                <td class="check-box">
                                    <div class="custom-control custom-checkbox text-center">
                                        @if (Auth::user()->id != $user->id && $user->user_type_id != 1)

                                        <input type="checkbox" class="custom-control-input action-checkbox"
                                            id="filled-in-box_{{ $user->id }}" name="id[]" value="{{$user->id}}">
                                        <label class="custom-control-label"
                                            for="filled-in-box_{{ $user->id }}">&nbsp;</label>


                                        @endif
                                    </div>
                                </td>
                                <td>

                                </td>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>

                                <td>
                                    <div class="email-add"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    </div>
                                </td>
                                <td
                                    data-sort=" @if(!empty($user->last_login_at)) {{ \Carbon\Carbon::parse( $user->last_login_at)->format('Y/m/d H:i:s')}}  @endif">
                                    {{ \Carbon\Carbon::parse( $user->last_login_at)->format('Y/m/d H:i:s') }}
                                </td>
                                <td data-sort="{{ \Carbon\Carbon::parse( $user->created_at)->format('Y/m/d H:i:s') }}">
                                {{ \Carbon\Carbon::parse( $user->created_at)->format('Y/m/d H:i:s') }}</td>
                                <td>
                                    @if($user->email_verified_at == NULL)
                                    <a
                                        href="{{ route('user.reset-password', $user->email) }}">{{ __('user.account_activation') }}</a>
                                    @elseif($user->status == 1)
                                    <a
                                        href="{{ route('user.reset-password', $user->email) }}">{{ __('user.reset_password') }}</a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('user.edit', $user->id) }}"><i
                                            class="icon-edit-icon left"></i></a>
                                </td>
                            </tr>
                            @endforeach
                            @if ($users->total() == 0)
                            <tr class="noreocrd">
                                <td colspan="10" class="text-center">
                                    {{ __('user.no_result') }}
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
