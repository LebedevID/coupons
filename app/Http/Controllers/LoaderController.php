<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App;
use DB;

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


    private function delete_shops() {
        App\Shops::query()->delete();
    }
	
	private function get_dom_content($url,$proxy) {	
		if (!empty($proxy)) {
			$context = array(
							 'http' => array(
											'proxy' => 'tcp://'.$proxy,
											'request_fulluri' => true,
											),
								
							  'ssl' => array(
										'verify_peer' => false,
										'verify_peer_name' => false,
										),
							);

			$cxContext = stream_context_create($context);
			
			return file_get_contents($url, null, $cxContext);
		} 
		else
		{			
			return file_get_contents($url);			
		}
	}
	
    private function parse_shops($proxy) {
		$shops_html = $this->get_dom_content($this->shops_source_url,$proxy);

        $shops_parser = new Crawler(null,$this->shops_source_url);
        $shops_parser->addHtmlContent($shops_html, 'UTF-8');
        
        $shops = $shops_parser->filter($this->shop_link_class_name)->each(function (Crawler $node, $i) {
            return [
					'shop_url'   => $node->link()->getUri(),
                    'shop_name'  => $node->attr('title'),
                    'image_url'  => $node->filter('img')->image()->getUri()
					];
        });     

        return $shops;
    }

    private function parse_coupons($shop_url, $shop_id,$proxy) {
        $coupons_html = $this->get_dom_content($shop_url,$proxy);
        
        $coupons_parser = new Crawler(null,$shop_url);
        $coupons_parser->addHtmlContent($coupons_html, 'UTF-8');

        $count = $coupons_parser->filter($this->coupon_content_class_name)->count();

        if ($count>0) {
                $coupons = $coupons_parser
						   ->filter($this->coupon_content_class_name)
						   ->each(function (Crawler $node, $i) use ($shop_id) {
								
								if ($node->filter($this->coupon_title_class_name)->count() > 0) {
									$title = $node->filter($this->coupon_title_class_name)->text();
								}
								else {
									$title = null;
								}
								
								if ($node->filter($this->coupon_brand_class_name)->count() > 0) {
									$brand_name = $node->filter($this->coupon_brand_class_name)->text();
								}
								else {
									$brand_name = null;
								}
								
								if ($node->filter($this->coupon_description_class_name)->count() > 0) {
									$description = $node->filter($this->coupon_description_class_name)->text();
								}
								else {
									$description = null;
								}
								
								if ($node->filter($this->coupon_expiry_class_name)->count() > 0) {
									$expiry_date    = substr($node->filter($this->coupon_expiry_class_name)->text(),5,8);
								}
								else {
									$expiry_date = null;
								}
								
								if ($node->filter($this->coupon_image_container_class_name)->filter('img')->count() > 0) {
									$image_url = $node->filter($this->coupon_image_container_class_name)->filter('img')->image()->getUri();
								}
								else {
									$image_url = null;
								}
								
								return [
										'shop_id'     => $shop_id,
										'title'       => $title,
										'brand_name'  => $brand_name,
										'description' => $description,
										'exp_date'    => $expiry_date,
										'image_url'	  => $image_url
										];

							});    

			return $coupons;
        } 
		else
			return null;
        
    }
	
	
	public function save_shops($shops) {
		if (!empty($shops)) {
			App\Shops::insert($shops);
		}	
	}
	
	
	public function save_coupons($coupons) {	
		if (!empty($coupons)) {
			App\Coupons::insert($coupons);		
		}
	}
	
	
	public function load_shops($proxy) {	
		$this->delete_shops();
		
		echo 'Parsing shops...'.PHP_EOL;
		$shops = $this->parse_shops($proxy);
		echo 'Ok'.PHP_EOL;
		
		echo 'Saving shops...'.PHP_EOL;
		$this->save_shops($shops);
		echo 'Ok'.PHP_EOL;
		
	}

    public function load_coupons($proxy) {   		
		$shops = App\Shops::unloaded_coupons();
		if (count($shops) == 0) {
			App\Shops::where('id', '>', 0)->update(['coupons_loaded' => false]);
			$shops = App\Shops::all();
			if (count($shops) == 0) { 
				echo "No shops.".PHP_EOL;
				return false;
			}
		}
		
		foreach($shops as $shop) {
			echo 'Parsing coupons for:'.$shop['shop_name'].PHP_EOL;
			$coupons = $this->parse_coupons($shop['shop_url'], $shop['id'], $proxy);
			echo 'Ok'.PHP_EOL;
			
			App\Coupons::where('shop_id', $shop['id'])->delete();
			
			$this->save_coupons($coupons);	
			$shop->coupons_loaded = true;
			$shop->save();
			
		}	
		return true;		
    }

}
