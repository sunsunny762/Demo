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
<p> @lang('You have been invited to be an admin user for ')<a href="{{$domain }}">{{$domain }}</a>. @lang('This means you will be able to edit the content on the website in real-time.')</p>
<p> @lang('Please confirm and set up your password below.')</p>
<a href="{{ $veryfy_link }}" class="button button-primary" target="_blank" >@lang('ACTIVATE MY ACCOUNT')</a>
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
