<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoaderController extends Controller
{
    private $shops_source_url                   = 'https://www.coupons.com/store-loyalty-card-coupons/';
    private $shop_link_class_name               = '.store-pod';
    private $coupon_image_container_class_name  = '.media-object';
    private $coupon_content_class_name          = '.media';
    private $coupon_title_class_name            = '.pod_summary';
    private $coupon_brand_class_name            = '.pod_brand';
    private $coupon_description_class_name      = '.pod_description';
    private $coupon_expiry_class_name           = '.pod_expiry';
}
