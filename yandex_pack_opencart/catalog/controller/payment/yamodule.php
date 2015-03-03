<?php
class ControllerPaymentYamodule extends Controller
{
	const MONEY_URL = "https://money.yandex.ru";
    const SP_MONEY_URL = "https://sp-money.yandex.ru";
	public $error;
	public $errors;

	public function index() {
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->language->load('payment/yamodule');
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['p2p_action'] = $this->url->link('payment/yamodule/yaredirect', '', 'SSL');
		if ($this->config->get('ya_kassa_test'))
			$data['kassa_action'] = 'https://demomoney.yandex.ru/eshop.xml';
		else
			$data['kassa_action'] = 'https://money.yandex.ru/eshop.xml';

		$data['order_id'] = $this->session->data['order_id'];
		$data['p2p_mode'] = $this->config->get('ya_p2p_active');
		$data['kassa_mode'] = $this->config->get('ya_kassa_active');
		$data['shop_id'] = $this->config->get('ya_kassa_sid');
		$data['scid'] = $this->config->get('ya_kassa_scid');
		$data['customerNumber'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_address_1'] . ' ' . $order_info['payment_address_2'] . ' ' . $order_info['payment_city'] . ' ' . $order_info['email'];
		$data['shopSuccessURL'] = $this->url->link('checkout/success', '', 'SSL');
		$data['shopFailURL'] = $this->url->link('checkout/failure', '', 'SSL');
		$data['comment'] = $order_info['comment'];
		$data['sum'] = number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2);
		$data['method_ym'] = $this->config->get('ya_kassa_wallet');
		$data['method_cards'] = $this->config->get('ya_kassa_card');
		$data['method_cash'] = $this->config->get('ya_kassa_terminal');
		$data['method_mobile'] = $this->config->get('ya_kassa_mobile');
		$data['method_wm'] = $this->config->get('ya_kassa_wm');
		$data['method_sb'] = $this->config->get('ya_kassa_sber');
		$data['method_alfa'] = $this->config->get('ya_kassa_alfa');
		$data['method_label'] =  $this->language->get('text_method');
		$data['method_ym_text'] =  $this->language->get('text_method_ym');
		$data['method_cards_text'] =  $this->language->get('text_method_cards');
		$data['method_cash_text'] =  $this->language->get('text_method_cash');
		$data['method_mobile_text'] =  $this->language->get('text_method_mobile');
		$data['method_wm_text'] =  $this->language->get('text_method_wm');
		$data['method_sber_text'] =  $this->language->get('text_method_sber');
		$data['method_alfa_text'] =  $this->language->get('text_method_alfa');
		$data['order_text'] =  $this->language->get('text_order');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/yamodule.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/yamodule.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/yamodule.tpl', $data);
		}
	}

	public static function log_save($logtext)
	{
		$real_log_file = './'.date('Y-m-d').'.log';
		$h = fopen($real_log_file , 'ab');
		fwrite($h, date('Y-m-d H:i:s ') . '[' . addslashes($_SERVER['REMOTE_ADDR']) . '] ' . $logtext . "\n");
		fclose($h);
	}

	public function makeOrder($order_id, $red = true)
	{
		if ($order_id)
		{
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			if ($order_info['order_status_id'] != $this->config->get('ya_kassa_os'))
				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('ya_kassa_os'), '', true);
			if ($red)
				$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
		}
	}

	public static function buildObtainTokenUrl($client_id, $redirect_uri, $scope)
	{
        $params = sprintf(
            "client_id=%s&response_type=%s&redirect_uri=%s&scope=%s",
            $client_id, "code", $redirect_uri, implode(" ", $scope)
		);

        return sprintf("%s/oauth/authorize?%s", self::SP_MONEY_URL, $params);
    }

	public function d($d)
	{
		echo '<pre>';
		print_r($d);
		echo '</pre>';
		die();
	}

	public function yaredirect()
	{
		if (isset($_POST['payment-type']) && !empty($_POST['payment-type']))
		{
			if ($_POST['payment-type'] == 'wallet')
			{
				$this->session->data['p2p_type'] = $_POST['payment-type'];
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				// $this->d($total);
				$limit = number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2);
				$scope = array(
					"payment.to-account(\"".$this->config->get('ya_p2p_number')."\",\"account\").limit(,".$limit.")",
					"money-source(\"wallet\",\"card\")"
				);
				
				$auth_url = $this->buildObtainTokenUrl($this->config->get('ya_p2p_idapp'), $this->url->link('payment/yamodule/insidewallet', '', 'SSL'), $scope);
				$this->response->redirect($auth_url);
			}
			elseif ($_POST['payment-type'] == 'card')
			{
				$this->insidecard();
			}
			else
				$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
		}
		else
			$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
	}

	public function getAccessToken($client_id, $code, $redirect_uri, $client_secret = null)
	{
        $full_url = self::SP_MONEY_URL . "/oauth/token";
        $result = $this->SendCurl($full_url, array(), array(
            "code" => $code,
            "client_id" => $client_id,
            "grant_type" => "authorization_code",
            "redirect_uri" => $redirect_uri,
            "client_secret" => $client_secret
        ));

        return json_decode($result->body);
    }

	public function successcard()
	{
		if ($this->session->data['cps_context_id'] == $_GET['cps_context_id'])
		{
			$this->load->model('checkout/order');
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('ya_p2p_os'), '', true);
			$this->session->data['cps_context_id'] = '';
			$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
		}
	}

	public function insidecard()
	{
		$instance = $this->sendRequest('/api/instance-id', array('client_id' => $this->config->get('ya_p2p_idapp')));
		if($instance->status == 'success')
		{
			$instance_id = $instance->instance_id;
			$message = 'payment to order #'.$this->session->data['order_id'];
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$total = number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2);
			$payment_options = array(
				'pattern_id' => 'p2p',
				'to' => $this->config->get('ya_p2p_number'),
				'amount_due' => $total,
				'comment' => trim($message),
				'message' => trim($message),
				'instance_id' => $instance_id,
				'label' => $this->session->data['order_id']
			);

			$response = $this->sendRequest('/api/request-external-payment', $payment_options);
			if($response->status == 'success')
			{
				$this->error = false;
				if($this->config->get('ya_p2p_log'))
					$this->log_save('card_redirect:  request success');
				$request_id = $response->request_id;
				$this->session->data['ya_encrypt_CRequestId'] = urlencode(base64_encode($request_id));
				
				do{
					$process_options = array(
						"request_id" => $request_id,
						'instance_id' => $instance_id,
						'ext_auth_success_uri' => $this->url->link('payment/yamodule/successcard', '', 'SSL'),
						'ext_auth_fail_uri' => $this->url->link('checkout/failure', '', 'SSL')
					);	
					
					$result = $this->sendRequest('/api/process-external-payment', $process_options);					
					if($result->status == "in_progress") {
						sleep(1);
					}
				}while ($result->status == "in_progress");

				if($result->status == 'success')
				{
					$this->load->model('checkout/order');
					$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('ya_p2p_os'), '', true);
					$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
				}
				elseif($result->status == 'ext_auth_required')
				{
					$this->session->data['cps_context_id'] = $result->acs_params->cps_context_id;
					$url = sprintf("%s?%s", $result->acs_uri, http_build_query($result->acs_params));
					if($this->config->get('ya_p2p_log'))
					{
						$this->log_save('card_redirect:  redirect to '.$url);
						$this->log_save('card_redirect:  params 3d '.serialize($result));
					}
					$this->response->redirect($url);
					exit;
				}
				elseif($result->status == 'refused')
				{
					$this->errors[] = $this->descriptionError($resp->error) ? $this->descriptionError($resp->error) : $result->error;
					if($this->config->get('ya_p2p_log'))
						$this->log_save('card_redirect:refused '.$this->descriptionError($resp->error) ? $this->module->descriptionError($resp->error) : $result->error);
					$this->error = true;
				}
			}
		}
	}

	public function insidewallet()
	{
		$code = $this->request->get['code'];
		if (empty($code))
			$this->response->redirect($this->url->link('payment/yamodule/yaredirect', '', 'SSL'));
		else
		{
			$response = $this->getAccessToken($this->config->get('ya_p2p_idapp'), $code, $this->url->link('payment/yamodule/yaredirect', '', 'SSL'), $this->config->get('ya_p2p_pw'));
			$error = '';
			$token = '';
			if (isset($response->access_token))
				$token = $response->access_token;
			else
				$error = $response->error;
			
			if (!empty($token))
			{
				$message = 'payment to order #'.$this->session->data['order_id'];
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				$total = number_format($this->currency->convert($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], 'RUB'), 2);
				$rarray = array(
					'pattern_id' => 'p2p',
					'to' => $this->config->get('ya_p2p_number'),
					'amount_due' => $total,
					'comment' => trim($message),
					'message' => trim($message),
					'label' => $this->session->data['order_id']
				);

				$request_payment = $this->sendRequest('/api/request-payment', $rarray, $token);
				switch($request_payment->status)
				{
					case 'success':
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: request success');
						$this->session->data['ya_encrypt_token'] = urlencode(base64_encode($token));
						$this->session->data['ya_encrypt_RequestId'] = urlencode(base64_encode($request_payment->request_id));
						do{
							$array_p = array("request_id" => $request_payment->request_id);
							$process_payment = $this->sendRequest("/api/process-payment", $array_p, $token);
							
							if($process_payment->status == "in_progress") {
								sleep(1);
							}
						}while ($process_payment->status == "in_progress");

						$this->updateStatus($process_payment);
						$this->error = false;
						break;
					case 'refused':
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: request refused');
						$this->errors[] = $this->descriptionError($request_payment->error);
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: refused '.$this->descriptionError($request_payment->error));
							$this->error = true;
							// $this->d($this->descriptionError($request_payment->error));
							$this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
						break;
					case 'hold_for_pickup':
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: hold_for_pickup');
						$this->errors[] = 'Получатель перевода не найден, будет отправлен перевод до востребования. Успешное выполнение.';
						if($this->config->get('ya_p2p_log'))
							$this->log_save('wallet_redirect: hold_for_pickup Получатель перевода не найден, будет отправлен перевод до востребования. Успешное выполнение.');
							$this->error = true;
						break;
						
				}

				$this->language->load('payment/yamodule');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
				$data['text_back'] = $this->language->get('text_back');
				if (!$this->error)
					$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
				else
				{
					// $this->errors[] = $this->descriptionError('invalid_request');
					// $this->errors[] = $this->descriptionError('invalid_scope');
					// $data['error'] = $this->errors;
					// if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/wallet_error.tpl')) {
						// $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/wallet_error.tpl', $data));
					// } else {
						// $this->response->setOutput($this->load->view('default/template/payment/wallet_error.tpl', $data));
					// }
					$data['shopFailURL'] = $this->url->link('checkout/failure', '', 'SSL');
					$this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
				}
			}
			else
				$this->response->redirect($this->url->link('payment/yamodule/yaredirect', '', 'SSL'));
		}
	}

	public function callback()
	{
		$data = $_POST;
		if($this->config->get('ya_kassa_log'))
			$this->log_save('callback:  request '.serialize($_REQUEST));
		$this->load->model('yamodel/yamoney');
		$this->model_yamodel_yamoney->password = $this->config->get('ya_kassa_pw');
		$this->model_yamodel_yamoney->password2 = $this->config->get('ya_p2p_pw');
		$this->model_yamodel_yamoney->shopid = $this->config->get('ya_kassa_sid');
		$order_id = isset($data['orderNumber']) ? (int)$data['orderNumber'] : 0;
		if ($this->config->get('ya_kassa_active') && isset($data['action']) && !empty($data['action']))
		{
			if ($data['action'] == 'checkOrder')
			{
				if($this->config->get('ya_kassa_log'))
					$this->log_save('callback:  checkOrder');
				$this->model_yamodel_yamoney->checkOrder($data, true, false);
			}

			if ($data['action'] == 'paymentAviso'){
				if ($order_id > 0)
					$this->makeOrder($order_id, false);
				if($this->config->get('ya_kassa_log'))
					$this->log_save('callback:  Aviso');
				$this->model_yamodel_yamoney->checkOrder($data, true, true);
			}
		}
		else
		{	
			exit('error settings yamodule or empty request');
		}
	}

	public function descriptionError($error)
	{
		$error_array = array(
			'invalid_request' => 'Your request is missing required parameters or settings are incorrect or invalid values',
			'invalid_scope' => 'The scope parameter is missing or has an invalid value or a logical contradiction',
			'unauthorized_client' => 'Invalid parameter client_id, or the application does not have the right to request authorization (such as its client_id blocked Yandex.Money)',
			'access_denied' => 'Has declined a request authorization application',
			'invalid_grant' => 'The issue access_token denied. Issued a temporary token is not Google search or expired, or on the temporary token is issued access_token (second request authorization token with the same time token)',
			'illegal_params' => 'Required payment options are not available or have invalid values.',
			'illegal_param_label' => 'Invalid parameter value label',
			'phone_unknown' => 'A phone number is not associated with a user account or payee',
			'payment_refused' => 'Магазин отказал в приеме платежа (например, пользователь пытался заплатить за товар, которого нет в магазине)',
			'limit_exceeded' => 'Exceeded one of the limits on operations: on the amount of the transaction for authorization token issued; transaction amount for the period of time for the token issued by the authorization; Yandeks.Deneg restrictions for different types of operations.',
			'authorization_reject' => 'In payment authorization is denied. Possible reasons are: transaction with the current parameters is not available to the user; person does not accept the Agreement on the use of the service "shops".',
			'contract_not_found' => 'None exhibited a contract with a given request_id',
			'not_enough_funds' => 'Insufficient funds in the account of the payer. Need to recharge and carry out a new delivery',
			'not-enough-funds' => 'Insufficient funds in the account of the payer. Need to recharge and carry out a new delivery',
			'money_source_not_available' => 'The requested method of payment (money_source) is not available for this payment',
			'illegal_param_csc' => 'tsutstvuet or an invalid parameter value cs',
			'payment_refused' => 'Shop for whatever reason, refused to accept payment.'
		);
		if(array_key_exists($error,$error_array))
			$return = $error_array[$error];
		else
			$return = $error;
		return $return;
	}
	
	public function SendCurl($url, $headers, $params){
		$curl = curl_init($url);
		if(isset($headers['Authorization'])){
			$token = $headers['Authorization'];
			$headers = array();
			$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
			$headers[] = 'Authorization: '.$token;
		}
		$params = http_build_query($params);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERAGENT, 'yamolib-php');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 80);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $rbody = curl_exec($curl);
        $errno = curl_errno($curl);
        $error = curl_error($curl);
        $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// self::de(curl_getinfo($curl, CURLINFO_HEADER_OUT), false);
        curl_close($curl);
		$result = new stdClass();
		$result->status_code = $rcode;
		$result->body = $rbody;
		return $result;
	}
	
	public function updateStatus(&$resp)
	{
		if ($resp->status == 'success')
		{
			$this->makeOrder($this->session->data['order_id'], false);
			$this->session->data['ya_encrypt_token'] = '';
			$this->session->data['ya_encrypt_RequestId'] = '';
			if($this->config->get('ya_p2p_log'))
				$this->log_save('wallet_redirect: #'.$this->session->data['order_id'].' Order success');
			$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
		}
		else
		{ 
			$this->errors[] = $this->descriptionError($resp->error);
			if($this->config->get('ya_p2p_log'))
				$this->log_save('wallet_redirect: Error '.$this->descriptionError($resp->error));
			$this->error = true;
		}
    }

	public function sendRequest($url, $options = array(), $access_token = null)
	{
        $full_url= self::MONEY_URL . $url;
        if($access_token != null) {
            $headers = array(
                "Authorization" => sprintf("Bearer %s", $access_token),
            );
        } 
        else {
            $headers = array();
        }
        $result = $this->SendCurl($full_url, $headers, $options);
        return json_decode($result->body);
    }
}