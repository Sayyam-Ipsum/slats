<?php

defined('BASEPATH') or die('No direct script access allowed');

class Journal_account extends MY_Model
{

	protected $modelName = 'Journal_account';
	protected $_table = 'journal_accounts';
	protected $_listFieldName = 'id';
	protected $_fieldsNames = ['journal_id', 'account_id', 'mvt_type', 'amount', 'value_date', 'description', 'id'];
	protected $_dateFields = ['value_date'];
	protected $allowedNulls = ['description'];

	public function __construct()
	{
		parent::__construct();
		$this->validate = [
			'journal_id' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('transaction'))
			],
			'account_id' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('item'))
			],
			'mvt_type' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('mvt_type'))
			],
			'amount' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('qty'))
			],
			'value_date' => [
				'required' => FALSE,
				'allowEmpty' => TRUE,
				'rule' => ['date', 'dmy'],
				'message' => sprintf($this->lang->line('date_rule'), $this->lang->line('value_date'))
			],

		];
	}


	public function paginate_journal_accounts()
	{
		$query = [
			'select' => [
				["CONCAT_WS('/', journals.journal_type, journals.auto_no) AS journal"],
				["CONCAT_WS('/', t.trans_type, t.auto_no) AS transaction"],
				["accounts.account_name"],
				["(IF(journal_accounts.mvt_type = '1', 'Debit', 'Credit')) AS credit_debit"], 
				["journal_accounts.amount, journal_accounts.value_date, journal_accounts.description"],
			],
			'join' => [
				['accounts', 'accounts.id = journal_accounts.account_id', 'inner'],
				['journals', 'journals.id = journal_accounts.journal_id', 'inner'],
				['transactions AS t', 't.id = journals.transaction_id', 'left'],
			],
			'where' => [
				["journals.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_journal_accounts_data_tables()
	{
		$dt = [ 
			'columns' => [
				['CONCAT_WS("/", t.trans_type, t.auto_no)', 'transaction'],
				['CONCAT_WS("/", journals.journal_type, journals.auto_no)' , 'journal'],
				'accounts.account_name', 
				["IF(journal_accounts.mvt_type = '1', 'Debit', 'Credit')", 'credit_debit']
				,' journal_accounts.amount', 'journal_accounts.value_date', 'journal_accounts.description'
			],
			'query' => [
				'join' => [
					['accounts', 'accounts.id = journal_accounts.account_id', 'inner'],
					['journals', 'journals.id = journal_accounts.journal_id', 'inner'],
					['transactions AS t', 't.id = journals.transaction_id', 'left'],
				],
				'where' => [
					["journals.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
				]
			],
			'search_in' => ['accounts.account_name', 'journals.journal_type', 'journals.auto_no']
		];

		return parent::load_datatables_pagedata($dt);
	}

	public function fetch_journal_accounts_id_by_journal_id($journal_id)
	{
		$query = [
			'select' => "journal_accounts.id",
			'join' => [
				['journals', 'journals.id = journal_accounts.journal_id', 'inner']
			],
			'where' => [
				["journal_accounts.journal_id", $journal_id],
				["journals.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load_all($query);
	}
}
