<?php

defined('BASEPATH') OR die('No direct script access allowed');

class Fiscal_year extends MY_Model {

	protected $modelName = 'Fiscal_year';
	protected $_table = 'fiscal_years';
	protected $_listFieldName = 'year_name';
	protected $_fieldsNames = ['id', 'year_name', 'start_date', 'end_date'];
	protected $allowedNulls = [];
	protected $_dateFields = ['start_date', 'end_date'];

	public function __construct() {
		parent::__construct();
		$this->validate = [
			'year_name' => [
				'required' => [
					'required' => true, 'allowEmpty' => false, 'rule' => ['maxLength', 255],
					'message' => sprintf($this->lang->line('required_rule'), 'Name', 255)
				],
				'unique' => [
					'rule' => 'isUnique',
					'message' => sprintf($this->lang->line('is_unique_rule'), 'Name', 255)
				]
			],
			'start_date' => [
				'required' => true,
				'allowEmpty' => false,
				'rule' => ['date', 'dmy'],
				'message' => sprintf($this->lang->line('required_date_rule'), 'Start Date')
			],
			'end_date' => [
				'required' => true,
				'allowEmpty' => false,
				'rule' => ['date', 'dmy'],
				'message' => sprintf($this->lang->line('required_date_rule'), 'End Date')
			]
		];
	}

	public function paginate_fiscal_years() {
		$query = ['select' => "fiscal_years.*", 'order_by' => [['year_name', 'ASC']]];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_fiscal_years_data_tables() {
		$dt = [
			'columns' => ['fiscal_years.year_name', 'fiscal_years.start_date', 'fiscal_years.end_date', 'fiscal_years.id'],
			'query' => [],
			'search_in' => ['fiscal_years.year_name']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function load_fiscal_years_list() {
		return $this->load_list(['order_by' => ['year_name', 'DESC']], ['firstLine' => ['' => $this->lang->line('choose_year')]]);
	}

	public function check_if_fiscal_year_is_active($id){
		$this->db->select('COUNT(*) as count');
		$this->db->from('transactions');
		$this->db->where('transactions.fiscal_year_id', $id);
		$query = $this->db->get()->row_array();
		return $query;
	}

}
