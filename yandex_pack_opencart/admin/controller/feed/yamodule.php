<?php
class ControllerFeedYamodule extends Controller {

	private $error = array();
	public $fields_p2p = array(
		'ya_p2p_active',
		'ya_p2p_number',
		'ya_p2p_test',
		'ya_p2p_idapp',
		'ya_p2p_pw',
		'ya_p2p_log',
		'ya_p2p_os'
	);
	
	public $fields_kassa = array(
		'ya_kassa_active',
		'ya_kassa_sid',
		'ya_kassa_wallet',
		'ya_kassa_log',
		'ya_kassa_test',
		'ya_kassa_scid',
		'ya_kassa_pw',
		'ya_kassa_card',
		'ya_kassa_terminal',
		'ya_kassa_mobile',
		'ya_kassa_sber',
		'ya_kassa_alfa',
		'ya_kassa_wm',
		'ya_kassa_os'
	);

	public $fields_metrika = array(
		'ya_metrika_active',
		'ya_metrika_number',
		'ya_metrika_idapp',
		'ya_metrika_uname',
		'ya_metrika_upw',
		'ya_metrika_pw',
		'ya_metrika_webvizor',
		'ya_metrika_otkaz',
		'ya_metrika_clickmap',
		'ya_metrika_out',
		'ya_metrika_hash',
		'ya_metrika_cart',
		'ya_metrika_order',
	);
	
	public $fields_market = array(
		'ya_market_active',
		'ya_market_catall',
		'ya_market_prostoy',
		'ya_market_set_available',
		'ya_market_shopname',
		'ya_market_localcoast',
		'ya_market_available',
		'ya_market_homecarrier',
		'ya_market_combination',
		'ya_market_features',
		'ya_market_dimensions',
		'ya_market_allcurrencies',
		'ya_market_store',
		'ya_market_delivery',
		'ya_market_pickup',
		'ya_market_color_options',
		'ya_market_size_options',
		'ya_market_categories'
	);

	public $fields_pokupki = array(
		'ya_pokupki_stoken',
		'ya_pokupki_yapi',
		'ya_pokupki_number',
		'ya_pokupki_login',
		'ya_pokupki_upw',
		'ya_pokupki_idapp',
		'ya_pokupki_pw',
		'ya_pokupki_idpickup',
		'ya_pokupki_yandex',
		'ya_pokupki_sprepaid',
		'ya_pokupki_cash',
		'ya_pokupki_bank',
		'ya_pokupki_carrier'
	);

	public function initErrors()
	{
		$data = array();
		if ($this->config->get('ya_pokupki_stoken') == '')
			$data['pokupki_status'][] = $this->errors_alert('Токен не заполнен!');
		if ($this->config->get('ya_pokupki_yapi') == '')
			$data['pokupki_status'][] = $this->errors_alert('URL api не заполнен');
		if ($this->config->get('ya_pokupki_number') == '')
			$data['pokupki_status'][] = $this->errors_alert('Номер кампании не заполнен');
		if ($this->config->get('ya_pokupki_login') == '')
			$data['pokupki_status'][] = $this->errors_alert('Логин пользователя yandex не заполнен');
		if ($this->config->get('ya_pokupki_upw') == '')
			$data['pokupki_status'][] = $this->errors_alert('Пароль yandex не заполнен');
		if ($this->config->get('ya_pokupki_idapp') == '')
			$data['pokupki_status'][] = $this->errors_alert('ID приложения не заполнен');
		if ($this->config->get('ya_pokupki_pw') == '')
			$data['pokupki_status'][] = $this->errors_alert('Пароль приложения не заполнен');
		if ($this->config->get('ya_pokupki_gtoken') == '')
			$data['pokupki_status'][] = $this->errors_alert('Токен yandex не получен!');

		if ($this->config->get('ya_market_shopname') == '')
			$data['market_status'][] = $this->errors_alert('Не введено название магазина');
		if ($this->config->get('ya_market_localcoast') == '')
			$data['market_status'][] = $this->errors_alert('Введите стоимость доставки в домашнем регионе');

		if ($this->config->get('ya_metrika_number') == '')
			$data['metrika_status'][] = $this->errors_alert('Не заполнен номер счётчика');
		if ($this->config->get('ya_metrika_idapp') == '')
			$data['metrika_status'][] = $this->errors_alert('ID Приложения не заполнено');
		if ($this->config->get('ya_metrika_pw') == '')
			$data['metrika_status'][] = $this->errors_alert('Пароль приложения не заполнено');
		if ($this->config->get('ya_metrika_uname') == '')
			$data['metrika_status'][] = $this->errors_alert('Имя пользователя yandex не заполнено');
		if ($this->config->get('ya_metrika_upw') == '')
			$data['metrika_status'][] = $this->errors_alert('Пароль пользователя yandex не заполнен');
		if ($this->config->get('ya_metrika_o2auth') == '')
			$data['metrika_status'][] = $this->errors_alert('Получите токен OAuth');
		
		if ($this->config->get('ya_p2p_number') == '')
			$data['p2p_status'][] = $this->errors_alert('Введите номер кошелька');
		if ($this->config->get('ya_p2p_idapp') == '')
			$data['p2p_status'][] = $this->errors_alert('Введите ID Приложения');
		if ($this->config->get('ya_p2p_pw') == '')
			$data['p2p_status'][] = $this->errors_alert('Введите секретный ключ');
		
		if ($this->config->get('ya_kassa_sid') == '')
			$data['kassa_status'][] = $this->errors_alert('ShopId не заполнен');
		if ($this->config->get('ya_kassa_scid') == '')
			$data['kassa_status'][] = $this->errors_alert('SCID Не заполнен');

		if (empty($data['market_status']))
			$data['market_status'][] = $this->success_alert('Все необходимые настроки заполнены!');
		if (empty($data['kassa_status']))
			$data['kassa_status'][] = $this->success_alert('Все необходимые настроки заполнены!');
		if (empty($data['p2p_status']))
			$data['p2p_status'][] = $this->success_alert('Все необходимые настроки заполнены!');
		if (empty($data['metrika_status']))
			$data['metrika_status'][] = $this->success_alert('Все необходимые настроки заполнены!');
		if (empty($data['pokupki_status']))
			$data['pokupki_status'][] = $this->success_alert('Все необходимые настроки заполнены!');

		return $data;
	}

	public function saveData($source)
	{
		foreach ($source as $s)
			if (isset($this->request->post[$s]))
				$this->model_setting_setting->editSetting($s, $this->request->post);
			else
				$this->model_setting_setting->editSetting($s, array($s => 0));
	}

	public function processSave()
	{
		switch($this->request->post['type_data'])
		{
			case 'kassa':
				$this->session->data['kassa_status'] = array();
				$this->saveData($this->fields_kassa);
				$this->session->data['kassa_status'][] = $this->success_alert('Настройки успешно сохранены!');
				if($this->request->post['ya_kassa_active'] == 1)
					$this->model_setting_setting->editSetting('ya_p2p_active', array('ya_p2p_active' => 0));
				break;
			case 'p2p':
				$this->session->data['p2p_status'] = array();
				$this->saveData($this->fields_p2p);
				$this->session->data['p2p_status'][] = $this->success_alert('Настройки успешно сохранены!');
				if($this->request->post['ya_p2p_active'] == 1)
					$this->model_setting_setting->editSetting('ya_kassa_active', array('ya_kassa_active' => 0));
				break;
			case 'metrika':
				$this->session->data['metrika_status'] = array();
				$this->saveData($this->fields_metrika);
				$this->session->data['metrika_status'][] = $this->success_alert('Данные метрики сохранены!');
				$this->load->model('yamodule/metrika');
				$this->model_yamodule_metrika->initData($this->config->get('ya_metrika_o2auth'), ($this->request->post['ya_metrika_number'] != $this->config->get('ya_metrika_number') ? $this->request->post['ya_metrika_number'] : $this->config->get('ya_metrika_number')));
				$this->model_yamodule_metrika->processCounter();
				break;
			case 'market':
				$this->session->data['market_status'] = array();
				$this->session->data['market_status'][] = $this->success_alert('Настройки успешно сохранены!');
				$this->saveData($this->fields_market);
				break;
			case 'pokupki':
				$this->session->data['pokupki_status'] = array();
				$this->saveData($this->fields_pokupki);
				$this->session->data['pokupki_status'][] = $this->success_alert('Настройки успешно сохранены!');
				break;
			
		}
	}

	public function initForm($array)
	{
		foreach ($array as $a)
			$data[$a] = $this->config->get($a);

		$data['ya_p2p_linkapp'] = HTTPS_CATALOG.'index.php?route=payment/yamodule/inside';
		$data['ya_kassa_check'] = HTTPS_CATALOG.'index.php?route=payment/yamodule/callback';
		$data['ya_kassa_aviso'] = HTTPS_CATALOG.'index.php?route=payment/yamodule/callback';
		$data['ya_kassa_fail'] = HTTPS_CATALOG.'index.php?route=checkout/failure';
		$data['ya_kassa_success'] = HTTPS_CATALOG.'index.php?route=checkout/success';
		$data['ya_metrika_callback'] = $this->url->link('feed/yamodule/preparem', 'token='.$this->session->data['token'], 'SSL');
		$data['ya_pokupki_callback'] = $this->url->link('feed/yamodule/preparep', 'token='.$this->session->data['token'], 'SSL');
		$data['ya_pokupki_sapi'] = HTTPS_CATALOG.'yandexbuy';
		$data['ya_market_lnk_yml'] = HTTPS_CATALOG.'index.php?route=feed/yamodule';
		$data['ya_pokupki_gtoken'] = $this->config->get('ya_pokupki_gtoken');
		$data['ya_metrika_o2auth'] = $this->config->get('ya_metrika_o2auth');
		$data['token_url'] = 'https://oauth.yandex.ru/token?';
		$data['mod_status'] = $this->config->get('yamodule_status');
		
		return $data;
	}
	
	public function preparem()
	{
		return $this->gocurl('m', 'grant_type=password&username='.$this->config->get('ya_metrika_uname').'&password='.$this->config->get('ya_metrika_upw').'&client_id='.$this->config->get('ya_metrika_idapp').'&client_secret='.$this->config->get('ya_metrika_pw'));
	}
	
	public function preparep()
	{
		return $this->gocurl('p', 'grant_type=password&username='.$this->config->get('ya_pokupki_login').'&password='.$this->config->get('ya_pokupki_upw').'&client_id='.$this->config->get('ya_pokupki_idapp').'&client_secret='.$this->config->get('ya_pokupki_pw'));
	}

	public function gocurl($type, $post)
	{
		$url = 'https://oauth.yandex.ru/token';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 9);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);  
		$data = json_decode($result);

		if ($status == 200) {
			if (!empty($data->access_token))
			{
				$this->load->model('setting/setting');
				if($type == 'm')
					$this->model_setting_setting->editSetting('ya_metrika_o2auth', array('ya_metrika_o2auth' => $data->access_token));
				elseif($type == 'p')
					$this->model_setting_setting->editSetting('ya_pokupki_gtoken', array('ya_pokupki_gtoken' => $data->access_token));
				$this->response->redirect($this->url->link('feed/yamodule', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$this->response->redirect($this->url->link('feed/yamodule', 'err='.$data->error_description.'&token=' . $this->session->data['token'], 'SSL'));
	}

	public function treeItem($id, $name)
	{
		$html = '<li class="tree-item">
						<span class="tree-item-name">
							<input type="checkbox" name="ya_market_categories[]" value="'.$id.'">
							<i class="tree-dot"></i>
							<label class="">'.$name.'</label>
						</span>
					</li>';
		return $html;
	}

	public function treeFolder($id, $name)
	{
		$html = '<li class="tree-folder">
					<span class="tree-folder-name">
						<input type="checkbox" name="ya_market_categories[]" value="'.$id.'">
						<i class="icon-folder-open"></i>
						<label class="tree-toggler">'.$name.'</label>
					</span>
					<ul class="tree" style="display: block;">'.$this->treeCat($id).'</ul>
				</li>';
		return $html;
	}

	public function treeCat($id_cat = 0)
	{
		$html = '';
		$categories = $this->getCategories($id_cat);
		foreach ($categories as $category)
		{
			$children = $this->getCategories($category['category_id']);
			if (count($children))
			{
				$html .= $this->treeFolder($category['category_id'], $category['name']);
			}
			else
			{
				$html .= $this->treeItem($category['category_id'], $category['name']);
			}
		}

		return $html;
	}

	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}

	public function carrierList()
	{
		$types = array('POST', 'PICKUP', 'DELIVERY');
		$this->load->model('extension/extension');
		$extensions = $this->model_extension_extension->getInstalled('shipping');
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/shipping/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('shipping', $value);

				unset($extensions[$key]);
			}
		}
		$data['extensions'] = array();
		$files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
		if ($files)
		{
			foreach ($files as $file)
			{
				$extension = basename($file, '.php');
				if (in_array($extension, $extensions))
				{
					$this->load->language('shipping/' . $extension);
					$data['extensions'][] = array(
						'name'       => $this->language->get('heading_title'),
						'sort_order' => $this->config->get($extension . '_sort_order'),
						'installed'  => in_array($extension, $extensions),
						'ext' => $extension
					);
				}
			}
		}
		$html = '';
		$save_data = $this->config->get('ya_pokupki_carrier');
		foreach ($data['extensions'] as $row)
		{
			$html .= '<div class="form-group">
							<label class="col-sm-4 control-label" for="ya_pokupki_carrier">'.$row['name'].'</label>
							<div class="col-sm-8">
								<select name="ya_pokupki_carrier['.$row['ext'].']" id="ya_pokupki carrier" class="form-control">';
									foreach ($types as $t)
										$html .= '<option value="'.$t.'" '.((isset($save_data[$row['ext']]) && $save_data[$row['ext']] == $t) ? 'selected="selected"' : '').'>'.$t.'</option>';
							$html .= '</select>
							</div>
						</div>';
		}
		
		return $html;
	}
	public function index()
	{
		$this->load->language('feed/yamodule');
		$this->load->model('setting/setting');
		$this->load->model('catalog/option');
		$this->load->model('localisation/order_status');
		$data['data_carrier'] = $this->carrierList();
		$data['kassa_status'] = '';
		$data['p2p_status'] = '';
		$data['metrika_status'] = '';
		$data['market_status'] = '';
		$data['pokupki_status'] = '';
		$array_init = array_merge($this->fields_p2p, $this->fields_kassa, $this->fields_metrika, $this->fields_market, $this->fields_pokupki);
		if (($this->request->server['REQUEST_METHOD'] == 'POST'))
		{
			$this->processSave();
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('feed/yamodule', 'token=' . $this->session->data['token'], 'SSL'));
		}

		if(isset($this->request->get['err']))
			$data['err_token'] = $this->request->get['err'];
		else
			$data['err_token'] = '';

		// pokupki
		$data['pokupki_gtoken'] = $this->language->get('pokupki_gtoken');
		$data['pokupki_stoken'] = $this->language->get('pokupki_stoken');
		$data['pokupki_yapi'] = $this->language->get('pokupki_yapi');
		$data['pokupki_number'] = $this->language->get('pokupki_number');
		$data['pokupki_login'] = $this->language->get('pokupki_login');
		$data['pokupki_pw'] = $this->language->get('pokupki_pw');
		$data['pokupki_idapp'] = $this->language->get('pokupki_idapp');
		$data['pokupki_token'] = $this->language->get('pokupki_token');
		$data['pokupki_idpickup'] = $this->language->get('pokupki_idpickup');
		$data['pokupki_method'] = $this->language->get('pokupki_method');
		$data['pokupki_sapi'] = $this->language->get('pokupki_sapi');
		$data['pokupki_set_1'] = $this->language->get('pokupki_set_1');
		$data['pokupki_set_2'] = $this->language->get('pokupki_set_2');
		$data['pokupki_set_3'] = $this->language->get('pokupki_set_3');
		$data['pokupki_set_4'] = $this->language->get('pokupki_set_4');
		$data['pokupki_sv'] = $this->language->get('pokupki_sv');
		$data['pokupki_upw'] = $this->language->get('pokupki_upw');
		$data['pokupki_callback'] = $this->language->get('pokupki_callback');
		//market
		$data['market_color_option'] = $this->language->get('market_color_option');
		$data['market_size_option'] = $this->language->get('market_size_option');
		$data['market_size_unit'] = $this->language->get('market_size_unit');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_no'] = $this->language->get('text_no');
		$data['market_set'] = $this->language->get('market_set');
		$data['market_set_1'] = $this->language->get('market_set_1');
		$data['market_set_2'] = $this->language->get('market_set_2');
		$data['market_set_3'] = $this->language->get('market_set_3');
		$data['market_set_4'] = $this->language->get('market_set_4');
		$data['market_set_5'] = $this->language->get('market_set_5');
		$data['market_set_6'] = $this->language->get('market_set_6');
		$data['market_set_7'] = $this->language->get('market_set_7');
		$data['market_set_8'] = $this->language->get('market_set_8');
		$data['market_set_9'] = $this->language->get('market_set_9');
		$data['market_lnk_yml'] = $this->language->get('market_lnk_yml');
		$data['market_cat'] = $this->language->get('market_cat');
		$data['market_out'] = $this->language->get('market_out');
		$data['market_out_sel'] = $this->language->get('market_out_sel');
		$data['market_out_all'] = $this->language->get('market_out_all');
		$data['market_dostup'] = $this->language->get('market_dostup');
		$data['market_dostup_1'] = $this->language->get('market_dostup_1');
		$data['market_dostup_2'] = $this->language->get('market_dostup_2');
		$data['market_dostup_3'] = $this->language->get('market_dostup_3');
		$data['market_dostup_4'] = $this->language->get('market_dostup_4');
		$data['market_s_name'] = $this->language->get('market_s_name');
		$data['market_d_cost'] = $this->language->get('market_d_cost');
		$data['market_sv_all'] = $this->language->get('market_sv_all');
		$data['market_rv_all'] = $this->language->get('market_rv_all');
		$data['market_ch_all'] = $this->language->get('market_ch_all');
		$data['market_unch_all'] = $this->language->get('market_unch_all');
		$data['market_prostoy'] = $this->language->get('market_prostoy');
		$data['market_sv'] = $this->language->get('market_sv');
		$data['market_gen'] = $this->language->get('market_gen');
		// p2p
		$data['p2p_os'] = $this->language->get('p2p_os');
		$data['p2p_sv'] = $this->language->get('p2p_sv');
		$data['p2p_number'] = $this->language->get('p2p_number');
		$data['p2p_idapp'] = $this->language->get('p2p_idapp');
		$data['p2p_pw'] = $this->language->get('p2p_pw');
		$data['p2p_linkapp'] = $this->language->get('p2p_linkapp');
		// kassa
		$data['kassa_os'] = $this->language->get('kassa_os');
		$data['kassa_test'] = $this->language->get('kassa_test');
		$data['kassa_sid'] = $this->language->get('kassa_sid');
		$data['kassa_scid'] = $this->language->get('kassa_scid');
		$data['kassa_pw'] = $this->language->get('kassa_pw');
		$data['kassa_wm'] = $this->language->get('kassa_wm');
		$data['kassa_mobile'] = $this->language->get('kassa_mobile');
		$data['kassa_sber'] = $this->language->get('kassa_sber');
		$data['kassa_alfa'] = $this->language->get('kassa_alfa');
		$data['kassa_wallet'] = $this->language->get('kassa_wallet');
		$data['kassa_method'] = $this->language->get('kassa_method');
		$data['kassa_card'] = $this->language->get('kassa_card');
		$data['kassa_terminal'] = $this->language->get('kassa_terminal');
		$data['kassa_check'] = $this->language->get('kassa_check');
		$data['kassa_fail'] = $this->language->get('kassa_fail');
		$data['kassa_success'] = $this->language->get('kassa_success');
		$data['kassa_aviso'] = $this->language->get('kassa_aviso');
		$data['kassa_sv'] = $this->language->get('kassa_sv');
		//metrika
		$data['metrika_gtoken'] = $this->language->get('metrika_gtoken');
		$data['metrika_number'] = $this->language->get('metrika_number');
		$data['metrika_idapp'] = $this->language->get('metrika_idapp');
		$data['metrika_o2auth'] = $this->language->get('metrika_o2auth');
		$data['metrika_pw'] = $this->language->get('metrika_pw');
		$data['metrika_uname'] = $this->language->get('metrika_uname');
		$data['metrika_upw'] = $this->language->get('metrika_upw');
		$data['metrika_set'] = $this->language->get('metrika_set');
		$data['metrika_celi'] = $this->language->get('metrika_celi');
		$data['metrika_callback'] = $this->language->get('metrika_callback');
		$data['metrika_sv'] = $this->language->get('metrika_sv');
		$data['metrika_set_1'] = $this->language->get('metrika_set_1');
		$data['metrika_set_2'] = $this->language->get('metrika_set_2');
		$data['metrika_set_3'] = $this->language->get('metrika_set_3');
		$data['metrika_set_4'] = $this->language->get('metrika_set_4');
		$data['metrika_set_5'] = $this->language->get('metrika_set_5');
		$data['celi_cart'] = $this->language->get('celi_cart');
		$data['celi_order'] = $this->language->get('celi_order');
		//
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		//
		$results = $this->model_catalog_option->getOptions(array('sort' => 'name'));
		$data['options'] = $results;
		$data['tab_general'] = $this->language->get('tab_general');
		$data['ya_market_size_options'] = array();
		$data['ya_market_color_options'] = array();
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		$data['heading_title'] = $this->language->get('heading_title_ya');
		$data['market'] = $this->language->get('market');
		$data['kassa'] = $this->language->get('kassa');
		$data['metrika'] = $this->language->get('metrika');
		$data['pokupki'] = $this->language->get('pokupki');
		$data['p2p'] = $this->language->get('p2p');
		$data['active'] = $this->language->get('active');
		$data['active_on'] = $this->language->get('active_on');
		$data['active_off'] = $this->language->get('active_off');
		$data['log'] = $this->language->get('log');
		$data['mod_off'] = $this->language->get('mod_off');
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$data['breadcrumbs'][] = array(
			'href'      => $this->url->link('feed/yamodule', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title_ya'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('feed/yamodule', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/feed', 'token=' . $this->session->data['token'], 'SSL');
		$this->load->model('localisation/stock_status');
		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();
		$this->load->model('catalog/category');
		$data['categories'] = $this->model_catalog_category->getCategories(0);
		$this->document->setTitle($this->language->get('heading_title_ya'));
		if (isset($this->request->post['ya_market_categories'])) {
			$data['ya_market_categories'] = $this->request->post['ya_market_categories'];
		} elseif ($this->config->get('yandex_market_categories') != '') {
			$data['ya_market_categories'] = explode(',', $this->config->get('ya_market_categories'));
		} else {
			$data['ya_market_categories'] = array();
		}

		$this->load->model('localisation/currency');
		$currencies = $this->model_localisation_currency->getCurrencies();
		$allowed_currencies = array_flip(array('RUR', 'RUB', 'BYR', 'KZT', 'UAH'));
		$data['currencies'] = array_intersect_key($currencies, $allowed_currencies);
		$data['heading_title'] = $this->language->get('heading_title_ya');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['text_installed'] = $this->language->get('text_installed');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$data = array_merge($data, $this->initForm($array_init));
		$data = array_merge($data, $this->initErrors());
		$data['market_cat_tree'] = $this->treeCat(0);
		if (!isset($data['ya_market_size_options']))
			$data['ya_market_size_options'] = array();
		if (!isset($data['ya_market_color_options']))
			$data['ya_market_color_options'] = array();
			// Loader::dieObject($this->session->data);
		if (isset($this->session->data['metrika_status']) && !empty($this->session->data['metrika_status']))
			$data['metrika_status'] = array_merge($data['metrika_status'], $this->session->data['metrika_status']);
		if (isset($this->session->data['market_status']) && !empty($this->session->data['market_status']))
			$data['market_status'] = array_merge($data['market_status'], $this->session->data['market_status']);
		if (isset($this->session->data['kassa_status']) && !empty($this->session->data['kassa_status']))
			$data['kassa_status'] = array_merge($data['kassa_status'], $this->session->data['kassa_status']);
		if (isset($this->session->data['p2p_status']) && !empty($this->session->data['p2p_status']))
			$data['p2p_status'] = array_merge($data['p2p_status'], $this->session->data['p2p_status']);
		if (isset($this->session->data['pokupki_status']) && !empty($this->session->data['pokupki_status']))
			$data['pokupki_status'] = array_merge($data['pokupki_status'], $this->session->data['pokupki_status']);
		
		$this->response->setOutput($this->load->view('feed/yamodule.tpl', $data));
	}

	public function errors_alert($text)
	{
		$html = '<div class="alert alert-danger">
						<i class="fa fa-exclamation-circle"></i> '.$text.'
							<button type="button" class="close" data-dismiss="alert">×</button>
					</div>';
		return $html;
	}

	public function success_alert($text)
	{
		$html = ' <div class="alert alert-success">
					<i class="fa fa-check-circle"></i> '.$text.'
						<button type="button" class="close" data-dismiss="alert">×</button>
					</div>';
		return $html;
	}

	public function install()
	{
		$fields = array(
			'ya_p2p_active' => 0,
			'ya_p2p_test' => 1,
			'ya_p2p_log' => 1,
			'ya_kassa_active' => 0,
			'ya_kassa_log' => 1,
			'ya_kassa_test' => 1,
			'ya_metrika_active' => 0,
			'ya_metrika_webvizor' => 1,
			'ya_metrika_otkaz' => 1,
			'ya_metrika_clickmap' => 1,
			'ya_metrika_out' => 1,
			'ya_metrika_hash' => 1,
			'ya_metrika_cart' => 1,
			'ya_metrika_order' => 1,
			'ya_market_active' => 0,
			'ya_market_prostoy' => 0,
			'ya_market_set_available' => 2,
			'ya_market_available' => 1,
			'ya_market_homecarrier' => 1,
			'ya_market_combination' => 1,
			'ya_market_features' => 1,
			'ya_market_dimensions' => 1,
			'ya_market_allcurrencies' => 1,
			'ya_market_store' => 1,
			'ya_market_delivery' => 1,
			'ya_market_pickup' => 1,
			'ya_pokupki_yandex' => 1,
			'ya_pokupki_sprepaid' => 1,
			'ya_pokupki_cash' => 1,
			'ya_pokupki_bank' => 1
		);

		$q = $this->db->query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."pokupki_orders` (
		  `id_order` int(10) NOT NULL,
		  `id_market_order` varchar(100) NOT NULL,
		  `currency` varchar(100) NOT NULL,
		  `ptype` varchar(100) NOT NULL,
		  `home` varchar(100) NOT NULL,
		  `pmethod` varchar(100) NOT NULL,
		  `outlet` varchar(100) NOT NULL,
		  PRIMARY KEY (`id_order`,`id_market_order`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

		$this->load->model('setting/setting');
		foreach ($fields as $k => $field)
			$this->model_setting_setting->editSetting($k, array($k => $field));
		$user = array();
		$user['firstname'] = 'ya-name';
		$user['lastname'] = 'ya-lastname';
		$user['address'][0]['firstname'] = 'ya-name';
		$user['address'][0]['lastname'] = 'ya-lastname';
		$user['email'] = 'test@2.ru';
		$user['telephone'] = 999999;
		$user['address'][0]['telephone'] = 999999;
		$user['address'][0]['address_1'] = 'Address';
		$user['address'][0]['postcode'] = 000000;
		$user['address'][0]['city'] = 'ya-Город';
		$user['address'][0]['country_id'] = '';
		$user['address'][0]['custom_field'] = '';
		$user['newsletter'] = '';
		$user['customer_group_id'] = 1;
		$user['custom_field'] = '';
		$user['safe'] = '';
		$user['fax'] = '';
		$user['address'][0]['fax'] = '';
		$user['address'][0]['company'] = '';
		$user['address'][0]['address_2'] = '';
		$user['address'][0]['zone_id'] = '';
		$user['status'] = true;
		$user['password'] = rand(100000, 500000);
		$customer_id = $this->addCustomer($user);
		$this->model_setting_setting->editSetting('yandexbuy_customer', array('yandexbuy_customer' => $customer_id));
	}

	public function addCustomer($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(serialize($data['custom_field'])) . "', newsletter = '" . (int)$data['newsletter'] . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '" . (int)$data['status'] . "', safe = '" . (int)$data['safe'] . "', date_added = NOW()");
		$customer_id = $this->db->getLastId();
		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($address['firstname']) . "', lastname = '" . $this->db->escape($address['lastname']) . "', company = '" . $this->db->escape($address['company']) . "', address_1 = '" . $this->db->escape($address['address_1']) . "', address_2 = '" . $this->db->escape($address['address_2']) . "', city = '" . $this->db->escape($address['city']) . "', postcode = '" . $this->db->escape($address['postcode']) . "', country_id = '" . (int)$address['country_id'] . "', zone_id = '" . (int)$address['zone_id'] . "', custom_field = '" . $this->db->escape(serialize($address['custom_field'])) . "'");
				$address_id = $this->db->getLastId();
				$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
			}
		}
		
		return $customer_id;
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		return $query->row;
	}

	public function uninstall()
	{
		// $this->db->query("DROP TABLE `".DB_PREFIX."pokupki_orders`");
		$cu = $this->getCustomer($this->config->get('yandexbuy_customer'));
		if ($cu['customer_id'] && $cu['address_id'])
		{
			$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE customer_id = ".$cu['customer_id']);
			$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = ".$cu['address_id']);
		}
	}
}
?>