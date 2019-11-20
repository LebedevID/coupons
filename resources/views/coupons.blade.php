<html>
    <head>
    </head>

    <body>
        @if (count($coupons)>0)
            @foreach($coupons as $coupon)
                <img src="{{$coupon->image_url}}" alt="{{$coupon->title}}">
            @endforeach       
        @else
            Нет данных.
        @endif 
    </body>
</html>