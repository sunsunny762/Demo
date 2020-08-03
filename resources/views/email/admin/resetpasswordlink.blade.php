@component('mail::layout')
{{-- Header --}}
@slot('header')
@include('email.admin.includes.header')
@endslot
{{-- Body --}}
<!-- Email Body -->
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# Hello {{ $user->first_name }} {{ $user->last_name }},
@endif
@endif
<p>You are receiving this email because we received a password reset request for your account.</p>
<center><a href="{{ $reset_link }}" class="button button-primary" target="_blank" >Reset Password</a></center>
<p>This password reset link will expire in {{ floor(config('auth.passwords.users.expire')/ 60) }} hours.</p>
<p>If you did not request a password reset, no further action is required.</p>
@if (! empty($salutation))
<p>{{ $salutation }}</p>
@endif
{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset
{{-- Footer --}}
@slot('footer')
@include('email.admin.includes.footer')
@endslot
@endcomponent
