<?php

defined('BASEPATH') or die('No direct script access allowed');

class Transaction_item extends MY_Model
{

	protected $modelName = 'Transaction_item';
	protected $_table = 'transaction_items';
	protected $_listFieldName = 'item_id';
	protected $_fieldsNames = ['id', 'transaction_id', 'item_id', 'warehouse_id', 'qty', 'mvt_type', 'cost', 'price', 'discount', 'net_cost', 'profit', 'item_profit', 'profit_percent', 'checkbox', 'account_id', 'relation_id', 'pickedup_qty'];
	protected $allowedNulls = [];

	public function __construct()
	{
		parent::__construct();
		$this->validate = [
			'transaction_id' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('transaction'))
			],
			'item_id' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('item'))
			],
			'qty' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('qty'))
			],
			'mvt_type' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('mvt_type'))
			],
			'cost' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('cost'))
			],
			'price' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('price'))
			],
			'discount' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('discount'))
			],
			'net_cost' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('net_cost'))
			]
		];
	}

	public function paginate_transactions_items()
	{
		$this->load->model(['Transaction', 'Item']);
		$TransactionsFld = $this->Transaction->get('_listFieldName');
		$ItemsFld = $this->Item->get('_listFieldName');
		$query = [
			'select' => "transactions_items.*, transactions.{$TransactionsFld} as Transaction, items.{$ItemsFld} as Item",
			'join' => [
				['transactions', 'transactions.id = transactions_items.transaction_id', 'inner'],
				['items', 'items.id = transactions_items.item_id', 'inner']
			],
			'order_by' => [['item_id', 'ASC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_transactions_items_data_tables()
	{
		$this->load->model(['Transaction', 'Item']);
		$TransactionsFld = $this->Transaction->get('_listFieldName');
		$ItemsFld = $this->Item->get('_listFieldName');
		$init_override = [
			'columns' => [
				'transactions_items.qty', 'transactions_items.mvt_type', 'transactions_items.cost', 'transactions_items.price', 'transactions_items.discount', 'transactions_items.id',
				['transactions.' . $TransactionsFld, 'Transaction'],
				['items.' . $ItemsFld, 'Item']
			],
			'customQuery' => ['join' => [
				['transactions', 'transactions.id = transactions_items.transaction_id', 'inner'],
				['items', 'items.id = transactions_items.item_id', 'inner']
			]],
			'search_in' => ['transactions_items.qty', 'transactions_items.mvt_type', 'transactions_items.cost', 'transactions_items.price', 'transactions_items.discount', 'transactions.' . $TransactionsFld, 'items.' . $ItemsFld]
		];
		$init_override['customQuery']['select'] = $this->columns_arra_to_str($init_override['columns']);
		return parent::load_data_tables_results($init_override);
	}

	public function fetch_qty_from_items_and_transaction_items($id)
	{
		$query = [
			'select' => "transaction_items.mvt_type, items.qty",
			'join' => [
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner']
			],
			'where' => [
				["transaction_items.id", $id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load_all($query);
	}

	public function fetch_trans_item_id($transaction_id)
	{
		$query = [
			'select' => "transaction_items.id",
			'join' => [
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner']
			],
			'where' => [
				["transaction_items.transaction_id", $transaction_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load_all($query);
	}

	public function load_all_trans_items($trans_id)
	{
		$query = [
			'select' => "transaction_items.*, items.description ,items.category, items.barcode, items.brand, items.EAN, items.artical_number, items.alternative, warehouses.warehouse, warehouses.shelf, items.cost as item_cost",
			'join' => [
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['warehouses', 'warehouses.id = transaction_items.warehouse_id', 'inner'],
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner']
			],
			'where' => [
				["transaction_items.transaction_id", $trans_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			],
			'order_by' => [['transaction_items.id', 'ASC']]
		];
		return $this->load_all($query);
	}

	public function fetch_trans_items_id_for_edit($trans_id)
	{
		$query = [
			'select' => "transaction_items.id",
			'join' => [
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner']
			],
			'where' => [
				["transaction_items.transaction_id", $trans_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load_all($query);
	}

	public function load_all_trans_items_grouped_by_item_id($trans_id)
	{
		$query = [
			'select' => "MIN(transaction_items.item_id) as item_id, MIN(items.description) as description, MIN(items.barcode) as barcode, SUM(transaction_items.qty) as qty",
			'join' => [
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner']
			],
			'where' => [
				["transaction_items.transaction_id", $trans_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			],
			'group_by' => ["item_id"]
		];
		return $this->load_all($query);
	}

	public function fetch_trans_data_by_trans_item_id($trans_item_id)
	{
		$this->db->select('transactions.*');
		$this->db->from('transactions');
		$this->db->join('transaction_items', 'transactions.id = transaction_items.transaction_id');
		$this->db->where('transaction_items.id', $trans_item_id);
		$this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_trans_item_id_by_item_and_id_trans_id($item_id, $trans_id)
	{
		$this->db->select('Max(transaction_items.id) as id');
		$this->db->from('transaction_items');
		$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id', 'inner');
		$this->db->where('transaction_items.transaction_id', $trans_id);
		$this->db->where('transaction_items.item_id', $item_id);
		$this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_trans_item_data($id)
	{
		$this->db->select('transaction_items.*, transactions.currency_rate, transactions.currency_id, transactions.trans_type, items.EAN, items.artical_number, items.brand, items.cost as item_cost');
		$this->db->from('transaction_items');
		$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id', 'inner');
		$this->db->join('items', 'items.id = transaction_items.item_id', 'inner');
		$this->db->where('transaction_items.id', $id);
		$this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function load_all_trans_items_for_QO_AND_OS($trans_id)
	{
		$query = [
			'select' => "transaction_items.*, items.description ,items.category, items.barcode, items.EAN, items.brand, items.artical_number, items.cost as item_cost",
			'join' => [
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner']
			],
			'where' => [
				["transaction_items.transaction_id", $trans_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load_all($query);
	}

	public function group_update_of_status($ids, $status, $date)
	{
		foreach ($ids as $id) {
			if ($status === "Ordered" || $status === "Delivered") {
				$data = array(
					'status' => $status,
					'status_date' => $date
				);
			} else {
				$data = array(
					'status' => $status
				);
			}
			$this->db->where('id', $id);
			$this->db->update('transaction_items', $data);
		}
	}

	public function load_activity_report_data($data)
	{
		$query = [
			'select' => "transactions.auto_no, transactions.trans_type, DATE_FORMAT(transactions.trans_date,'%d-%m-%Y') as trans_date, transactions.model, items.description, items.artical_number, items.brand, transaction_items.qty, transaction_items.price, (transaction_items.qty * transaction_items.price) as tot, currencies.currency_code",
			'join' => [
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['accounts', 'accounts.id = transactions.account_id', 'inner'],
			],
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			],
			'where_in' => ["transactions.trans_type", ["QU", "OS", "SA"]],
			'order_by' => [['transactions.trans_date', 'ASC']]
		];
		if ($data["customer_name"] !== '') {
			$query['where'] = ["accounts.account_name", $data["customer_name"]];
		}
		if ($data["trans_date"] !== '' && $data["to_date"] !== '') {
			$query["where"][] = ["transactions.trans_date >=", date("Y-m-d", strtotime($data["trans_date"]))];
			$query["where"][] = ["transactions.trans_date <=", date("Y-m-d", strtotime($data["to_date"]))];
		}
		if ($data["VIN"] !== '') {
			$query['where'] = ["transactions.VIN", $data["VIN"]];
		}
		return $this->load_all($query);
	}

	public function add_adjust_trans_items($trans_id, $item_id, $warehouse_id, $mvt_type, $qty)
	{
		$data = array(
			'transaction_id' => $trans_id,
			'item_id' => $item_id,
			'warehouse_id' => $warehouse_id,
			'mvt_type' => $mvt_type,
			'qty' => $qty,
			'cost' => 0,
			'price' => 0,
			'net_cost' => 0,
			'discount' => 0
		);
		$query = $this->db->insert('transaction_items', $data);
		return $query;
	}

	public function update_transaction_item_warehouse_id($trans_item_id, $warehouse_id)
	{
		$data = [
			'warehouse_id' => $warehouse_id
		];
		$this->db->where('id', $trans_item_id);
		return $this->db->update('transaction_items', $data);
	}

	public function load_all_trans_items_for_op($trans_id)
	{
		$query = [
			'select' => "transaction_items.*, items.description ,items.category, items.barcode, items.brand, items.EAN, items.artical_number, items.alternative, warehouses.warehouse, warehouses.shelf, items.cost as item_cost, accounts.account_name",
			'join' => [
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['warehouses', 'warehouses.id = transaction_items.warehouse_id', 'inner'],
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner'],
				['accounts', 'accounts.id = transaction_items.account_id', 'left'],
			],
			'where' => [
				["transaction_items.transaction_id", $trans_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			],
			'order_by' => [['transaction_items.id', 'ASC']]
		];
		return $this->load_all($query);
	}

	public function update_trans_item_relation_id($os_id, $item_id, $op_id)
	{
		$data = array(
			'relation_id' => $op_id
		);
		$this->db->where('transaction_id', $os_id);
		$this->db->where('item_id', $item_id);
		$this->db->update('transaction_items', $data);
	}

	public function load_related_order_purchase_items($os_id){
		$query = [
			'select' => "transaction_items.*",
			'join' => [
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner'],
			],
			'where' => [
				["transaction_items.relation_id", $os_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load_all($query);
	}

	public function updated_pickedup_qty($trans_item_id, $pickuped_qty){
		$data = array(
			'pickedup_qty' => $pickuped_qty
		);
		$this->db->where('id', $trans_item_id);
		return $this->db->update('transaction_items', $data);
	}

	public function load_all_trans_items_by_auto_no_and_type($auto_no, $trans_type)
	{
		$query = [
			'select' => "tr1.auto_no as op_nb, tr1.id as op_id, transactions.auto_no, transactions.id as os_id, transaction_items.*, items.description ,items.category, items.barcode, items.EAN, items.brand, items.artical_number, items.cost as item_cost, warehouses.warehouse, warehouses.shelf",
			'join' => [
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner'],
				['warehouses', 'warehouses.id = transaction_items.warehouse_id', 'inner'],
				['transactions as tr1', 'tr1.id = transaction_items.relation_id', 'left'],
			],
			'where' => [
				["transactions.auto_no", $auto_no],
				["transactions.trans_type", $trans_type],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load_all($query);
	}

	public function load_order_purchase_pickedup_qty_for_item_customer($customer_id, $item_id, $op_id, $os_id){
		$query = [
			'select' => "transaction_items.*",
			'join' => [
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner']
			],
			'where' => [
				["transaction_items.transaction_id", $op_id],
				["transaction_items.item_id", $item_id],
				["transaction_items.relation_id", $os_id],
				["transaction_items.account_id", $customer_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load($query);
	}

	public function load_trans_item_data_by_item_id_and_trans_id($item_id, $trans_id){
		$query = [
			'select' => "transaction_items.*",
			'join' => [
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner']
			],
			'where' => [
				["transaction_items.transaction_id", $trans_id],
				["transaction_items.item_id", $item_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load($query);
	}

	public function update_trans_item_qty($trans_item_id, $qty){
		$data = array(
			'qty' => $qty
		);
		$this->db->where('transaction_items.id', $trans_item_id);
		return $this->db->update('transaction_items', $data);
	}

	public function updated_trans_item_pickedup_qty_by_trans_id_item_id_and_account_id($trans_id, $item_id, $account_id, $pickup_qty){
		$data = array(
			'pickedup_qty' => $pickup_qty
		);
		$this->db->where('transaction_items.transaction_id', $trans_id);
		$this->db->where('transaction_items.item_id', $item_id);
		$this->db->where('transaction_items.account_id', $account_id);
		return $this->db->update('transaction_items', $data);
	}

	public function update_trans_item_pickedup_qty($trans_item_id, $pickedup_qty){
		$data = array(
			'pickedup_qty' => $pickedup_qty
		);
		$this->db->where('transaction_items.id', $trans_item_id);
		return $this->db->update('transaction_items', $data);
	}

	public function load_all_trans_items_grouped_by_item($trans_id)
	{
		$query = [
			'select' => "transaction_items.*, items.description ,items.category, items.barcode, items.brand, items.EAN, items.artical_number, items.alternative, warehouses.warehouse, warehouses.shelf, items.cost as item_cost, SUM(transaction_items.qty) as qty, transactions.auto_no as op_nb, accounts.account_name as supplier_name",
			'join' => [
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['warehouses', 'warehouses.id = transaction_items.warehouse_id', 'inner'],
				['transactions', 'transactions.id = transaction_items.transaction_id', 'inner'],
				['accounts', 'accounts.id = transactions.account_id', 'inner'],
			],
			'where' => [
				["transaction_items.transaction_id", $trans_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			],
			'group_by' => ['items.id'],
			'order_by' => [['transaction_items.id', 'ASC']]
		];
		return $this->load_all($query);
	}
}
