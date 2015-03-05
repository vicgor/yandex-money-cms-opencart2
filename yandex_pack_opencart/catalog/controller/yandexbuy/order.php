<?php
class ControllerYandexbuyOrder extends Controller
{
	public function getRegion($data, $type)
	{
		if (isset($data->type))
		{
			if ($data->type == $type)
				return $data->name;
			else
				return $this->getRegion($data->parent, $type);
		}
		else
			return '';
	}

	public function getShipping($id)
	{
		$this->load->model('extension/extension');
		$results = $this->model_extension_extension->getExtensions('shipping');
		foreach ($results as $res)
			if ($res['extension_id'] == $id)
				return $res['code'].'.'.$res['code'];
		
		return '';
	}
	
	public function accept()
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
			$this->log_save('pokupki order accept: '.$json);
			if (!$json)
			{
				header('HTTP/1.0 404 Not Found');
				echo '<h1>No data posted</h1>';
				exit;
			}
			else
			{
				$data = json_decode($json);
				$this->load->model('catalog/product');
				$this->load->model('account/customer');
				$this->load->model('account/address');
				$this->load->model('checkout/order');
				$shop_currency = $this->config->get('config_currency');//RUB
				$offers_currency = ($data->order->currency == 'RUR') ? 'RUB' : $data->order->currency;
				$decimal_place = $this->currency->getDecimalPlace($offers_currency);
				$order_data = array();
				$items = $data->order->items;
				$resultat = true;
				if (count($items))
				{
					$this->cart->clear();
					$count_items = 0;
					foreach ($items as $item)
					{
						$opt = array();
						$id_array = explode('c', $item->offerId);
						$id_product = $id_array[0];
						$product_info = $this->model_catalog_product->getProduct($id_product);
						if (count($id_array) > 1)
						{
							unset($id_array[0]);
							foreach ($this->model_catalog_product->getProductOptions($id_product) as $options)
							{
								foreach ($options['product_option_value'] as $option)
								{
									if (in_array($option['option_value_id'], $id_array))
										$opt[$options['product_option_id']] = $option['product_option_value_id'];
								}
							}
						}
						$count_items += (int)$item->count;
						$this->cart->add($id_product, $item->count, $opt);
					}

					if ($this->cart->countProducts() == $count_items)
					{
						$taxes = $this->cart->getTaxes();
						$this->session->data['customer_id'] = '';
						$message = isset($data->order->notes) ? $data->order->notes : null;
						$customer_info = $this->model_account_customer->getCustomer($this->config->get('yandexbuy_customer'));
						$this->session->data['customer_id'] = $customer_info['customer_id'];
						$delivery = isset($data->order->delivery->address) ? $data->order->delivery->address : new stdClass();
						$street = isset($delivery->street) ? ' Улица: '.$delivery->street : 'Самовывоз';
						$subway = isset($delivery->subway) ? ' Метро: '.$delivery->subway : '';
						$block = isset($delivery->block) ? ' Корпус/Строение: '.$delivery->block : '';
						$floor = isset($delivery->floor) ? ' Этаж: '.$delivery->floor : '';
						$house = isset($delivery->house) ? ' Дом: '.$delivery->house : '';
						$address1 = $street.$subway.$block.$floor.$house;
						$order_data['customer_id'] = $this->customer->getId();
						$order_data['customer_group_id'] = $customer_info['customer_group_id'];
						$order_data['firstname'] = $customer_info['firstname'];
						$order_data['lastname'] = $customer_info['lastname'];
						$order_data['email'] = $customer_info['email'];
						$order_data['telephone'] = $customer_info['telephone'];
						$order_data['fax'] = $customer_info['fax'];
						$order_data['shipping_firstname'] = $customer_info['firstname'];
						$order_data['shipping_lastname'] = $customer_info['lastname'];
						$order_data['shipping_company'] = '';
						$order_data['shipping_address_1'] = $address1;
						$order_data['shipping_city'] = isset($delivery->city) ? $delivery->city : 'Город';
						$order_data['shipping_postcode'] = isset($delivery->postcode) ? $delivery->postcode : 000000;
						$order_data['shipping_zone'] = $this->getRegion($data->order->delivery->region, 'SUBJECT_FEDERATION');
						$order_data['shipping_zone_id'] = '';
						$order_data['shipping_country'] = $data->order->delivery->address->country;
						$order_data['shipping_country_id'] = '';
						$order_data['shipping_address_format'] = '';
						$order_data['shipping_method'] = $data->order->delivery->serviceName;
						$order_data['shipping_code'] = $this->getShipping($data->order->delivery->id);
						$order_data['shipping_address_2'] = '';
						$order_data['payment_firstname'] = $customer_info['firstname'];
						$order_data['payment_lastname'] = $customer_info['lastname'];
						$order_data['payment_address_1'] = $address1;
						$order_data['payment_city'] = isset($delivery->city) ? $delivery->city : 'Город';
						$order_data['payment_postcode'] = isset($delivery->postcode) ? $delivery->postcode : 000000;
						$order_data['payment_zone'] = $this->getRegion($data->order->delivery->region, 'SUBJECT_FEDERATION');
						$order_data['payment_zone_id'] = '';
						$order_data['payment_country'] = $data->order->delivery->address->country;
						$order_data['payment_country_id'] = '';
						$order_data['payment_address_format'] = '';
						$order_data['payment_method'] = $data->order->paymentMethod;
						$order_data['payment_address_2'] = '';
						$order_data['payment_code'] = 'yamodule';
						$order_data['language_id'] = $this->config->get('config_language_id');
						$order_data['currency_id'] = $this->currency->getId();
						$order_data['currency_code'] = $this->currency->getCode();
						$order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
						$order_data['ip'] = $this->request->server['REMOTE_ADDR'];
						$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
						$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
						$order_data['accept_language'] = '';
						$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
						$order_data['store_id'] = $this->config->get('config_store_id');
						$order_data['store_name'] = $this->config->get('config_name');
						$order_data['store_url'] = $this->config->get('config_url');
						$order_data['comment'] = $message;
						$order_data['products'] = array();
						$order_data['vouchers'] = array();
						$order_data['affiliate_id'] = 0;
						$order_data['commission'] = 0;
						$order_data['marketing_id'] = 0;
						$order_data['tracking'] = '';
						$order_data['totals'] = array();
						$order_data['payment_company'] = '';
						$this->load->model('extension/extension');
						$sort_order = array();
						$total = 0;
						$results = $this->model_extension_extension->getExtensions('total');
						foreach ($results as $key => $value) {
							$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
						}
						array_multisort($sort_order, SORT_ASC, $results);
						foreach ($results as $result) {
							if ($this->config->get($result['code'] . '_status')) {
								$this->load->model('total/' . $result['code']);

								$this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
							}
						}
						
						$order_data['total'] = $total;
						foreach ($this->cart->getProducts() as $product) {
							$option_data = array();

							foreach ($product['option'] as $option) {
								$option_data[] = array(
									'product_option_id'       => $option['product_option_id'],
									'product_option_value_id' => $option['product_option_value_id'],
									'option_id'               => $option['option_id'],
									'option_value_id'         => $option['option_value_id'],
									'name'                    => $option['name'],
									'value'                   => $option['value'],
									'type'                    => $option['type']
								);
							}

							$order_data['products'][] = array(
								'product_id' => $product['product_id'],
								'name'       => $product['name'],
								'model'      => $product['model'],
								'option'     => $option_data,
								'download'   => $product['download'],
								'quantity'   => $product['quantity'],
								'subtract'   => $product['subtract'],
								'price'      => $product['price'],
								'total'      => $product['total'],
								'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
								'reward'     => $product['reward']
							);
						}
						
						$id_order = $this->model_checkout_order->addOrder($order_data);
						$this->model_checkout_order->addOrderHistory($id_order, 1, 'Создание заказа Yandex', false);
						$this->session->data['order_id'] = $id_order;
						$values_to_insert = array(
							'id_order' => (int)$id_order,
							'id_market_order' => (int)$data->order->id,
							'ptype' => $data->order->paymentType,
							'pmethod' => $data->order->paymentMethod,
							'home' => isset($data->order->delivery->address->house) ? $data->order->delivery->address->house : 0,
							'outlet' => isset($data->order->delivery->outlet->id) ? $data->order->delivery->outlet->id : '',
							'currency' => $data->order->currency
						);
						
						$request = '';
						foreach ($values_to_insert as $key => $val)
							if (!empty($val))
								$request .= ' `'.$key.'` = "'.$this->db->escape($val).'",';

						$this->db->query('INSERT INTO '.DB_PREFIX.'pokupki_orders SET '.trim($request, ','));

					}
					else
						$resultat = false;
				}
				else
					$resultat = false;
				
				if($resultat)
				{
					$array = array(
						'order' => array(
							'accepted' => true,
							'id' => (string)$id_order,
						)
					);
				}
				else
				{
					$array = array(
						'order' => array(
							'accepted' => false,
							'reason' => 'OUT_OF_DATE'
						)
					);
				}

				$this->response->addHeader('Content-Type: application/json; charset=utf-8');
				$this->response->setOutput(json_encode($array));
			}
		}
	}
	
	public function getShopOrderId($id)
	{
		$query = $this->db->query('SELECT * FROM '.DB_PREFIX.'pokupki_orders WHERE `id_market_order` = '.(int)$id);
		return $query->row;
	}

	public function status()
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
			$this->log_save('pokupki order status: '.$json);
			if (!$json)
			{
				header('HTTP/1.0 404 Not Found');
				echo '<h1>No data posted</h1>';
				exit;
			}
			else
			{
				$data = json_decode($json);
				$shop_order = $this->getShopOrderId($data->order->id);
				if ($shop_order['id_order'])
				{
					$this->load->model('account/customer');
					$this->load->model('account/address');
					$this->load->model('checkout/order');
					$order = $this->model_checkout_order->getOrder($shop_order['id_order']);

					if ($data->order->status == 'UNPAID')
						$this->model_checkout_order->addOrderHistory($shop_order['id_order'], 13, 'Yandex.Покупки', false);

					if ($data->order->status == 'CANCELLED')
						$this->model_checkout_order->addOrderHistory($shop_order['id_order'], 7, (isset($data->order->substatus) ? $data->order->substatus : null), false);

					if ($data->order->status == 'PROCESSING')
					{
						$buyer = isset($data->order->buyer) ? $data->order->buyer : '';
						$customer_info = $this->model_account_customer->getCustomerByEmail($buyer->email);
						if (!$customer_info)
						{
							$delivery = isset($data->order->delivery->address) ? $data->order->delivery->address : new stdClass();
							$street = isset($delivery->street) ? ' Улица: '.$delivery->street : 'Самовывоз';
							$subway = isset($delivery->subway) ? ' Метро: '.$delivery->subway : '';
							$block = isset($delivery->block) ? ' Корпус/Строение: '.$delivery->block : '';
							$floor = isset($delivery->floor) ? ' Этаж: '.$delivery->floor : '';
							$house = isset($delivery->house) ? ' Дом: '.$delivery->house : '';
							$address1 = $street.$subway.$block.$floor.$house;
							$user = array();
							$user['firstname'] = $buyer->firstName;
							$user['lastname'] = $buyer->lastName;
							$user['email'] = $buyer->email;
							$user['telephone'] = isset($buyer->phone) ? $buyer->phone : 999999;
							$user['address_1'] = $address1;
							$user['postcode'] = isset($delivery->postcode) ? $delivery->postcode : 000000;
							$user['city'] = isset($delivery->city) ? $delivery->city : 'Город';
							$user['country_id'] = '';
							$user['fax'] = '';
							$user['company'] = '';
							$user['address_2'] = '';
							$user['zone_id'] = '';
							$user['password'] = rand(100000, 500000);
							$customer_id = $this->model_account_customer->addCustomer($user);
							$customer_info = $this->model_account_customer->getCustomer($customer_id);;
						}

						$order['customer_id'] = $customer_info['customer_id'];
						$order = array_merge($order, $customer_info);
						$order['payment_firstname'] = $customer_info['firstname'];
						$order['payment_lastname'] = $customer_info['lastname'];
						$order['firstname'] = $customer_info['firstname'];
						$order['lastname'] = $customer_info['lastname'];
						$order['shipping_firstname'] = $customer_info['firstname'];
						$order['shipping_lastname'] = $customer_info['lastname'];
						$order['telephone'] = isset($buyer->phone) ? $buyer->phone : 999999;
						$this->editOrder($shop_order['id_order'], $order);
						$this->model_checkout_order->addOrderHistory($shop_order['id_order'], 2, 'Yandex.Покупки', false);
					}
				}
				
				die();
			}
		}
	}
	
	public function editOrder($order_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int)$data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int)$data['customer_id'] . "', customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(serialize($data['custom_field'])) . "', payment_firstname = '" . $this->db->escape($data['payment_firstname']) . "', payment_lastname = '" . $this->db->escape($data['payment_lastname']) . "', payment_company = '" . $this->db->escape($data['payment_company']) . "', payment_address_1 = '" . $this->db->escape($data['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($data['payment_address_2']) . "', payment_city = '" . $this->db->escape($data['payment_city']) . "', payment_postcode = '" . $this->db->escape($data['payment_postcode']) . "', payment_country = '" . $this->db->escape($data['payment_country']) . "', payment_country_id = '" . (int)$data['payment_country_id'] . "', payment_zone = '" . $this->db->escape($data['payment_zone']) . "', payment_zone_id = '" . (int)$data['payment_zone_id'] . "', payment_address_format = '" . $this->db->escape($data['payment_address_format']) . "', payment_custom_field = '" . $this->db->escape(serialize($data['payment_custom_field'])) . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_firstname = '" . $this->db->escape($data['shipping_firstname']) . "', shipping_lastname = '" . $this->db->escape($data['shipping_lastname']) . "', shipping_company = '" . $this->db->escape($data['shipping_company']) . "', shipping_address_1 = '" . $this->db->escape($data['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($data['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($data['shipping_city']) . "', shipping_postcode = '" . $this->db->escape($data['shipping_postcode']) . "', shipping_country = '" . $this->db->escape($data['shipping_country']) . "', shipping_country_id = '" . (int)$data['shipping_country_id'] . "', shipping_zone = '" . $this->db->escape($data['shipping_zone']) . "', shipping_zone_id = '" . (int)$data['shipping_zone_id'] . "', shipping_address_format = '" . $this->db->escape($data['shipping_address_format']) . "', shipping_custom_field = '" . $this->db->escape(serialize($data['shipping_custom_field'])) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float)$data['total'] . "', affiliate_id = '" . (int)$data['affiliate_id'] . "', commission = '" . (float)$data['commission'] . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
	}
	
	public static function log_save($logtext)
	{
		$real_log_file = './ya_logs/'.date('Y-m-d').'.log';
		$h = fopen($real_log_file , 'ab');
		fwrite($h, date('Y-m-d H:i:s ') . '[' . addslashes($_SERVER['REMOTE_ADDR']) . '] ' . $logtext . "\n");
		fclose($h);
	}
}