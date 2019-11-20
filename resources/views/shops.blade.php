<html>
    <head>
    </head>

    <body>
        @if (count($shops)>0)
            @foreach($shops as $shop)
                <a href='shops/{{$shop->id}}/coupons'><img src="{{$shop->image_url}}" alt="{{$shop->shop_name}}"></a>
            @endforeach       
        @else
            Нет данных.
        @endif 
    </body>
</html>