@component('mail::message')

@component('mail::panel')
# Thanks for Your Quote Request
Hi **{{$quotation->name}}**, we've received your quote request and a member of our sales team will be in touch with you within 24 hours.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
