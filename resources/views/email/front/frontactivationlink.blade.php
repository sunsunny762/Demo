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
<p> @lang('Please click the button below to verify your email address.')</p>
<center><a href="{{ $veryfy_link }}" class="button button-primary" target="_blank" >@lang('Verify Email Address')</a></center>
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
@if (! empty($salutation))
{{ $salutation }}
@else
@include('email.admin.includes.footer')
@endif
@endslot
@endcomponent
