<?php

defined('BASEPATH') or die('No direct script access allowed');

class Currency extends MY_Model
{

	protected $modelName = 'Currency';
	protected $_table = 'currencies';
	protected $_listFieldName = 'currency_code';
	protected $_fieldsNames = ['id', 'currency_rate', 'currency_code', 'currency_name'];
	protected $allowedNulls = [];

	public function __construct()
	{
		parent::__construct();
		$this->validate = [
			'currency_rate' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('currency_rate'))
			],
			'currency_code' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => ['maxLength', 3],
				'message' => sprintf($this->lang->line('required__max_length_rule'), $this->lang->line('currency_code'), 3)
			],
			'currency_name' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => ['maxLength', 255],
				'message' => sprintf($this->lang->line('required__max_length_rule'), $this->lang->line('currency_name'), 255)
			]
		];
	}

	public function paginate_currencies()
	{
		$query = [
			'select' => "currencies.*",
			'order_by' => [['currency_code', 'ASC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_currencies_data_tables()
	{
		$dt = [
			'columns' => [
				'currencies.currency_name', 'currencies.currency_code', 'currencies.currency_rate', 'currencies.id'
			],
			'query' => [],
			'search_in' => ['currencies.currency_rate', 'currencies.currency_code', 'currencies.currency_name']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function load_currencies_list()
	{
		return $this->load_list([], ['firstLine' => ['' => $this->lang->line('currency')]]);
	}

	//	public function load_currencies_list_customized() {
	//		return $this->load_list([
	//			'select' => ["id, CONCAT(currency_code, ' - ', currency_name) AS testName"],
	//		], ['value' => 'testName', 'firstLine' => ['' => $this->lang->line('currency')]]);
	//	}

	public function fetch_currency_rate($id)
	{
		$this->db->select('currency_rate');
		$this->db->from('currencies');
		$this->db->where('currencies.id', $id);
		$query = $this->db->get()->row_array();
		return $query;
	}
	public function get_sys_config_cur()
	{
		$this->load->model('Configuration');
		$this->db->select('valueint');
		$this->db->from('configurations');
		$this->db->where( array(
			'name' => 'SystemCurrency'));
		$query = $this->db->get()->row_array();
		return $query;		
	}
	
	public function fetch_currency_code($id)
	{
		$this->db->select('currency_code');
		$this->db->from('currencies');
		$this->db->where('currencies.id', $id);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function check_if_currency_is_active($id){
		$this->db->select('COUNT(*) as count');
		$this->db->from('transactions');
		$this->db->where('transactions.currency_id', $id);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_currency_id_by_code($code)
	{
		$this->db->select('id');
		$this->db->from('currencies');
		$this->db->where('currencies.currency_code', $code);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_currency_rate_currency_name($currency_name){
		$this->db->select('currency_rate');
		$this->db->from('currencies');
		$this->db->where('currencies.currency_name', $currency_name);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function load_currency_data($currency_id){
		$this->db->select('*');
		$this->db->from('currencies');
		$this->db->where('currencies.id', $currency_id);
		$query = $this->db->get()->row_array();
		return $query;
	}
}
