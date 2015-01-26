<?php
class ControllerFeedYamarket extends Controller {

	public function index()
	{
		$this->load->model('yamodel/yamarket');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('localisation/currency');
		$categories = $this->model_yamodel_yamarket->getCategories();
		$allow_cat_array = $this->config->get('ya_market_categories');
		$ids_cat = implode(',', $allow_cat_array);
		if($this->config->get('ya_market_catall'))
			$ids_cat = '';
		$products = $this->model_yamodel_yamarket->getProducts($ids_cat, true);
		$currencies = $this->model_localisation_currency->getCurrencies();
		$offers_currency = 'RUB';
		$currency_default = $this->model_yamodel_yamarket->getCurrencyByISO($offers_currency);
		$decimal_place = $this->currency->getDecimalPlace($offers_currency);
		$shop_currency = $this->config->get('config_currency');
		$supported_currencies = array('RUR', 'RUB', 'USD', 'EUR', 'UAH');
		$currencies = array_intersect_key($currencies, array_flip($supported_currencies));
		$y_sname = $this->config->get('ya_market_shopname');
		$y_name = $this->config->get('config_name');
		$shop_url = $this->config->get('config_url');
		$this->model_yamodel_yamarket->yml('utf-8');
		$this->model_yamodel_yamarket->set_shop($y_sname, $y_name, $shop_url);
		$data = array();
		if ($this->config->get('ya_market_allcurrencies'))
		{
			foreach ($currencies as $currency)
				if ($currency['status'] == 1)
					$this->model_yamodel_yamarket->add_currency($currency['code'], ((float)$currency_default['value']/(float)$currency['value']));
		}
		else
			$this->model_yamodel_yamarket->add_currency($currency_default['code'], ((float)$currency_default['value']));

		foreach ($categories as $category)
		{
			if (!$this->config->get('ya_market_catall'))
				if(!in_array($category['category_id'], $allow_cat_array))
					continue;

			$this->model_yamodel_yamarket->add_category($category['name'], $category['category_id'], $category['parent_id']);
		}

		$data_product =array();
		foreach ($products as $product)
		{
			// if($product['product_id'] != 30)
				// continue;

			if ($this->config->get('ya_market_available'))
				if($product['quantity'] < 1)
					return;

			$available = 'false';
			if ($this->config->get('ya_market_set_available') == 1)
				$available = 'true';
			elseif ($this->config->get('ya_market_set_available') == 2)
			{
				if ($product['quantity'] > 0)
					$available = 'true';
			}
			elseif ($this->config->get('ya_market_set_available') == 3)
			{
				$available = 'true';
				if ($product['quantity'] == 0)
					return;
			}
			elseif ($this->config->get('ya_market_set_available') == 4)
				$available = 'false';

			$data = array();
			$data['id'] = $product['product_id'];
			$data['available'] = $available;
			$data['url'] = str_replace('https://', 'http://', $this->url->link('product/product', 'product_id=' . $product['product_id']));
			$data['price'] = $product['price'];
			if ($product['special'] && $product['special'] < $product['price'])
				$data['price'] = $product['special'];
			$data['currencyId'] = $currency_default['code'];
			$data['categoryId'] = $product['category_id'];
			$data['vendor'] = $product['manufacturer'];
			$data['vendorCode'] = $product['model'];
			$data['delivery'] = ($this->config->get('ya_market_delivery') ? 'true' : 'false');
			$data['pickup'] = ($this->config->get('ya_market_pickup') ? 'true' : 'false');
			$data['store'] = ($this->config->get('ya_market_store') ? 'true' : 'false');
			$data['description'] = $product['description'];
			$data['picture'] = array();
			if ($product['minimum'] > 1)
					$data['sales_notes'] = 'Минимальное кол-во для заказа: '.$product['minimum'];
			foreach ($this->model_catalog_product->getProductImages($data['id']) as $pic)
				$data['picture'][] = $this->model_tool_image->resize($pic['image'], 600, 600);

			if ($this->config->get('ya_market_prostoy'))
			{
				$data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
				$data['name'] = $product['name'];
				if ($data['price'] > 0)
					$this->model_yamodel_yamarket->add_offer($data['id'], $data, $data['available']);
			}
			else
			{
				$data['model'] = $product['name'];
				if ($product['weight'] > 0)
					$data['weight'] = number_format($product['weight'], 1, '.', '');
				if ($this->config->get('ya_market_dimensions') && $product['length'] > 0 && $product['width'] > 0 && $product['height'] > 0)
					$data['dimensions'] = number_format($product['length'], 1, '.', '').'/'.number_format($product['width'], 1, '.', '').'/'.number_format($product['height'], 1, '.', '');
				$data['downloadable'] = 'false';
				$data['rec'] = explode(',', $product['rel']);
				$data['param'][] = array('id' => 'weight', 'name' => 'Вес', 'value' => number_format($product['weight'], 1, '.', ''), 'unit' => $product['weight_unit']);
				if ($this->config->get('ya_market_features'))
				{
					$attributes = $this->model_catalog_product->getProductAttributes($data['id']);
					if (count($attributes))
						foreach ($attributes as $attr)
							foreach ($attr['attribute'] as $val)
								$data['param'][] = array('id' => $val['attribute_id'], 'name' => $val['name'], 'value' => $val['text']);
				}
				
				if (!$this->makeOfferCombination($data, $product, $shop_currency, $offers_currency, $decimal_place, $this->model_yamodel_yamarket))
				{
					$data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
					if ($data['price'] > 0)
						$this->model_yamodel_yamarket->add_offer($data['id'], $data, $data['available']);
				}
			}
		}

		$this->response->addHeader('Content-Type: application/xml; charset=utf-8');
		$this->response->setOutput($this->model_yamodel_yamarket->get_xml());
	}
	
	public function makeOfferCombination($data, $product, $shop_currency, $offers_currency, $decimal_place, $object)
	{
		$colors = array();
		$sizes = array();
		if (count($this->config->get('ya_market_color_options')))
			$colors = $this->model_yamodel_yamarket->getProductOptions($this->config->get('ya_market_color_options'), $product['product_id']);
		if (count($this->config->get('ya_market_size_options')))
			$sizes = $this->model_yamodel_yamarket->getProductOptions($this->config->get('ya_market_size_options'), $product['product_id']);
		if (!count($colors) && !count($sizes))
			return false;

		if(count($colors))
		{
			foreach ($colors as $option)
			{
				$data_temp = $data;
				$data_temp['model'].= ', '.$option['option_name'].' '.$option['name'];
				$data_temp['param'][] = array('name' => $option['option_name'], 'value' => $option['name']);
				$data_temp['id'] = $product['product_id'].'c'.$option['option_value_id'];
				$data_temp['available'] = $data['available'];
				if ($option['price_prefix'] == '+') {
					$data_temp['price']+= $option['price'];
					if (isset($data_temp['oldprice']))
						$data_temp['oldprice']+= $option['price'];
				}
				elseif ($option['price_prefix'] == '-') {
					$data_temp['price']-= $option['price'];
					if (isset($data_temp['oldprice']))
						$data_temp['oldprice']-= $option['price'];
				}
				elseif ($option['price_prefix'] == '=') {
					$data_temp['price'] = $option['price'];
				}
				$data_temp = $this->setOptionedWeight($data_temp, $option);
				$data_temp['url'].= '#'.$option['product_option_value_id'];
				$colors_array[] = $data_temp;
			}
		}	
		else
		{
			$colors_array[] = $data;
		}
		
		unset($data_temp);
		unset($option);
		foreach($colors_array as $i => $data)
			if(count($sizes))
			{
				foreach ($sizes as $option)
				{
					$data_temp = $data;
					$data_temp['id'] .= 'c'.$option['option_value_id'];
					$data_temp['model'].= ', '.$option['option_name'].' '.$option['name'];
					$data_temp['param'][] = array('name' => $option['option_name'], 'value' => $option['name']);
					$data_temp['available'] = $data['available'];
					if ($option['price_prefix'] == '+') {
						$data_temp['price']+= $option['price'];
						if (isset($data_temp['oldprice']))
							$data_temp['oldprice']+= $option['price'];
					}
					elseif ($option['price_prefix'] == '-') {
						$data_temp['price']-= $option['price'];
						if (isset($data_temp['oldprice']))
							$data_temp['oldprice']-= $option['price'];
					}
					elseif ($option['price_prefix'] == '=') {
						$data_temp['price'] = $option['price'];
					}
					
					$data_temp = $this->setOptionedWeight($data_temp, $option);
					if (count($colors))
						$data_temp['url'].= '-'.$option['product_option_value_id'];
					else
						$data_temp['url'].= '#'.$option['product_option_value_id'];

					$data_temp['price'] = number_format($this->currency->convert($this->tax->calculate($data_temp['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
					if (isset($data_temp['oldprice']))
						$data_temp['oldprice'] = number_format($this->currency->convert($this->tax->calculate($data_temp['oldprice'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
					if ($data['price'] > 0) {
						$object->add_offer($data_temp['id'], $data_temp, $data_temp['available']);
					}
					unset($data_temp);
				}
			}
			else
			{
				$data['price'] = number_format($this->currency->convert($this->tax->calculate($data['price'], $product['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');				
				if ($data['price'] > 0) {
					$object->add_offer($data['id'], $data, $data['available']);
				}
			}

		return true;
	}
	
	protected function setOptionedWeight($product, $option) {
		if (isset($option['weight']) && isset($option['weight_prefix'])) {
			foreach ($product['param'] as $i=>$param) {
				if (isset($param['id']) && ($param['id'] == 'WEIGHT')) {
					if ($option['weight_prefix'] == '+')
						$product['param'][$i]['value']+= $option['weight'];
					elseif ($option['weight_prefix'] == '-')
						$product['param'][$i]['value']-= $option['weight'];
					break;
				}
			}
		}
		return $product;
	}
}