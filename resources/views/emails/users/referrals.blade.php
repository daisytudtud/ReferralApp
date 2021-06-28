@component('mail::message')
{{ $name }} has been using ProductY, and thinks it could be of use for you.

Here’s their invitation link for you:<br />
<a href="{{ $referral_link }}">{{ $referral_link }}</a>

Thanks,<br />
{{ config('app.name') }}
@endcomponent
