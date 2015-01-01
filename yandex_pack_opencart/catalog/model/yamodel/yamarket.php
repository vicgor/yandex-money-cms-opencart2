<?php

Class ModelYamodelYamarket extends Model
{
	
	var $from_charset = 'windows-1251';
	var $shop = array('name' => '', 'company' => '', 'url' => '', 'platform' => 'ya_opencart');
	var $currencies = array();
	var $categories = array();
	var $offers = array();

	public function getCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category c
								LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id)
								LEFT JOIN " . DB_PREFIX . "category_to_store c2s ON (c.category_id = c2s.category_id)
								AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'
								AND c2s.store_id = '" . (int)$this->config->get('config_store_id') . "' 
								AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}
	
	public function getCurrencyByISO($id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency
									WHERE code = '" . $id . "'");

		return $query->row;
	}
	
	public function getProducts($allowed_categories, $vendor_required = true) {
		$query = $this->db->query("SELECT p.*, pd.name, pd.description, m.name AS manufacturer, p2c.category_id, IFNULL(ps.price, p.price) AS price, ps.price AS special, wcd.unit AS weight_unit,
									GROUP_CONCAT(DISTINCT CAST(pr.related_id AS CHAR) SEPARATOR ',') AS rel
									FROM " . DB_PREFIX . "product p JOIN " . DB_PREFIX . "product_to_category AS p2c ON (p.product_id = p2c.product_id)
									" . ($vendor_required ? '' : 'LEFT ') . "JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id)
									LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)
									LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)
									LEFT JOIN " . DB_PREFIX . "product_special ps ON (p.product_id = ps.product_id) AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ps.date_start < NOW() AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())
									LEFT JOIN " . DB_PREFIX . "weight_class_description wcd ON (p.weight_class_id = wcd.weight_class_id) AND wcd.language_id='" . (int)$this->config->get('config_language_id')."'
									LEFT JOIN " . DB_PREFIX . "product_related pr ON (p.product_id = pr.product_id AND p.date_available <= NOW() AND p.status = '1')
									WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'
									".($allowed_categories ? " AND p2c.category_id IN (" . $this->db->escape($allowed_categories) . ")" : "")."
									AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'
									AND p.status = '1'
									GROUP BY p.product_id");
		return $query->rows;
	}
	
	public function getProductOptions($option_ids, $product_id) {
		$lang = (int)$this->config->get('config_language_id');
		
		$query = $this->db->query("SELECT pov.*, od.name AS option_name, ovd.name
			FROM " . DB_PREFIX . "product_option_value pov 
			LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id)
			LEFT JOIN " . DB_PREFIX . "option_description od ON (od.option_id = pov.option_id) AND (od.language_id = '$lang')
			WHERE pov.option_id IN (". implode(',', array_map('intval', $option_ids)) .") AND pov.product_id = '". (int)$product_id."'
				AND ovd.language_id = '$lang'");
		return $query->rows;
	}
	
	public function getAttributes($attr_ids) {
		if (!$attr_ids) return array();
		$query = $this->db->query("SELECT a.attribute_id, ad.name
			FROM " . DB_PREFIX . "attribute a
			LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id)
			WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND a.attribute_id IN (" . $this->db->escape($attr_ids) . ")
				ORDER BY a.attribute_id, ad.name");
		$ret = array();
		foreach($query->rows as $row) {
			$ret[$row['attribute_id']] = $row['name'];
		}
		return $ret;
	}
	
	public function getProductAttributes($product_id) {
		$query = $this->db->query("SELECT pa.attribute_id, pa.text, ad.name
			FROM " . DB_PREFIX . "product_attribute pa
			LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (pa.attribute_id = ad.attribute_id)
			WHERE pa.product_id = '" . (int)$product_id . "'
				AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "'
				AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'
				ORDER BY pa.attribute_id");
		return $query->rows;
	}

	function yml($from_charset = 'windows-1251')
	{
		$this->from_charset = trim(strtolower($from_charset));
	}


	function convert_array_to_tag($arr)
	{
		$s = '';
		foreach($arr as $tag => $val)
		{
			if($tag == 'weight' && (int)$val == 0)
				continue;

			if($tag == 'picture')
			{
				foreach ($val as $v){
					$s .= '<'.$tag.'>'.$v.'</'.$tag.'>';
					$s .= "\r\n";
				}
			}
			elseif($tag == 'param')
			{
				foreach ($val as $v){
					$s .= '<param name="'.$this->prepare_field($v['name']).'">'.$this->prepare_field($v['value']).'</param>';
					$s .= "\r\n";
				}
			}
			else
			{
				$s .= '<'.$tag.'>'.$val.'</'.$tag.'>';
				$s .= "\r\n";
			}
		}

		return $s;
	}

	function convert_array_to_attr($arr, $tagname, $tagvalue = '')
	{
		$s = '<'.$tagname.' ';
		foreach($arr as $attrname=>$attrval)
			$s .= $attrname . '="'.$attrval.'" ';

		$s .= ($tagvalue!='') ? '>'.$tagvalue.'</'.$tagname.'>' : '/>';
		$s .= "\r\n";
		return $s;
	}

	function prepare_field($s)
	{
		$from = array('"', '&', '>', '<', '\'');
		$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;');
		$s = str_replace($from, $to, $s);
		$s=preg_replace('!<[^>]*?>!', ' ', $s);
		// if ($this->from_charset!='windows-1251') $s = iconv($this->from_charset, 'windows-1251', $s);
		$s = preg_replace('#[\x00-\x08\x0B-\x0C\x0E-\x1F]+#is', ' ', $s);
		return trim($s);
	}

	function set_shop($name, $company, $url)
	{
		$this->shop['name'] = $this->prepare_field($name);
		$this->shop['name'] = substr($this->shop['name'], 0, 20);
		$this->shop['company'] = $this->prepare_field($company);
		$this->shop['url'] = $this->prepare_field($url);
	}

	function add_currency($id, $rate = 'CBRF', $plus = 0)
	{
		$rate = strtoupper($rate);
		$plus = str_replace(',', '.', $plus);
		if ($rate=='CBRF' && $plus>0)
			$this->currencies[] = array('id'=>$this->prepare_field(strtoupper($id)), 'rate'=>'CBRF', 'plus'=>(float)$plus);
		else
		{
			$rate = str_replace(',', '.', $rate);
			$this->currencies[] = array('id'=>$this->prepare_field(strtoupper($id)), 'rate'=>(float)$rate);
		}
		return true;
	}

	function add_category($name, $id, $parent_id = -1)
	{
		if ((int)$id<1||trim($name)=='') return false;
		if ((int)$parent_id>0)
			$this->categories[] = array('id'=>(int)$id, 'parentId'=>(int)$parent_id, 'name'=>$this->prepare_field($name));
		else
			$this->categories[] = array('id'=>(int)$id, 'name'=>$this->prepare_field($name));
		return true;
	}

	function add_offer($id, $data, $available = true)
	{
		$allowed = array('url', 'price', 'currencyId', 'categoryId', 'picture', 'store', 'pickup', 'delivery', 'name', 'vendor', 'vendorCode', 'model', 'description', 'sales_notes', 'downloadable', 'weight', 'dimensions', 'param', 'sales_notes', 'country_of_origin');
		$param = array();
		// $data['model'] = $data['id'].'_tovar';
		// $data['vendor'] = $data['id'].'_tovar';
		if(isset($data['param']))
			$param = $data['param'];
		foreach($data as $k => $v)
		{
			if (!in_array($k, $allowed)) unset($data[$k]);
			if($k != 'picture' && $k != 'param' && $k != 'rec' )
				$data[$k] = strip_tags($this->prepare_field($v));
		}
		$tmp = $data;
		$data = array();
		foreach($allowed as $key)
			if (isset($tmp[$key]) && !empty($tmp[$key]))
				$data[$key] = $tmp[$key]; # Порядок важен для Я.Маркета!!!
		
		$out = array('id' => $id, 'data' => $data, 'available' => ($available) ? 'true' : 'false');
		if(!$this->config->get('ya_market_prostoy'))
			$out['type'] = 'vendor.model';
		$this->offers[] = $out;
	}

	function get_xml_header()
	{
		return '<?xml version="1.0" encoding="utf-8"?><!DOCTYPE yml_catalog SYSTEM "shops.dtd"><yml_catalog date="'.date('Y-m-d H:i').'">';
	}

	function get_xml_shop()
	{
		$s = '<shop>' . "\r\n";
		$s .= $this->convert_array_to_tag($this->shop);
		$s .= '<currencies>' . "\r\n";
		foreach($this->currencies as $currency)
			$s .= $this->convert_array_to_attr($currency, 'currency');

		$s .= '</currencies>' . "\r\n";
		$s .= '<categories>' . "\r\n";
		foreach($this->categories as $category)
		{
			$category_name = $category['name'];
			unset($category['name']);
			$s .= $this->convert_array_to_attr($category, 'category', $category_name);
		}
		$s .= '</categories>' . "\r\n";
		if($this->config->get('ya_market_homecarrier'))
			$s .= '<local_delivery_cost>'.$this->config->get('ya_market_localcoast').'</local_delivery_cost>' . "\r\n";

		$s .= '<offers>' . "\r\n";
		foreach($this->offers as $offer)
		{
			$data = $offer['data'];
			unset($offer['data']);
			$s .= $this->convert_array_to_attr($offer, 'offer', $this->convert_array_to_tag($data));
		}
		$s .= '</offers>' . "\r\n";
		$s .= '</shop>';
		return $s;
	}

	function get_xml_footer()
	{
		return '</yml_catalog>';
	}

	function get_xml()
	{
		$xml = $this->get_xml_header();
		$xml .= $this->get_xml_shop();
		$xml .= $this->get_xml_footer();
		return $xml;
	}
}