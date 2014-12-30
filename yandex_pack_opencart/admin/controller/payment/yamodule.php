<?php
class ControllerPaymentYamodule extends Controller {

	public function index() {
		$this->load->language('payment/yamodule');
		$this->document->setTitle($this->language->get('heading_title'));
	}

	public function install() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('yamodule_status', array('yamodule_status' => 1));
	}
	
	public function uninstall() {
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('yamodule_status', array('yamodule_status' => 0));
	}
}