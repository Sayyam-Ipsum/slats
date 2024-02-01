<?php

/**
 * @property Account $Account Accounting Account Model
 */
class Configuration extends MY_Model {

	protected $modelName = 'Configuration';
	protected $_table = 'configurations';
	protected $_listFieldName = 'name';
	protected $_fieldsNames = ['id', 'name', 'type', 'valueInt', 'valueStr'];
	protected $allowedNulls = ['valueInt', 'valueStr'];
	protected $variables = [];
	protected $keys = ['CompanyName', 'CompanyAddress', 'CompanyPhone', 'CompanyEmail', 'CompanyWebsite', 'CompanyLogo', 'Language', 'SystemCurrency', 'TestThree'];
	protected $numericFields = ['TestThree', 'Language', 'SystemCurrency'];
	private $serializedFields = [];
	protected $fileControls = [];

	public function __construct() {
		parent::__construct();
		$this->keys = array_merge(
				$this->get_conf_group_keys('set_1'),
				$this->get_conf_group_keys('set_2')
		);
		$this->validate = [
			'name' => ['required' => TRUE, 'allowEmpty' => FALSE, 'rule' => ['maxLength', 255], 'message' => sprintf($this->lang->line('required__max_length_rule'), 'Name', 255)],
			'type' => ['required' => TRUE, 'allowEmpty' => FALSE, 'rule' => 'numeric', 'message' => sprintf($this->lang->line('required__is_numeric_rule'), 'Type')],
			'valueInt' => ['required' => FALSE, 'allowEmpty' => TRUE, 'rule' => 'numeric', 'message' => sprintf($this->lang->line('is_numeric_rule'), 'Value Int')],
			'valueStr' => ['required' => FALSE, 'allowEmpty' => TRUE, 'rule' => ['maxLength', 255], 'message' => sprintf($this->lang->line('max_length_rule'), 'Value Str', 255)]
		];
		$this->variables = array_fill_keys($this->keys, NULL);
		$this->load_all_preferences();
	}

	private function get_conf_group_keys($grp) {
		$keys = [
			'set_1' => ['CompanyName', 'CompanyAddress', 'CompanyPhone', 'CompanyEmail', 'CompanyWebsite', 'CompanyLogo'],
			'set_2' => ['Language', 'TVA1', 'TVA2', 'SystemCurrency']		
		];
		return $keys[$grp];
	}

	public function get_conf_val($key) {
		return isset($this->variables[$key]) ? $this->variables[$key] : null;
	}

	public function get_values_by_keys($keys) {
		return array_intersect_key($this->variables, array_fill_keys($keys, ''));
	}

	public function load_configurations($keys = []) {
		$variables = $this->load_all_preferences();
		if (!empty($keys)) {
			$variables = array_intersect_key($variables, array_fill_keys($keys, ''));
		}
		return $variables;
	}

	public function load_all_preferences() {
		$this->variables = parent::load_list([
					'select' => [['name AS id'], ["IF(1 = type, valueStr, valueInt) AS name", false]],
					'where_in' => ['configurations.name', $this->keys],
					'order_by' => 'configurations.id'
				]) + $this->variables;
		foreach ($this->serializedFields as $k) {
			$this->variables[$k] = empty($this->variables[$k]) ? [] : ((array) unserialize($this->variables[$k]));
		}
		foreach ($this->variables as $k => $value) {
			if (in_array($k, $this->numericFields) && '' != $this->variables[$k]) {
				$this->variables[$k] = _gnv($value);
			}
		}
		return $this->variables;
	}

	public function save($formData) {
		$variables = $formData + $this->variables;
		$dataSample = array_fill_keys($this->_fieldsNames, null);
		unset($dataSample['id']);
		$dataSample['type'] = '0';
		$dataSample['valueInt'] = null;
		$dataSample['valueStr'] = null;
		$numericPreferences = array_intersect_key($variables, array_fill_keys($this->numericFields, NULL));
		$alphaPreferences = array_diff_key($variables + array_fill_keys($this->keys, NULL), $numericPreferences);
		$dataSet = [];
		foreach ($numericPreferences as $k => $v) {
			$dataSet[$k] = $dataSample;
			$dataSet[$k]['name'] = $k;
			$dataSet[$k]['valueInt'] = $v;
		}
		$dataSample['type'] = '1';
		foreach ($alphaPreferences as $k => $v) {
			if (in_array($k, $this->serializedFields)) {
				$v = empty($v) ? '' : serialize($v);
			}
			$dataSet[$k] = $dataSample;
			$dataSet[$k]['name'] = $k;
			$dataSet[$k]['valueStr'] = $v;
		}
		$this->db->empty_table('configurations');
		$res = $this->insert_on_duplicate_update_batch($dataSet);
		$this->adjust_auto_increment();
		return $res;
	}

	public function load_conf_group_form_elements($grp, $xtraClss = '') {
		$keys = $this->get_conf_group_keys($grp);
		$formElements = [];
		$this->load->library('inflector');
		foreach ($keys as $key) {
			$formElements[$key] = $this->get_form_html($key, $this->variables[$key], $xtraClss);
		}
		return $formElements;
	}

	private function get_form_html($keyName, $keyValue, $xtraClss = '') {
		$lower_undercase = $this->inflector->underscore($keyName); // revenue_account
		if (method_exists($this, "get_form_html_{$lower_undercase}")) {
			return call_user_func([$this, "get_form_html_{$lower_undercase}"], $keyName, $keyValue, $lower_undercase, $xtraClss);
		} else {
			return '<div class="form-group ' . $xtraClss . '"><label for="' . $keyName . '" class="col-sm-6 control-label">'
					. $this->lang->line($lower_undercase) . '</label><div class="col-sm-6">'
					. form_input(['name' => 'variables[' . $keyName . ']', 'id' => $keyName, 'type' => 'text', 'value' => $keyValue, 'class' => 'form-control', 'autocomplete' => 'on'])
					. '</div></div>';
		}
	}

	private function get_form_html_sample_var($keyName, $keyValue, $lower_undercase, $xtraClss) {
		return $this->get_form_html_from_month($keyName, $keyValue, $lower_undercase, $xtraClss);
	}

	private function get_form_html_test_three($keyName, $keyValue, $lower_undercase, $xtraClss) {
		return $this->_form_radio_buttons($keyName, $keyValue, $lower_undercase, $xtraClss);
	}

	private function get_form_html_company_logo($keyName, $keyValue, $lower_undercase, $xtraClss) {
		return $this->get_form_upload_image($keyName, $keyValue, $lower_undercase, $xtraClss);
	}

	private function get_form_html_language($keyName, $keyValue, $lower_undercase, $xtraClss) {
		return $this->get_form_language($keyName, $keyValue, $lower_undercase, $xtraClss);
	}

	private function get_form_html_system_currency($keyName, $keyValue, $lower_undercase, $xtraClss) {
		return $this->get_form_systemcurrency($keyName, $keyValue, $lower_undercase, $xtraClss);
	}

	private function _form_radio_buttons($keyName, $keyValue, $lower_undercase, $xtraClss, $options = ['0' => 'no', '1' => 'yes'], $inline = '-inline') {
		$html = '<div class="form-group ' . $xtraClss . '"><label for="' . $keyName . '" class="col-sm-6 control-label">'
				. $this->lang->line($lower_undercase) . '</label><div class="col-sm-6">';
		foreach ($options as $v => $l) {
			$html .= '<label class="radio' . $inline . '">'
					. form_radio("variables[{$keyName}]", $v, $v == $keyValue, 'id="' . $keyName . '_' . $v . '"') . ' ' . $this->lang->line($l)
					. '</label>';
		}
		return $html . '</div></div>';
	}

	private function get_form_html_from_month($keyName, $keyValue, $lower_undercase, $xtraClss) {
		return '<div class="form-group ' . $xtraClss . '"><label for="accountNumberLkUp" class="col-sm-6 control-label">'
				. $this->lang->line($lower_undercase) . '</label><div class="col-sm-6">'
				. form_dropdown("variables[{$keyName}]", $this->get_months_array(), $keyValue, "id=\"{$keyName}\" class = \"form-select\"")
				. '</div></div>';
	}

	private function get_form_systemcurrency($keyName, $keyValue, $lower_undercase, $xtraClss) {
		return '<div class="form-group ' . $xtraClss . '"><label for="accountNumberLkUp" id="labelcurrency" class="col-sm-6 control-label">'
				. $this->lang->line($lower_undercase) . '</label><div class="col-sm-6">'
				. form_dropdown("variables[{$keyName}]", $this->get_currency_types(), $keyValue, "id=\"{$keyName}\" class = \"form-select\"")
				. '</div></div>';
	}

	private function get_form_language($keyName, $keyValue, $lower_undercase, $xtraClss) {
		return '<div class="form-group ' . $xtraClss . '"><label for="accountNumberLkUp" class="col-sm-6 control-label">'
				. $this->lang->line($lower_undercase) . '</label><div class="col-sm-6">'
				. form_dropdown("variables[{$keyName}]", ["English"], $keyValue, "id=\"{$keyName}\" class = \"form-select\"")
				. '</div></div>';
	}

	private function get_form_upload_image($keyName, $keyValue, $lower_undercase, $xtraClss) {
		return '<div class="form-group ' . $xtraClss . '"><label for="' . $keyName . '" class="col-sm-6 control-label">'
					. $this->lang->line($lower_undercase) . '</label><div class="col-sm-6">'
					. form_input(['name' => 'variables[' . $keyName . ']', 'id' => $keyName, 'type' => 'file', 'value' => $keyValue, 'class' => 'form-control', 'autocomplete' => 'on'])
					. '</div></div>';
	}

	private function get_months_array() {
		return [
			$this->lang->line('select_month'),
			$this->lang->line('january'),
			$this->lang->line('february'),
			$this->lang->line('march'),
			$this->lang->line('april'),
			$this->lang->line('may'),
			$this->lang->line('june'),
			$this->lang->line('july'),
			$this->lang->line('august'),
			$this->lang->line('september'),
			$this->lang->line('october'),
			$this->lang->line('november'),
			$this->lang->line('december')
		];
	}

	private function get_currency_types(){
		$this->load->model('Currency');
		return $this->Currency->load_currencies_list();
	}

	public function fetch_company_name(){
		$this->db->select('valueStr');
		$this->db->from('configurations');
		$this->db->where('name', "CompanyName");
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_company_address(){
		$this->db->select('valueStr');
		$this->db->from('configurations');
		$this->db->where('name', "CompanyAddress");
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_company_phone(){
		$this->db->select('valueStr');
		$this->db->from('configurations');
		$this->db->where('name', "CompanyPhone");
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_company_email(){
		$this->db->select('valueStr');
		$this->db->from('configurations');
		$this->db->where('name', "CompanyEmail");
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_company_website(){
		$this->db->select('valueStr');
		$this->db->from('configurations');
		$this->db->where('name', "CompanyWebsite");
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_TVA1(){
		$this->db->select('valueStr');
		$this->db->from('configurations');
		$this->db->where('name', "TVA1");
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_TVA2(){
		$this->db->select('valueStr');
		$this->db->from('configurations');
		$this->db->where('name', "TVA2");
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_local_currency(){
		$this->db->select('valueInt');
		$this->db->from('configurations');
		$this->db->where('name', "SystemCurrency");
		$query = $this->db->get()->row_array();
		return $query;
	}

}
