<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .pagebreak {
            clear: both;
            page-break-after: always;
        }
    </style>
</head>
</head>
<body>
@foreach($subscribes as $subscribe)
    <img src="{{ env('APP_URL') }}/dist/img/pdf_logo.jpg" style="width:150px;height:150px;">
    <h3>{{ $subscribe->subscribeTypeSetting->value }} за {{ $month_str }}</h3>
    <hr/>
    <p>Подписчик: {{ $subscribe->subscriber->name }}</p>
    <p>Телефон: {{ $subscribe->subscriber->phone_str }}</p>
    <p>Email: {{ $subscribe->subscriber->email }}</p>
    <p>Адрес доставки: {{ $subscribe->deliverySetting->value }}. {{ $subscribe->delivery_addr }}</p>
    <hr/>
    Содержимое:
    @foreach($subscribe->subscribeConsistByMonth($month_int) as $subscribeProduct)
        <br/>{{ $subscribeProduct->article }}, {{ $subscribeProduct->name }}: {{ $subscribeProduct->pivot->qnt }}шт
    @endforeach
    @if(!$loop->last)
        <div class="pagebreak"> </div>
    @endif
@endforeach

</body>
</html>
