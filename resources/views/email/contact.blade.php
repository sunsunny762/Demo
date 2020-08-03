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
@endif
@endif
{{-- Intro Lines --}}
A customer is interested in contacting you.
<br>
<b>Name:</b> {{ $contact->first_name }}&nbsp;{{ $contact->last_name }} <br>
<b>Email:</b> <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a> <br>
<b>Page:</b> <a href="{{ (@unserialize($contact->page) !== false) ? (unserialize($contact->page)['page']) ? unserialize($contact->page)['page'] : '' : '' }}" target="_blank">{{ (@unserialize($contact->page) !== false) ? (unserialize($contact->page)['page_name']) ? unserialize($contact->page)['page_name'] : '' : '' }}</a><br>
@empty(!$contact->phone)
<b>Phone:</b> {{ $contact->phone }} <br>
@endempty
<b>Message:</b> {!! nl2br($contact->message) !!}<br>
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
