@component('mail::layout')
{{-- Header --}}
@slot('header')
@include('email.admin.includes.header')
@endslot
{{-- Body --}}

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# Hello {{ $contact->first_name }} {{ $contact->last_name }},
@endif
@endif
{{-- Intro Lines --}}
Your Inquiry successfully sent to the administrator.
<br/>
We will contact you as soon as possible.
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
