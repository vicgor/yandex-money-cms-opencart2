<?php
class ControllerYandexbuyCart extends Controller
{
	public function getinfo()
	{
		$id_product = isset($this->request->post['product_id']) ? $this->request->post['product_id'] : 0;
		$result = array();
		if ($id_product > 0)
		{
			$opt = isset($this->request->post['option']) ? $this->request->post['option'] : array();
			// $opt = array('227' => 18, '228' => 20);
			$opt_flip = array_flip($opt);
			// $id_product = 30;
			$quantity = $this->request->post['quantity'];
			$this->load->model('catalog/product');
			$product_info = $this->model_catalog_product->getProduct($id_product);
			$result = array();
			$name = array();
			$result['data'] = date('Y-m-d H:i:s');
			$result['action'] = 'add';
			$result['name'] = $product_info['name'];
			$result['quantity'] = $product_info['quantity'] ? $product_info['quantity'] : 1;
			$result['price'] = $product_info['special'] ? $product_info['special'] : $product_info['price'];
			if (count($opt))
			{
				$options = $this->model_catalog_product->getProductOptions($id_product);
				if (count($options))
				{
					foreach ($options as $option)
					{
						if (in_array($option['product_option_id'], $opt_flip))
						{
							foreach ($option['product_option_value'] as $o)
							{
								if (in_array($o['product_option_value_id'], $opt))
								{
									$name[] = $o['name'];
									if ($o['price_prefix'] == '+')
										$result['price'] += $o['price'];
									if ($o['price_prefix'] == '-')
										$result['price'] -= $o['price'];
									
								}
							}
						}
					}
				}
			}

			$result['name'] = $result['name'].' '.implode(' ', $name);
		}

		$this->response->addHeader('Content-Type: application/json; charset=utf-8');
		$this->response->setOutput(json_encode($result));
	}

	public function index()
	{
		$sign = $this->config->get('ya_pokupki_stoken');
		$key = isset($_REQUEST['auth-token']) ? $_REQUEST['auth-token'] : '';
		if (strtoupper($sign) != strtoupper($key))
		{
			header('HTTP/1.0 404 Not Found');
			echo '<h1>Wrong token</h1>';
			exit;
		}
		else
		{
			$json = file_get_contents("php://input");
			$this->log_save('pokupki cart request: '.$json);
			if (!$json)
			{
				header('HTTP/1.0 404 Not Found');
				echo '<h1>No data posted</h1>';
				exit;
			}
			else
			{
				$data = json_decode($json);
				$payments = array();
				$carriers = array();
				$items = array();
				$this->load->model('catalog/product');
				$shop_currency = $this->config->get('config_currency');//RUB
				$offers_currency = ($data->cart->currency == 'RUR') ? 'RUB' : $data->cart->currency;
				$decimal_place = $this->currency->getDecimalPlace($offers_currency);
				foreach ($data->cart->items as $item)
				{
					$add = true;
					$id_array = explode('c', $item->offerId);
					$id_product = $id_array[0];
					$price_option = 0;
					$product_info = $this->model_catalog_product->getProduct($id_product);
					if (!$product_info['status'])
						continue;

					if (count($id_array) > 1)
					{
						unset($id_array[0]);
						foreach ($this->model_catalog_product->getProductOptions($id_product) as $option)
						{
							foreach ($option['product_option_value'] as $value)
							{
								if (!in_array($value['option_value_id'], $id_array))
									continue;

								if ($value['quantity'] < $item->count || $value['quantity'] <= 0)
								{
									$add = false;
									break;
								}

								if ($value['price_prefix'] == '+')
									$price_option += $value['price'];
								elseif ($value['price_prefix'] == '-')
									$price_option -= $value['price'];

							}
							
							if (!$add)
								break;
						}
					}

					if ($add)
					{
						if ($item->count < $product_info['minimum'] || $product_info['quantity'] < $item->count || $product_info['quantity'] <= 0)
							continue;

						$count = min($product_info['quantity'], (int)$item->count);
						if ($product_info['special'] && $product_info['special'] < $product_info['price'])
							$total = number_format($this->currency->convert($this->tax->calculate($product_info['special'] + $price_option, $product_info['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');
						else
							$total = number_format($this->currency->convert($this->tax->calculate($product_info['price'] + $price_option, $product_info['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', '');

						$items[] = array(
							'feedId' => $item->feedId,
							'offerId' => $item->offerId,
							'price' => (float)$total,
							'count' => (int)$count,
							'delivery' => true,
						);
					}
				}

				if (count($items))
				{
					$address_array = array(
						'firstname'      => '',
						'lastname'       => '',
						'company'        => '',
						'address_1'      => '',
						'address_2'      => '',
						'postcode'       => '',
						'city'           => '',
						'zone_id'        => '',
						'zone'           => '',
						'zone_code'      => '',
						'country_id'     => '',
						'country'        => '',
						'iso_code_2'     => '',
						'iso_code_3'     => '',
						'address_format' => ''
					);
					
					$this->load->model('extension/extension');
					$results = $this->model_extension_extension->getExtensions('shipping');
					$k = 0;
					foreach ($results as $result)
					{
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('shipping/'.$result['code']);
							$quote = $this->{'model_shipping_'.$result['code']}->getQuote($address_array);
							$id = $result['code'];
							$types = $this->config->get('ya_pokupki_carrier');
							$type = isset($types[$id]) ? $types[$id] : 'POST';
							if ($quote)
							{
								$carriers[$k] = array(
									'id' => $result['extension_id'],
									'serviceName' => $quote['title'],
									'type' => $type,
									'price' => (float)number_format($this->currency->convert($this->tax->calculate($quote['quote'][$result['code']]['cost'], $product_info['tax_class_id'], $this->config->get('config_tax')), $shop_currency, $offers_currency), $decimal_place, '.', ''),
									'dates' => array(
										'fromDate' => date('d-m-Y'),
										'toDate' => date('d-m-Y'),
									),
								);
								
								if($type == 'PICKUP')
								{
									$this->load->model('yamodel/pokupki');
									$this->model_yamodel_pokupki->makeData();
									$outlets = $this->model_yamodel_pokupki->getOutlets();
									$carriers[$k] = array_merge($carriers[$k], $outlets['json']);
								}
								
								$k++;
							}
						}
					}
					
					if ($this->config->get('ya_pokupki_yandex'))
						$payments[] = 'YANDEX';

					if ($this->config->get('ya_pokupki_sprepaid'))
						$payments[] = 'SHOP_PREPAID';

					if ($this->config->get('ya_pokupki_cash'))
						$payments[] = 'CASH_ON_DELIVERY';

					if ($this->config->get('ya_pokupki_bank'))
						$payments[] = 'CARD_ON_DELIVERY';
				}

				$array = array(
					'cart' => array(
						'items' => $items,
						'deliveryOptions' => $carriers,
						'paymentMethods' => $payments
					)
				);

				$this->response->addHeader('Content-Type: application/json; charset=utf-8');
				$this->response->setOutput(json_encode($array));
			}
		}
	}
	
	public static function log_save($logtext)
	{
		$real_log_file = './ya_logs/'.date('Y-m-d').'.log';
		$h = fopen($real_log_file , 'ab');
		fwrite($h, date('Y-m-d H:i:s ') . '[' . addslashes($_SERVER['REMOTE_ADDR']) . '] ' . $logtext . "\n");
		fclose($h);
	}
}