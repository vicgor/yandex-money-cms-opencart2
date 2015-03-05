<?php

Class ModelYamodelPokupki extends Model
{

	public function getscript($id)
	{
		$this->load->model('checkout/order');
		$order = $this->model_checkout_order->getOrder($id);
		$product_array = $this->getOrderProducts($id);
		$ret = array();
		$data = '';
		$ret['order_price'] = $order['total'].' '.$order['currency_code'];
		$ret['order_id'] = $order['order_id'];
		$ret['currency'] = $order['currency_code'];
		$ret['payment'] = $order['payment_method'];
		$products = array();
		foreach($product_array as $k => $product)
		{
			$products[$k]['id'] = $product['product_id'];
			$products[$k]['name'] = $product['name'];
			$products[$k]['quantity'] = $product['quantity'];
			$products[$k]['price'] = $product['price'];
		}

		$ret['goods'] = $products;
		if ($this->config->get('ya_metrika_order'))
			$data = '<script>
					$(window).load(function() {
							metrikaReach(\'metrikaOrder\', '.json_encode($ret).');
					});
					</script>
			';

		return $data;
	}

	public function getOrderProducts($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function makeData()
	{
		$this->app_id = $this->config->get('ya_pokupki_idapp');
		$this->url = $this->config->get('ya_pokupki_yapi');
		$this->number = $this->config->get('ya_pokupki_number');
		$this->login = $this->config->get('ya_pokupki_login');
		$this->app_pw = $this->config->get('ya_pokupki_upw');
		$this->ya_token = $this->config->get('ya_pokupki_gtoken');
	}

	public function getOrders()
	{
		return $data = $this->SendResponse('/campaigns/'.$this->number.'/orders', array(), array(), 'GET');
	}

	public function getOutlets()
	{
		$data = $this->SendResponse('/campaigns/'.$this->number.'/outlets', array(), array(), 'GET');
		$array = array('outlets' => array());
		foreach($data->outlets as $o)
			$array['outlets'][] = array('id' => (int)$o->shopOutletId);
		$return = array(
			'json' => $array,
			'array' => $data->outlets
		);

		return $return;
	}

	public function getOrder($id)
	{
		$data = $this->SendResponse('/campaigns/'.$this->number.'/orders/'.$id, array(), array(), 'GET');
		return $data;
	}

	public function sendOrder($state, $id)
	{
		$params = array(
			'order' => array(
				'status' => $state,
			)
		);

		if($state == 'CANCELLED')
			$params['order']['substatus'] = 'SHOP_FAILED';

		return $data = $this->SendResponse('/campaigns/'.$this->number.'/orders/'.$id.'/status', array(), $params, 'PUT');
	}

	public function SendResponse($to, $headers, $params, $type)
	{
		$response = $this->post($this->url.$to.'.json?oauth_token='.$this->ya_token.'&oauth_client_id='.$this->app_id.'&oauth_login='.$this->login, $headers, $params, $type);
		$data = json_decode($response->body);
		if(isset($data->error))
			$this->log_save($response->body);
		if($response->status_code == 200)
			return $data;
		else
			die(print_r($response));
	}

	public static function log_save($logtext)
	{
		$real_log_file = './ya_logs/'.date('Y-m-d').'.log';
		$h = fopen($real_log_file , 'ab');
		fwrite($h, date('Y-m-d H:i:s ') . '[' . addslashes($_SERVER['REMOTE_ADDR']) . '] ' . $logtext . "\n");
		fclose($h);
	}

	public static function post($url, $headers, $params, $type){
		$curlOpt = array(
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLINFO_HEADER_OUT => 1,
		);

		switch (strtoupper($type)){
			case 'DELETE':
				$curlOpt[CURLOPT_CUSTOMREQUEST] = "DELETE";
			case 'GET':
				if (!empty($params))
					$url .= (strpos($url, '?')===false ? '?' : '&') . http_build_query($params);
			break;
			case 'PUT':
				$headers[] = 'Content-Type: application/json;';
				$body = json_encode($params);
				$fp = tmpfile();
				fwrite($fp, $body, strlen($body));
				fseek($fp, 0);
				$curlOpt[CURLOPT_PUT] = true;
				$curlOpt[CURLOPT_INFILE] = $fp;
				$curlOpt[CURLOPT_INFILESIZE] = strlen($body);
			break;
		}

		$curlOpt[CURLOPT_HTTPHEADER] = $headers;
		$curl = curl_init($url);
		curl_setopt_array($curl, $curlOpt);
		$rbody = curl_exec($curl);
		$errno = curl_errno($curl);
		$error = curl_error($curl);
		$rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// Tools::d(curl_getinfo($curl, CURLINFO_HEADER_OUT));
		curl_close($curl);
		$result = new stdClass();
		$result->status_code = $rcode;
		$result->body = $rbody;
		$result->error = $error;
		return $result;
	}
}