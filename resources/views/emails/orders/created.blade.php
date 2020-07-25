@component('mail::message')

@component('mail::panel')
# Thanks for Your Order
Hi **{{$order->name}}**, we've received your order and a member of our sales team will be in touch with you within 24 hours.
@endcomponent

@component('mail::button', ['url' => config('app.url').'/order/show/'.$order->order_no])
View Order Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
