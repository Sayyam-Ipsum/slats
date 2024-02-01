<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * @property Transaction_item $Transaction_item
 * @property Item $Item
 */
class Transaction extends MY_Model
{

	const PurchaseTransType = 'PU';
	const SaleTransType = 'SA';
	const ReturnPurchaseTransType = 'RP';
	const ReturnSaleTransType = 'RS';
	const QuatationTransType = 'QU';
	const OpeningItemTransType = 'OI';
	const DeliveryNoteTransType = 'DN';
	const OrderTransType = 'OS';
	const MissingProductsTransType = 'MP';
	const TransfersTransType = 'TR';
	const OrderPurchaseTransType = 'OP';
	const AdjustTransType = 'AD';

	protected $modelName = 'Transaction';
	protected $_table = 'transactions';
	protected $_listFieldName = 'auto_no';
	protected $_fieldsNames = ['id', 'fiscal_year_id', 'trans_type', 'auto_no', 'trans_date', 'value_date', 'account_id', 'account2_id', 'currency_id', 'currency_rate', 'discount', 'user_id', 'user2_id', 'driver_id', 'employee_id', 'status', 'delivered', 'pickup', 'to_invoice', 'description', 'relation_id', 'VIN', 'model', 'OE', 'TVA', 'delivery_type', 'delivery_charge', 'tracking_number', 'payment_method', 'transaction_number', 'pfand', 'locked', 'edit_user_id', 'transfered', 'invoice_related_nb', 'order_id'];
	protected $_dateFields = ['trans_date', 'value_date'];
	protected $allowedNulls = ['value_date', 'discount'];

	public function __construct()
	{
		parent::__construct();
		$this->validate = [
			'fiscal_year_id' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('fiscal_year'))
			],
			'trans_type' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => ['maxLength', 2],
				'message' => sprintf($this->lang->line('required__max_length_rule'), $this->lang->line('trans_type'), 2)
			],
			'auto_no' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('auto_no'))
			],
			'trans_date' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => ['date', 'dmy'],
				'message' => sprintf($this->lang->line('required_date_rule'), $this->lang->line('trans_date'))
			],
			'value_date' => [
				'required' => FALSE,
				'allowEmpty' => TRUE,
				'rule' => ['date', 'dmy'],
				'message' => sprintf($this->lang->line('date_rule'), $this->lang->line('value_date'))
			],
			'account_id' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('account'))
			],
			'account2_id' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('account2'))
			],
			'currency_id' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('currency'))
			],
			'currency_rate' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('currency_rate'))
			],
			'discount' => [
				'required' => FALSE,
				'allowEmpty' => TRUE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('is_numeric_rule'), $this->lang->line('discount'))
			],
			'user_id' => [
				'required' => TRUE,
				'allowEmpty' => FALSE,
				'rule' => 'numeric',
				'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('user'))
			]
		];
	}

	public function get_transaction_types_list()
	{
		return [
			self::PurchaseTransType => 'Purchase',
			self::ReturnPurchaseTransType => 'Return Purchase',
			self::SaleTransType => 'Sale',
			self::ReturnSaleTransType => 'Return Sale',
			self::QuatationTransType => 'Quatation',
			self::OpeningItemTransType => 'Opening Items',
			self::DeliveryNoteTransType => 'Delivery Note',
			self::OrderTransType => 'Order',
			self::MissingProductsTransType => 'Missing Products',
			self::TransfersTransType => 'Transfers',
			self::OrderPurchaseTransType => 'Order Purchase',
			self::AdjustTransType => 'Adjust'
		];
	}
	public function paginate_order_purchases()
	{
		return $this->paginate_transactions(self::OrderPurchaseTransType, true);
	}
	public function paginate_orders()
	{
		return $this->paginate_transactions(self::OrderTransType, false);
	}
	public function paginate_delivery_notes()
	{
		return $this->paginate_transactions(self::DeliveryNoteTransType, false);
	}
	public function paginate_quotations()
	{
		return $this->paginate_transactions(self::QuatationTransType, false);
	}
	public function paginate_purchases()
	{
		return $this->paginate_transactions(self::PurchaseTransType, true);
	}
	public function paginate_sales()
	{
		return $this->paginate_transactions(self::SaleTransType, false);
	}
	public function paginate_return_purchases()
	{
		return $this->paginate_transactions(self::ReturnPurchaseTransType, false);
	}
	public function paginate_return_sales()
	{
		return $this->paginate_transactions(self::ReturnSaleTransType, false);
	}
	private function paginate_transactions($transType, $use_cost)
	{
		if ($use_cost === true) {
			$total = 'ROUND(SUM(( ti.qty * ((ti.price * (1 - ti.discount/100))+ (ti.price * (ti.cost/100))))) - transactions.discount,2)';
		} else {
			$total = 'ROUND(SUM((ti.qty * ti.price * (1 - ti.discount/100))) - transactions.discount,2)';
		}
		$query = [
			'select' => [
				["transactions.auto_no, transactions.invoice_related_nb, transactions.trans_date, transactions.value_date"],
				["account1.account_name AS account1, account2.account_name AS account2"],
				["currencies.currency_code, transactions.currency_rate, transactions.discount, $total AS total"],
				["users.user_name, transactions.status, transactions.id"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items', 'ti.item_id = items.id', 'left'],
				['users', 'transactions.user_id = users.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", $transType],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

    public function fetch_nb_of_orders()
    {
        $this->db->select('COUNT(*) AS count');
        $this->db->from('transactions');
        $this->db->where('transactions.trans_type', self::OrderTransType);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function fetch_sum_of_invoices_by_date($date)
    {
        //SELECT sum(cost) FROM adudimih_mazen.transaction_items ti, adudimih_mazen.transactions t where t.id = ti.transaction_id and t.trans_type = 'SA' and t.trans_date = '2023-03-14';
        $this->db->select('SUM(ti.cost) AS sum');
        $this->db->from('transaction_items ti, transactions t');
        $this->db->where('t.id = ti.transaction_id');
        $this->db->where('t.trans_type', self::SaleTransType);
        $this->db->where('t.trans_date', $date);
        $query = $this->db->get()->row_array();
        return $query;
    }


	public function load_transactions_data_tables($transType, $use_cost)
	{
		if ($use_cost === true) {
			$total = 'ROUND(SUM(( ti.qty * ((ti.price * (1 - ti.discount/100))+ (ti.price * (ti.cost/100))))) - transactions.discount,2)';
		} else {
			$total = 'ROUND(SUM((ti.qty * ti.price * (1 - ti.discount/100))) - transactions.discount,2)';
		}
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.invoice_related_nb', 'transactions.trans_date', 'transactions.value_date',
				['account1.account_name', 'account1'], ['account2.account_name', 'account2'],
				'currencies.currency_code', 'transactions.currency_rate',
				'transactions.discount', [$total, 'total'], 'users.user_name', 'transactions.status', 'transactions.id'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
					['items', 'ti.item_id = items.id', 'left'],
					['users', 'transactions.user_id = users.id', 'left']
				],
				'where' => [
					["{$this->_table}.trans_type", $transType],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'transactions.currency_rate', 'transactions.discount', 'transactions.account_id', 'transactions.account2_id', 'account1.account_name', 'account2.account_name', 'items.EAN', 'items.artical_number', 'items.barcode']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function save_transaction_items($items, $mvtType)
	{
		$this->load->model('Warehouse');
		$this->load->model('Transaction_item');
		$this->load->model('Item');
		$transaction_id = _gnv($this->get_field('id'));
		foreach ($items as $item) {
			$warehouse_id = $this->Warehouse->get_warehouse_id_by_warehouse_and_shelf($item["warehouses"], $item["shelfs"]);
			$this->Transaction_item->reset_fields();
			$this->Transaction_item->set_fields($item);
			$this->Transaction_item->set_field('transaction_id', $transaction_id);
			$this->Transaction_item->set_field('warehouse_id', $warehouse_id["id"]);
			$this->Transaction_item->set_field('mvt_type', $mvtType);
			// $this->Transaction_item->set_field('cost', $this->Item->fetch_item_cost($item["item_id"])["0"]["cost"]);
			$this->Transaction_item->set_field('net_cost', "0");
			$this->Transaction_item->insert();
		}
	}

	public function save_trans_items_with_cost($items, $mvtType)
	{
		$this->load->model('Warehouse');
		$this->load->model('Transaction_item');
		$this->load->model('Item');
		$transaction_id = _gnv($this->get_field('id'));
		foreach ($items as $item) {
			$warehouse_id = $this->Warehouse->get_warehouse_id_by_warehouse_and_shelf($item["warehouses"], $item["shelfs"]);
			$this->Transaction_item->reset_fields();
			$this->Transaction_item->set_fields($item);
			$this->Transaction_item->set_field('transaction_id', $transaction_id);
			$this->Transaction_item->set_field('warehouse_id', $warehouse_id["id"]);
			$this->Transaction_item->set_field('mvt_type', $mvtType);
			$this->Transaction_item->set_field('cost', $this->Item->fetch_item_cost($item["item_id"])["0"]["cost"]);
			$this->Transaction_item->set_field('net_cost', "0");
			$this->Transaction_item->insert();
		}
	}

	public function save_purchases_items($items, $mvtType)
	{
		$this->load->model('Warehouse');
		$this->load->model('Transaction_item');
		$transaction_id = _gnv($this->get_field('id'));
		foreach ($items as $item) {
			$warehouse_id = $this->Warehouse->get_warehouse_id_by_warehouse_and_shelf($item["warehouses"], $item["shelfs"]); 
			$this->Transaction_item->reset_fields();
			$this->Transaction_item->set_fields($item);
			$this->Transaction_item->set_field('transaction_id', $transaction_id);
			$this->Transaction_item->set_field('warehouse_id', $warehouse_id["id"]);
			$this->Transaction_item->set_field('mvt_type', $mvtType);
			$this->Transaction_item->set_field('net_cost', $item["price"] * (1 + ($item["cost"] / 100)));
			$this->Transaction_item->insert();
		}
	}

	public function update_cost_and_price($items, $currency_rate, $currency_code)
	{
		$this->load->model('Item');
		foreach ($items as $item) {
			$i = $this->Item->fetch_item($item["item_id"])["0"];
			$cost = ($item["price"] + $item["cost"]) * $currency_rate;
			$pcost = $item["price"] + $item["cost"];
			if ($cost != "0") {
				$this->Item->reset_fields();
				$this->Item->set_fields($i);
				$this->Item->set_field('cost', $cost);
				$this->Item->set_field('purchase_cost', $pcost . $currency_code);
				if ($i["profit"] > 0) {
					$this->Item->set_field('price', $cost * (1 + ($i["profit"] / 100)));
				}
			}
			$this->Item->update();
		}
	}

	public function set_next_auto_number($transType)
	{
		$query = $this->db->select_max('auto_no', 'nextAutoNo')
			->where('trans_type', $transType)
			->where("fiscal_year_id", $this->violet_auth->get_fiscal_year_id())
			->get($this->_table);
		$no = ($query->num_rows() > 0 ? _gnv($query->row()->nextAutoNo) : 0);
		return 1 + $no;
	}

	public function fetch_transaction_id_by_autono($auto_no, $transType)
	{
		$query = [
			'select' => "transactions.id",
			'where' => [
				["transactions.auto_no", $auto_no],
				["{$this->_table}.trans_type", $transType],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load_all($query);
	}

    public function fetch_transaction_relation_id_by_id($id, $transType)
    {
        $query = [
            'select' => "transactions.relation_id",
            'where' => [
                ["transactions.id", $id],
                ["{$this->_table}.trans_type", $transType],
                ["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
            ]
        ];
        $result = $this->load_all($query);

        if (!empty($result)) {
            return $result[0]['relation_id']; // Return the relation ID from the first row
        } else {
            return null; // or return an appropriate value if no rows are found
        }
    }


    public function save_transaction_in_journals($trans, $trans_id, $total, $journal_type)
	{
		$this->load->model('Journal');
		$this->Journal->set_field('auto_no', $trans['auto_no']);
		$this->Journal->set_field('transaction_id', $trans_id);
		$this->Journal->set_field('account_id', $trans['account_id']);
		$this->Journal->set_field('account2_id', $trans['account2_id']);
		$this->Journal->set_field('journal_date', $trans['trans_date']);
		$this->Journal->set_field('value_date', $trans['value_date']);
		$this->Journal->set_field('currency_id', $trans['currency_id']);
		$this->Journal->set_field('currency_rate', $trans['currency_rate']);
		$this->Journal->set_field('description', $journal_type . " NO " . $trans['auto_no']);
		$this->Journal->set_field('amount', $total);
		$this->Journal->set_field('journal_type', $journal_type);
		$this->Journal->set_field('fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
		$this->Journal->set_field('user_id', $this->violet_auth->get_user_id());
		$saved = $this->Journal->insert();
		return $saved;
	}

	public function save_in_journal_accounts($journal_id, $account_id, $auto_no, $total, $mvt_Type, $name, $trans_type)
	{
		$this->load->model('Journal_account');
		$this->Journal_account->reset_fields();
		$this->Journal_account->set_field('journal_id', $journal_id);
		$this->Journal_account->set_field('account_id', $account_id);
		$this->Journal_account->set_field('description', $trans_type . " NO " . $auto_no . " - " . $name);
		$this->Journal_account->set_field('mvt_type', $mvt_Type);
		$this->Journal_account->set_field('amount', $total);
		$this->Journal_account->insert();
	}

	public function fetch_journal_id_by_transaction_id($transaction_id, $transType)
	{
		$query = [
			'select' => [
				["journals.id"]
			],
			'join' => [
				['journals', 'journals.transaction_id = transactions.id', 'inner'],
			],
			'where' => [
				["journals.transaction_id", $transaction_id],
				["{$this->_table}.trans_type", $transType],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],

		];
		return $this->load_all($query);
	}

	public function calculate_purchase_total($values, $total_discount)
	{
		$total = 0;
		foreach ($values as $k => $v) {
			$p = $c = $q = $d = 0;
			$p = $v["price"];
			$c = $v["cost"];
			$q = $v["qty"];
			$d = $v["discount"];
			$tot_item = ((floatval($p) * (1 - (floatval($d) / 100))) + (floatval($p) * ((floatval($c) / 100)))) * floatval($q);
			$total = floatval($total) + floatval($tot_item);
		}

		return floatval($total) - floatval($total_discount);
	}

	public function calculate_transaction_total($values, $total_discount, $tva)
	{
		$total = 0;
		foreach ($values as $v) {
			$p = $v["price"];
			$q = $v["qty"];
			$d = $v["discount"];
			$tot_item = floatval($p) * floatval($q) * (1 - (floatval($d) / 100));
			$total = floatval($total) + floatval($tot_item);
		}
		return (floatval($total) - floatval($total_discount)) * (1 + (floatval($tva) / 100));
	}

	public function update_qty($items, $mvt_Type)
	{
		$this->load->model('Item');
		foreach ($items as $item) {
			$item_qty = $this->Item->fetch_qty_by_item_id($item["item_id"]);
			// $item_open_qty = $this->Item->fetch_open_qty_by_item_id($item["item_id"]);
			$new_qty = floatval($item_qty["qty"]) + ($mvt_Type * floatval($item["qty"]));
			$this->Item->update_item_qty_by_item_id($item["item_id"], $new_qty);
		}
	}

	public function update_qty_on_edit($items, $mvt_Type)
	{
		$this->load->model('Transaction_item');
		$this->load->model('Item');
		$trans_items = $this->Transaction_item->load_all_trans_items($this->Transaction->get_field('id'));
		foreach ($trans_items as $old) {
			foreach ($items as $new) {
				if ($old["item_id"] === $new["item_id"]) {
					if ($old["qty"] !== $new["qty"]) {
						$item_qty = $this->Item->fetch_qty_by_item_id($new["item_id"]);
						$edited_qty = floatval($item_qty["qty"]) - ($mvt_Type * floatval($old["qty"])) + ($mvt_Type * floatval($new["qty"]));
						$this->Item->update_item_qty_by_item_id($new["item_id"], $edited_qty);
					}
				}
			}
		}
	}

	public function update_qty_on_delete($id, $mvt_Type)
	{
		$this->load->model('Transaction_item');
		$this->load->model('Item');
		$trans_items = $this->Transaction_item->load_all_trans_items($id);
		foreach ($trans_items as $t) {
			$item_qty = $this->Item->fetch_qty_by_item_id($t["item_id"]);
			$new_qty = floatval($item_qty["qty"]) - ($mvt_Type * floatval($t["qty"]));
			$this->Item->update_item_qty_by_item_id($t["item_id"], $new_qty);
		}
	}

	public function fetch_trans_date($id)
	{
		$query = [
			'select' => "transactions.trans_date",
			'where' => [
				["transactions.id", $id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load($query);
	}

	public function update_cost_on_delete($item_id, $net_cost)
	{
		$this->load->model('Item');
		$this->Item->set_fields($this->Item->fetch_item($item_id));
		$this->Item->set_field('cost', $net_cost);
		$this->Item->update();
	}

	public function fetch_last_purchase_or_transfer()
	{
		// $this->db->select('transactions.*');
		$this->db->select_max('transactions.trans_date');
		$this->db->select_max('transactions.id');
		$this->db->from('transactions');
		$this->db->where('transactions.trans_type', "PU");
		$this->db->or_where('transactions.trans_type', "TR");
		$this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
		$query = $this->db->get()->row_array();
		return $query;
	}


	public function fetch_array_cost_of_each_item_of_last_transaction($trans_items)
	{
		$array_cost_of_itemid = array();
		$last_pu = $this->fetch_last_purchase_or_transfer();
		foreach ($trans_items as $t => $val) {
			$this->db->select('transaction_items.net_cost , items.id as item_id, items.open_cost, items.profit, items.TVA, transaction_items.id, transactions.currency_rate, transactions.currency_id');
			$this->db->from('transaction_items');
			$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id');
			$this->db->join('items', 'items.id = transaction_items.item_id');
			$this->db->where('transactions.trans_date', $last_pu["trans_date"]);
			$this->db->where('transactions.id', $last_pu["id"]);
			$this->db->where('transactions.trans_type', "PU");
			$this->db->where('transaction_items.item_id', $val["item_id"]);
			$this->db->where('items.id', $val["item_id"]);
			$this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
			$query = $this->db->get()->result_array();
			if ($query == false)
				$arr1 = array($val["item_id"] => 0);
			else
				$arr1 = array($val["item_id"] => $query[0]);
			$array_cost_of_itemid += $arr1;
		}
		return $array_cost_of_itemid;
	}

	public function load_trans_data_by_trans_id($trans_id)
	{
		$query = [
			'select' => "transactions.*",
			'where' => [
				["transactions.id", $trans_id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load($query);
	}

	public function fetch_warehouse_id_by_trans_id($trans_id)
	{
		$query = [
			'select' => "transactions.warehouse_id",
			'where' => [
				["transactions.id", $trans_id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load($query);
	}

	public function save_opening_items_trans_items($items, $mvtType)
	{
		$this->load->model('Warehouse');
		$this->load->model('Transaction_item');
		$transaction_id = _gnv($this->get_field('id'));
		foreach ($items as $item) {
			$warehouse_id = $this->Warehouse->get_warehouse_id_by_warehouse_and_shelf($item["warehouses"], $item["shelfs"]);
			$this->Transaction_item->reset_fields();
			$this->Transaction_item->set_fields($item);
			$this->Transaction_item->set_field('transaction_id', $transaction_id);
			$this->Transaction_item->set_field('warehouse_id', $warehouse_id["id"]);
			$this->Transaction_item->set_field('mvt_type', $mvtType);
			$this->Transaction_item->set_field('cost', 0);
			$this->Transaction_item->set_field('price', 0);
			$this->Transaction_item->set_field('discount', 0);
			$this->Transaction_item->set_field('net_cost', 0);
			$this->Transaction_item->set_field('item_profit', 0);
			$test = $this->Transaction_item->insert();
		}
	}

	public function paginate_opening_items($transType)
	{
		$query = [
			'select' => [
				["transactions.auto_no, transactions.trans_date"],
				// ["MIN(i.description) AS description, MIN(i.barcode) AS barcode"],
				// ["SUM(ti.qty) AS total_qty"],
				["transactions.id"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items AS i', 'i.id = ti.item_id', 'inner']
			],
			'where' => [
				["{$this->_table}.trans_type", $transType],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];


		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_opening_items_data_tables($transType)
	{
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.trans_date', 'transactions.id',
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
					['items AS i', 'i.id = ti.item_id', 'inner']
				],
				'where' => [
					["{$this->_table}.trans_type", $transType],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function get_array_of_qty_of_each_item_in_trans($transItems)
	{
		$item_ids = [];
		foreach ($transItems as $t) {
			array_push($item_ids, $t["item_id"]);
		}
		$qty_cost_array = [];
		foreach (array_unique($item_ids) as $i) {
			$qty = $this->Item->fetch_open_qty_of_item($i)["open_qty"];
			foreach ($transItems as $t) {
				if ($i === $t["item_id"]) {
					$qty += $t["qty"];
				}
			}
			array_push($qty_cost_array, ["item_id" => $i, "qty" => $qty]);
		}
		return $qty_cost_array;
	}

	public function check_if_items_purchased($transItems)
	{
		$item_ids = [];
		foreach ($transItems as $t) {
			array_push($item_ids, $t["item_id"]);
		}
		$query = [];
		foreach (array_unique($item_ids) as $i) {
			$this->db->select('count(*) as count');
			$this->db->from('transactions');
			$this->db->join('transaction_items', 'transaction_items.transaction_id = transactions.id');
			$this->db->where('transaction_items.item_id', $i);
			$this->db->where('transactions.trans_type', "PU");
			$this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
			$result = $this->db->get()->row_array();
			array_push($query, ["item_id" => $i, "count" => $result["count"]]);
		}
		return $query;
	}

	public function save_transaction_items_without_warehouse_id($items, $mvtType)
	{
		$this->load->model('Transaction_item');
		// $this->load->model('Item');
		$transaction_id = _gnv($this->get_field('id'));
		foreach ($items as $item) {
			$this->Transaction_item->reset_fields();
			$this->Transaction_item->set_fields($item);
			$this->Transaction_item->set_field('transaction_id', $transaction_id);
			$this->Transaction_item->set_field('warehouse_id', 0);
			$this->Transaction_item->set_field('mvt_type', $mvtType);
			// $this->Transaction_item->set_field('cost', $this->Item->fetch_item_cost($item["item_id"])["0"]["cost"]);
			$this->Transaction_item->set_field('net_cost', "0");
			$this->Transaction_item->insert();
		}
	}

	public function fetch_MP_id()
	{
		$this->db->select('transactions.id');
		$this->db->from('transactions');
		$this->db->where('transactions.trans_type', "MP");
		$this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function load_missing_products_data_tables()
	{
	}

	public function update_and_save_transaction_items_for_MP($missing_products, $MP_trans_items, $mvtType, $transaction_id)
	{
		// var_dump($missing_products);
		// var_dump($MP_trans_items);
		// exit;
		$this->load->model('Transaction_item');
		$this->load->model('Item');
		foreach ($missing_products as $mp) {
			$count = 0;
			foreach ($MP_trans_items as $mti) {
				if ($mp["item_id"] === $mti["item_id"]) {
					if ($mti["checkbox"] === NULL) {
						$count++;
						$qty = $mp["qty"] + $mti["qty"];
						$data = array(
							'qty' => $qty
						);
						$this->db->where('id', $mti["id"]);
						$this->db->update('transaction_items', $data);
						break;
					}
				}
			}
			if ($count === 0) {
				$this->Transaction_item->reset_fields();
				$this->Transaction_item->set_fields($mp);
				$this->Transaction_item->set_field('transaction_id', $transaction_id);
				$this->Transaction_item->set_field('warehouse_id', 0);
				$this->Transaction_item->set_field('mvt_type', $mvtType);
				$this->Transaction_item->set_field('cost', $this->Item->fetch_item_cost($mp["item_id"])["0"]["cost"]);
				$this->Transaction_item->set_field('net_cost', "0");
				$this->Transaction_item->insert();
			}
		}
	}

	public function update_checkbox_in_transaction_items($id)
	{
		$data = array(
			'checkbox' => true
		);
		$this->db->where('id', $id);
		$this->db->update('transaction_items', $data);
	}

	public function save_transaction_items_for_product_transfer($warehouse_id, $item_id, $qty, $cost, $mvtType)
	{
		$this->load->model('Transaction_item');
		$transaction_id = _gnv($this->get_field('id'));
		$this->Transaction_item->reset_fields();
		$this->Transaction_item->set_field('item_id', $item_id);
		$this->Transaction_item->set_field('transaction_id', $transaction_id);
		$this->Transaction_item->set_field('qty', $qty);
		$this->Transaction_item->set_field('warehouse_id', $warehouse_id);
		$this->Transaction_item->set_field('mvt_type', $mvtType);
		$this->Transaction_item->set_field('cost', $cost);
		$this->Transaction_item->set_field('price', "0");
		$this->Transaction_item->set_field('discount', "0");
		$this->Transaction_item->set_field('net_cost', $cost);
		$this->Transaction_item->insert();
	}

	public function paginate_transfers()
	{
		$query = [
			'select' => [
				["transactions.auto_no, transactions.trans_date, transactions.id"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner']
			],
			'where' => [
				["{$this->_table}.trans_type", "TR"],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_transfers_data_tables()
	{
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.trans_date', 'transactions.id'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner']
				],
				'where' => [
					["{$this->_table}.trans_type", "TR"],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function get_array_of_qty_for_each_item_in_trans_on_edit_op($transItems)
	{
		$item_ids = [];
		foreach ($transItems as $t) {
			array_push($item_ids, $t["item_id"]);
		}
		$qty_array = [];
		foreach (array_unique($item_ids) as $i) {
			$qty = 0;
			foreach ($transItems as $t) {
				if ($i === $t["item_id"]) {
					$qty = $qty + $t["qty"];
				}
			}
			array_push($qty_array, ["item_id" => $i, "qty" => $qty]);
		}
		return $qty_array;
	}

	public function get_array_of_old_open_qty_each_item_in_trans_on_edit_op($transItems)
	{
		$item_ids = [];
		foreach ($transItems as $t) {
			array_push($item_ids, $t["item_id"]);
		}
		$old_qty_array = [];
		foreach (array_unique($item_ids) as $i) {
			$qty = $this->Item->fetch_open_qty_of_item($i)["open_qty"];
			array_push($old_qty_array, ["item_id" => $i, "qty" => $qty]);
		}
		return $old_qty_array;
	}

	public function fetch_OP_trans_id_for_item($id)
	{
		$this->db->select('transactions.id');
		$this->db->from('transactions');
		$this->db->join('transaction_items', 'transactions.id = transaction_items.transaction_id');
		$this->db->where('transactions.trans_type', "OI");
		$this->db->where('transaction_items.item_id', $id);
		$this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function fetch_trans_type_by_trans_id($trans_id)
	{
		$this->db->select('transactions.trans_type');
		$this->db->from('transactions');
		$this->db->where('transactions.id', $trans_id);
		$this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
		$result = $this->db->get()->row_array()["trans_type"];
		$trans = "";
		if ($result == "PU") {
			$trans = "purchases";
		}
		if ($result == "SA") {
			$trans = "sales";
		}
		if ($result == "RP") {
			$trans = "return_purchases";
		}
		if ($result == "RS") {
			$trans = "return_sales";
		}
		if ($result == "OS") {
			$trans = "orders";
		}
		if ($result == "OP") {
			$trans = "order_purchases";
		}
		if ($result == "QU") {
			$trans = "quotations";
		}
		if ($result == "OI") {
			$trans = "opening_items";
		}
		if ($result == "MP") {
			$trans = "missing_products";
		}
		if ($result == "TR") {
			$trans = "transfers";
		}
		if ($result == "AD") {
			$trans = "adjusts";
		}
		return $trans;
	}

	public function update_cost_and_price_in_tr($item_id, $cost)
	{
		$this->load->model('Item');
		$i = $this->Item->fetch_item($item_id)["0"];
		if ($i["profit"] > 0) {
			$data = array(
				'cost' => $cost,
				'price' => $cost * (1 + ($i["profit"] / 100)),
			);
		} else {
			$data = array(
				'cost' => $cost
			);
		}
		$this->db->where('id', $item_id);
		$this->db->update('items', $data);
	}

	public function update_status($trans_id)
	{
		$data = array(
			'status' => 1
		);
		$this->db->where('id', $trans_id);
		$this->db->update('transactions', $data);
	}

    public function update_to_invoice($trans_id)
    {
        $data = array(
            'to_invoice' => 1
        );
        $this->db->where('id', $trans_id);
        $this->db->update('transactions', $data);
    }

	public function fetch_last_trans_date_of_purchase_or_transfer_of_item($item_id)
	{
		$fiscal_year = $this->violet_auth->get_fiscal_year_id();
		$this->db->select('trans_date');
		$this->db->from('transaction_items');
		$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id', 'inner');
		$this->db->where(
			"transaction_items.item_id = $item_id
			and transaction_items.net_cost != 0 
			and (transactions.trans_type = 'PU' or transactions.trans_type = 'TR')		
			and transactions.fiscal_year_id = $fiscal_year"
		);
		$this->db->order_by('trans_date', 'DESC');
		$query = $this->db->get()->result_array();
		if ($query) {
			return $query[0];
		} else {
			return null;
		}
	}

	public function fetch_last_purchase_or_transfer_of_item_using_trans_date($item_id, $trans_date)
	{
		$fiscal_year = $this->violet_auth->get_fiscal_year_id();
		$this->db->select('MAX(transaction_items.id) as transaction_item_id');
		$this->db->from('transaction_items');
		$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id');
		$this->db->where(
			"transaction_items.item_id = $item_id
			and transaction_items.net_cost != 0 
			and transactions.trans_date = '$trans_date'		
			and transactions.fiscal_year_id = $fiscal_year
			and (transactions.trans_type = 'PU' or transactions.trans_type = 'TR')"
		);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function update_cost_and_price_of_item($item, $currency_rate, $currency_code)
	{
		$this->load->model('Item');
		$i = $this->Item->fetch_item($item["item_id"])["0"];
		$pcost = (doubleval($item["price"]) * (1 + (doubleval($item["cost"]) / 100))) * (1 - (doubleval($item["discount"]) / 100));
		$cost =  $pcost * $currency_rate;
		if ($cost != "0") {
			$this->Item->reset_fields();
			$this->Item->set_fields($i);
			if ($item["trans_type"] === "PU") {
				$this->Item->set_field('cost', $cost);
				$this->Item->set_field('purchase_cost', $pcost);
			} else {
				$this->Item->set_field('cost', $item["cost"] * $currency_rate);
			}
			// if ($i["profit"] > 0) {
			// 	$this->Item->set_field('price', $cost * (1 + ($i["profit"] / 100)));
			// }
		}
		$this->Item->update();
	}

	public function update_item_cost_and_price_after_del($val)
	{
		$this->load->model('Currency');
		if ($val['id'] === NULL) {
			$for_update = array(
				'cost' => $val['open_cost'],
				'purchase_cost' => 0
			);
		} else {
			$currency_code = $this->Currency->fetch_currency_code($val['currency_id'])["currency_code"];
			if ($val['profit'] > 0) {
				if ($val['trans_type'] === "PU") {
					$for_update = array(
						'cost' => $val['net_cost'] * $val['currency_rate'],
						'purchase_cost' => $val['net_cost'] . $currency_code,
						'price' => $val['net_cost'] * $val['currency_rate'] * (1 + ($val['profit'] / 100)),
					);
				} else {
					$for_update = array(
						'cost' => $val['net_cost'] * $val['currency_rate'],
						'price' => $val['net_cost'] * $val['currency_rate'] * (1 + ($val['profit'] / 100)),
					);
				}
			} else {
				if ($val['trans_type'] === "PU") {
					$for_update = array(
						'cost' => $val['net_cost'] * $val['currency_rate'],
						'purchase_cost' => $val['net_cost'] . $currency_code
					);
				} else {
					$for_update = array(
						'cost' => $val['net_cost'] * $val['currency_rate']
					);
				}
			}
		}
		$this->db->where('id', $val['item_id']);
		$this->db->update('items', $for_update);
	}

	public function paginate_driver_delivery_notes($driver_id)
	{
		$total = 'ROUND(SUM((ti.qty * ti.price * (1 - ti.discount/100)))- transactions.discount,2)';
		$query = [
			'select' => [
				["transactions.auto_no, transactions.relation_id, transactions.trans_date, transactions.value_date"],
				["account1.account_name AS account1, account2.account_name AS account2, account1.account_number AS account_number1"],
				["currencies.currency_code, transactions.currency_rate, transactions.discount, $total AS total"],
				["transactions.delivered"],
				["transactions.id"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
			],
			'where' => [
				["{$this->_table}.driver_id", $driver_id],
				["{$this->_table}.trans_type", "SA"],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_driver_delivery_notes_data_tables($driver_id)
	{
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.relation_id', 'transactions.trans_date', 'transactions.value_date',
				['account1.account_name', 'account1'], ['account1.account_number', 'account_number1'],
				['account2.account_name', 'account2'],
				'transactions.delivered', 'transactions.id'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				],
				'where' => [
					["{$this->_table}.driver_id", $driver_id],
					["{$this->_table}.trans_type", "SA"],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'transactions.currency_rate', 'transactions.discount', 'transactions.account_id', 'transactions.account2_id', 'account1.account_name', 'account2.account_name']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function update_delivered_field($trans_id)
	{
		$data = array(
			'delivered' => 1
		);
		$this->db->where('id', $trans_id);
		$this->db->update('transactions', $data);
	}

	public function fetch_trans_autono($id)
	{
		$query = [
			'select' => "transactions.auto_no",
			'where' => [
				["transactions.id", $id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load($query);
	}

	public function fetch_last_trans_date_of_purchase_of_item($item_id)
	{
		$fiscal_year = $this->violet_auth->get_fiscal_year_id();
		$this->db->select('trans_date');
		$this->db->from('transaction_items');
		$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id', 'inner');
		$this->db->where(
			"transaction_items.item_id = $item_id
			and transaction_items.net_cost != 0 
			and transactions.trans_type = 'PU'		
			and transactions.fiscal_year_id = $fiscal_year"
		);
		$this->db->order_by('trans_date', 'DESC');
		$query = $this->db->get()->result_array();
		if ($query) {
			return $query[0];
		} else {
			return null;
		}
	}

	public function paginate_employee_pickup_notes_notes($employee_id)
	{
		$total = 'ROUND(SUM((ti.qty * ti.price * (1 - ti.discount/100))) - transactions.discount,2)';
		$query = [
			'select' => [
				["transactions.auto_no, transactions.relation_id, transactions.trans_date, transactions.value_date"],
				["account1.account_name AS account1, account2.account_name AS account2, account1.account_number AS account_number1"],
				["currencies.currency_code, transactions.currency_rate, transactions.discount, $total AS total"],
				["transactions.pickup"],
				["transactions.id"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
			],
			'where' => [
				["{$this->_table}.employee_id", $employee_id],
				["{$this->_table}.trans_type", "OS"],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_employee_pickup_notes_data_tables($employee_id)
	{
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.relation_id', 'transactions.trans_date', 'transactions.value_date',
				['account1.account_name', 'account1'], ['account1.account_number', 'account_number1'],
				['account2.account_name', 'account2'],
				'transactions.pickup', 'transactions.id'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				],
				'where' => [
					["{$this->_table}.employee_id", $employee_id],
					["{$this->_table}.trans_type", "0S"],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'transactions.currency_rate', 'transactions.discount', 'transactions.account_id', 'transactions.account2_id', 'account1.account_name', 'account2.account_name']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function update_pickup_field($trans_id)
	{
		$data = array(
			'pickup' => 1
		);
		$this->db->where('id', $trans_id);
		$this->db->update('transactions', $data);
	}

	public function paginate_sales_data($transType)
	{
		$total = "CONCAT_WS(' ',ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount) * (1+ transactions.TVA/100)) + transactions.pfand ,2), currencies.currency_code)";
		$query = [
			'select' => [
				["transactions.auto_no as auto_no, t1.auto_no as os_no, t2.auto_no as qu_no, transactions.trans_date, transactions.value_date"],
				["account1.account_name AS account1, account2.account_name AS account2"],
				["currencies.currency_code, transactions.currency_rate, transactions.VIN, transactions.OE, transactions.model, transactions.description, transactions.discount, $total AS total"],
				["users.user_name, transactions.status, transactions.delivered, transactions.id"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['transactions AS t1', 'transactions.relation_id = t1.id', 'left'],
				['transactions AS t2', 't1.relation_id = t2.id', 'left'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items', 'ti.item_id = items.id', 'left'],
				['users', 'transactions.user_id = users.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", $transType],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']],
            //'limit' => '10000'
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}


	public function load_sales_data_tables($transType)
	{
		$total = "CONCAT_WS(' ',ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount) * (1+ transactions.TVA/100)) + transactions.pfand ,2), currencies.currency_code)";
		$dt = [
			'columns' => [
				['transactions.auto_no', 'auto_no'], ['t1.auto_no', 'os_no'], ['t2.auto_no', 'qu_no'], 'transactions.trans_date',
				['account1.account_name', 'account1'],
				'transactions.model', 'transactions.VIN', 'transactions.OE', 'transactions.description',
				'users.user_name', [$total, 'total'], 'transactions.status', 'transactions.delivered', 'transactions.id', 'transactions.value_date'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['transactions AS t1', 'transactions.relation_id = t1.id', 'left'],
					['transactions AS t2', 't1.relation_id = t2.id', 'left'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
					['items', 'ti.item_id = items.id', 'left'],
					['users', 'transactions.user_id = users.id', 'left']
				],
				'where' => [
					["{$this->_table}.trans_type", $transType],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'transactions.OE', 'transactions.model', 'transactions.VIN', 'transactions.description', 'account1.account_name', 'account2.account_name', 'items.EAN', 'items.artical_number', 'items.barcode', 'users.user_name']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function paginate_order_sales_data($transType)
	{
		$total = "CONCAT_WS(' ',ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount) * (1+ transactions.TVA/100)) + transactions.pfand ,2), currencies.currency_code)";
		$query = [
			'select' => [
				["transactions.auto_no as auto_no, t1.auto_no as qu_no, transactions.trans_date"],
				["account1.account_name AS account1"],
				["transactions.model, transactions.VIN, transactions.OE, transactions.description"],
				["users.user_name, $total AS total, transactions.status"],
				["transactions.pickup, transactions.id, transactions.transfered, transactions.to_invoice"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['transactions AS t1', 'transactions.relation_id = t1.id', 'left'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items', 'ti.item_id = items.id', 'left'],
				['users', 'transactions.user_id = users.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", $transType],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}


	public function load_order_sales_data_tables($transType)
	{
		$total = "CONCAT_WS(' ',ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount) * (1+ transactions.TVA/100)) + transactions.pfand ,2), currencies.currency_code)";
		$dt = [
			'columns' => [
				['transactions.auto_no', 'auto_no'], ['t1.auto_no', 'qu_no'], 'transactions.trans_date', ['account1.account_name', 'account1'],
				'transactions.model', 'transactions.VIN', 'transactions.OE', 'transactions.description',
				'users.user_name', [$total, 'total'], 'transactions.status', 'transactions.pickup', 'transactions.id', 'transactions.transfered',' transactions.to_invoice'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['transactions AS t1', 'transactions.relation_id = t1.id', 'left'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
					['items', 'ti.item_id = items.id', 'left'],
					['users', 'transactions.user_id = users.id', 'left']
				],
				'where' => [
					["{$this->_table}.trans_type", $transType],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'transactions.OE', 'transactions.model', 'transactions.VIN', 'transactions.description', 'account1.account_name', 'account2.account_name', 'items.EAN', 'items.artical_number', 'items.barcode', 'users.user_name']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function paginate_employees_sale_orders()
	{
		$query = [
			'select' => [
				["transactions.auto_no, users.user_name, transactions.value_date, transactions.pickup"]
			],
			'join' => [
				['users', 'users.id = transactions.employee_id', 'inner'],
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
			],
			'where' => [
				["{$this->_table}.trans_type", "OS"],
				["users.user_type", "Employee"],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_employees_sale_orders_data_tables()
	{
		$dt = [
			'columns' => [
				'transactions.auto_no', 'users.user_name',
				'transactions.value_date', 'transactions.pickup'
			],
			'query' => [
				'join' => [
					['users', 'users.id = transactions.employee_id', 'inner'],
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left']
				],
				'where' => [
					["{$this->_table}.trans_type", "OS"],
					["users.user_type", "Employee"],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no',]
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function paginate_specific_employee_sale_orders($employee_id)
	{
		$query = [
			'select' => [
				["transactions.auto_no, users.user_name, transactions.value_date, transactions.pickup"]
			],
			'join' => [
				['users', 'users.id = transactions.employee_id', 'inner'],
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
			],
			'where' => [
				["{$this->_table}.trans_type", "OS"],
				["users.user_type", "Employee"],
				["{$this->_table}.employee_id", $employee_id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_specific_employee_sale_orders_data_tables($employee_id)
	{
		$dt = [
			'columns' => [
				'transactions.auto_no', 'users.user_name',
				'transactions.value_date', 'transactions.pickup'
			],
			'query' => [
				'join' => [
					['users', 'users.id = transactions.employee_id', 'inner'],
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				],
				'where' => [
					["{$this->_table}.trans_type", "OS"],
					["users.user_type", "Employee"],
					["{$this->_table}.employee_id", $employee_id],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function  update_items_qty($trans_items)
	{
		foreach ($trans_items as $t) {
			$query = [
				'select' => [
					"SUM(transaction_items.qty * transaction_items.mvt_type) as total_qty,"
				],
				'join' => [
					['transaction_items', 'transactions.id = transaction_items.transaction_id', 'inner'],
					['items', 'items.id = transaction_items.item_id', 'inner']
				],
				'where' => [
					["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
					["transaction_items.item_id", $t["item_id"]],
				],
				'group_by' => ['items.id'],
				// 'having' => [
				// 	['SUM(transaction_items.qty * transaction_items.mvt_type) >', 0],
				// ],
			];
			$result = $this->load($query);
			if ($result["total_qty"] === NULL) {
				$result["total_qty"] = 0;
			}
			$data = [
				'qty' => $result["total_qty"]
			];
			$this->db->where('id', $t["item_id"]);
			$this->db->update('items', $data);
		}
	}

	public function  update_items_open_qty($trans_items)
	{
		foreach ($trans_items as $t) {
			$query = [
				'select' => [
					"SUM(transaction_items.qty * transaction_items.mvt_type) as total_qty,"
				],
				'join' => [
					['transaction_items', 'transactions.id = transaction_items.transaction_id', 'inner'],
					['items', 'items.id = transaction_items.item_id', 'inner']
				],
				'where' => [
					["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
					["transaction_items.item_id", $t["item_id"]],
					["transactions.trans_type", "OI"]
				],
				'group_by' => ['items.id'],
				'having' => [
					['SUM(transaction_items.qty * transaction_items.mvt_type) >', 0],
				],
			];
			$result = $this->load($query);
			if ($result["total_qty"] === NULL) {
				$result["total_qty"] = 0;
			}
			$data = [
				'open_qty' => $result["total_qty"]
			];
			$this->db->where('id', $t["item_id"]);
			$this->db->update('items', $data);
		}
	}

	public function paginate_product_openings($transType, $item_id)
	{
		$query = [
			'select' => [
				["transactions.auto_no, transactions.trans_date"],
				["SUM(ti.qty) AS total_qty"],
				["transactions.id"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", $transType],
				["ti.item_id", $item_id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_product_openings_data_tables($transType, $item_id)
	{
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.trans_date', ['SUM(ti.qty)', 'total_qty'], 'transactions.id',
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left']
				],
				'where' => [
					["{$this->_table}.trans_type", $transType],
					["ti.item_id", $item_id],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				// 'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function paginate_quotations_data()
	{
		$total = "CONCAT_WS(' ',ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount) * (1+ transactions.TVA/100)) + transactions.pfand ,2), currencies.currency_code)";
		$query = [
			'select' => [
				["transactions.auto_no, transactions.trans_date"],
				["account1.account_name AS account1"],
				["transactions.model, transactions.VIN, transactions.OE, transactions.description, users.user_name, $total AS total"],
				["transactions.status, transactions.id, transactions.transfered, transactions.to_invoice"],
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items', 'ti.item_id = items.id', 'left'],
				['users', 'transactions.user_id = users.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", "QU"],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '', 'inPage' => '50']);
	}

	public function load_quotations_data_tables($transType, $use_cost)
	{
		$total = "CONCAT_WS(' ',ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount) * (1+ transactions.TVA/100)) + transactions.pfand ,2), currencies.currency_code)";
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.trans_date', ['account1.account_name', 'account1'],
				'transactions.model', 'transactions.VIN', 'transactions.OE', 'transactions.description',
				'users.user_name', [$total, 'total'],  'transactions.status', 'transactions.id', 'transactions.transfered', 'transactions.to_invoice'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
					['items', 'ti.item_id = items.id', 'left'],
					['users', 'transactions.user_id = users.id', 'left']
				],
				'where' => [
					["{$this->_table}.trans_type", $transType],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'transactions.OE', 'transactions.model', 'transactions.VIN', 'transactions.description', 'account1.account_name', 'account2.account_name', 'items.EAN', 'items.artical_number', 'items.barcode', 'users.user_name']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function update_edit_user_id_and_locked($trans_id, $locked, $edit_user_id)
	{
		$data = array(
			'locked' => $locked,
			'edit_user_id' => $edit_user_id,
		);
		$this->db->where('id', $trans_id);
		$this->db->update('transactions', $data);
	}

	public function check_if_user_can_edit($trans_id)
	{
		$trans = $this->load_trans_data_by_trans_id($trans_id);
		if ($trans["locked"] !== "1") {
			$check = 1;
		} else {
			if ($trans["edit_user_id"] === $this->violet_auth->get_user_id()) {
				$check = 1;
			} else {
				$check = 0;
			}
		}
		$trans['check'] = $check;
		return $trans;
	}

	public function check_if_quotation_transfered_to_order($id)
	{
		$query = [
			'select' => "transactions.id",
			'where' => [
				["{$this->_table}.trans_type", "OS"],
				["{$this->_table}.relation_id", $id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load($query);
	}

	public function check_if_order_transfered_to_sale($id)
	{
		$query = [
			'select' => "transactions.id, transactions.auto_no",
			'where' => [
				["{$this->_table}.trans_type", "SA"],
				["{$this->_table}.relation_id", $id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load($query);
	}

	public function update_transfered($trans_id, $transfered)
	{
		$data = array(
			'transfered' => $transfered
		);
		$this->db->where('id', $trans_id);
		$this->db->update('transactions', $data);
	}

	public function load_all_locked_transactions_by_user($user)
	{
		$query = [
			'select' => [
				"id, edit_user_id, locked"
			],
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				["transactions.edit_user_id", $user],
			],
		];
		return $this->load_all($query);
	}

	public function add_new_adjust_trans($auto_no, $lc_currency, $transType)
	{
		$trans_data = array(
			'auto_no' => $auto_no,
			'account_id' => 1,
			'account2_id' => 1,
			'currency_id' => $lc_currency,
			'currency_rate' => 1,
			'trans_date' => date('Y-m-d'),
			'trans_type' => $transType,
			'fiscal_year_id' => $this->violet_auth->get_fiscal_year_id(),
			'user_id' => $this->violet_auth->get_user_id()
		);
		$query = $this->db->insert('transactions', $trans_data);
		return $query;
	}

	public function load_receiving_items_report_data($data, $from_date, $to_date)
	{
		$query = [
			'select' => "transactions.auto_no, tr1.auto_no as os_nb, tr2.auto_no as qu_nb, DATE_FORMAT(transactions.trans_date,'%d-%m-%Y') as trans_date, tr2.OE, tr2.VIN, tr2.model, transaction_items.qty, accounts.account_name, accounts.account_number, items.artical_number, items.EAN, items.description, transaction_items.id as trans_item_id, transaction_items.pickedup_qty",
			'join' => [
				['transaction_items', 'transactions.id = transaction_items.transaction_id', 'inner'],
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['accounts', 'accounts.id = transaction_items.account_id', 'inner'],
				['transactions as tr1', 'tr1.id = transaction_items.relation_id', 'inner'],
				['transactions as tr2', 'tr1.relation_id = tr2.id', 'inner'],
			],
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				["transactions.trans_type", "OP"],
			],
			'order_by' => [['transactions.trans_date', 'ASC']]
		];
		if ($data["item_id"] !== '') {
			$query['where'][] = ["items.id", $data["item_id"]];
		}
		if ($from_date !== '' && $to_date !== '') {
			$query["where"][] = ["transactions.trans_date >=", $from_date];
			$query["where"][] = ["transactions.trans_date <=", $to_date];
		}
		return $this->load_all($query);
	}

	public function paginate_employee_scan_box_data($transType)
	{
		$query = [
			'select' => [
				["transactions.auto_no, transactions.trans_date, account1.account_name AS account1"],
				["transactions.VIN, transactions.OE, transactions.model, transactions.description, transactions.id"],
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items', 'ti.item_id = items.id', 'left'],
			],
			'where' => [
				["{$this->_table}.trans_type", $transType],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_employee_scan_box_data_tables($transType)
	{
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.trans_date', ['account1.account_name', 'account1'],
				'transactions.model', 'transactions.VIN', 'transactions.OE', 'transactions.description', 'transactions.id'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
					['items', 'ti.item_id = items.id', 'left'],
				],
				'where' => [
					["{$this->_table}.trans_type", $transType],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'transactions.OE', 'transactions.model', 'transactions.VIN', 'transactions.description', 'account1.account_name', 'account2.account_name', 'items.EAN', 'items.artical_number', 'items.barcode', 'users.user_name']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function fetch_last_trans_date_of_sale_of_item($item_id)
	{
		$fiscal_year = $this->violet_auth->get_fiscal_year_id();
		$this->db->select('trans_date');
		$this->db->from('transaction_items');
		$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id', 'inner');
		$this->db->where(
			"transaction_items.item_id = $item_id
			and transactions.trans_type = 'SA'	
			and transactions.fiscal_year_id = $fiscal_year"
		);
		$this->db->order_by('trans_date', 'DESC');
		$query = $this->db->get()->result_array();
		if ($query) {
			return $query[0];
		} else {
			return null;
		}
	}

	public function fetch_last_sale_of_item_using_trans_date($item_id, $trans_date)
	{
		$fiscal_year = $this->violet_auth->get_fiscal_year_id();
		$this->db->select('MAX(transaction_items.id) as transaction_item_id');
		$this->db->from('transaction_items');
		$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id');
		$this->db->where(
			"transaction_items.item_id = $item_id
			and transactions.trans_date = '$trans_date'		
			and transactions.fiscal_year_id = $fiscal_year
			and transactions.trans_type = 'SA'"
		);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function get_transaction_created_on_field($trans_id)
	{
		$this->db->select('created_on');
		$this->db->from('transactions');
		$this->db->where('id', $trans_id);
		$query = $this->db->get()->row_array();
		return $query;
	}

	public function paginate_delivery_note()
	{
		$query = [
			'select' => [
				["transactions.auto_no, transactions.trans_date, transactions.value_date"],
				["account1.account_name AS account1, transactions.id"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items', 'ti.item_id = items.id', 'left'],
				['users', 'transactions.user_id = users.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", 'SA'],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']]
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}

	public function load_delivery_note_data_tables()
	{
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.trans_date', 'transactions.value_date',
				['account1.account_name', 'account1'], 'transactions.id'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
					['items', 'ti.item_id = items.id', 'left'],
					['users', 'transactions.user_id = users.id', 'left']
				],
				'where' => [
					["{$this->_table}.trans_type", 'SA'],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'account1.account_name']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function load_order_purchase_report_data($from_date, $to_date, $brand, $artical_nb, $supplier_id)
	{
		$query = [
			'select' => [
				["ti.qty, items.description, items.brand, items.artical_number, items.EAN, transactions.auto_no, transactions.trans_date"], ["account1.account_name AS account_name"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items', 'ti.item_id = items.id', 'left'],
				['users', 'transactions.user_id = users.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", 'OP'],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'order_by' => [['auto_no', 'DESC']]
		];
		if ($from_date !== '' && $to_date !== '') {
			$query["where"][] = ["transactions.trans_date >=", $from_date];
			$query["where"][] = ["transactions.trans_date <=", $to_date];
		}
		if ($brand !== '') {
			$query["where"][] = ["items.brand", $brand];
		}
		if ($artical_nb !== '') {
			$query["where"][] = ["items.artical_number", $artical_nb];
		}
		if ($supplier_id !== '') {
			$query["where"][] = ["account1.id", $supplier_id];
		}
		return $this->load_all($query);
	}

	public function load_customer_receiving_items_report_data($from_date, $to_date)
	{
		$query = [
			'select' => [
				["ti.transaction_id, ti.id as trans_item_id, ti.qty, items.description, items.brand, items.artical_number, items.EAN, transactions.auto_no, transactions.trans_date, account1.id AS account_id, warehouses.warehouse, warehouses.shelf"],
				["account1.account_name AS account_name, date_format(transactions.trans_date, '%d-%m-%Y') as date"]
			],
			'join' => [
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'inner'],
				['items', 'ti.item_id = items.id', 'inner'],
				['warehouses', 'warehouses.id = ti.warehouse_id', 'inner'],
			],
			'where' => [
				["{$this->_table}.trans_type", 'OS'],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'order_by' => [['account1.id', 'DESC'], ['transactions.auto_no', 'ASC']]
		];
		if ($from_date !== '' && $to_date !== '') {
			$query["where"][] = ["transactions.trans_date >=", $from_date];
			$query["where"][] = ["transactions.trans_date <=", $to_date];
		}
		return $this->load_all($query);
	}

	public function load_all_customers_for_customer_receiving_items_report_data($from_date, $to_date)
	{
		$query = [
			'select' => [
				["account1.id AS account_id"], ["account1.account_name AS account_name"]
			],
			'join' => [
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'inner'],
				['items', 'ti.item_id = items.id', 'inner'],
			],
			'where' => [
				["{$this->_table}.trans_type", 'OS'],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['account1.id']
		];
		if ($from_date !== '' && $to_date !== '') {
			$query["where"][] = ["transactions.trans_date >=", $from_date];
			$query["where"][] = ["transactions.trans_date <=", $to_date];
		}
		return $this->load_all($query);
	}

	public function check_if_OS_transfered_SA($id)
	{
		$query = [
			'select' => "transactions.id",
			'where' => [
				["{$this->_table}.trans_type", "SA"],
				["{$this->_table}.relation_id", $id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load($query);
	}

	public function paginate_receiving($transType, $use_cost)
	{
		if ($use_cost === true) {
			$total = 'ROUND(SUM(( ti.qty * ((ti.price * (1 - ti.discount/100))+ (ti.price * (ti.cost/100))))) - transactions.discount,2)';
		} else {
			$total = 'ROUND(SUM((ti.qty * ti.price * (1 - ti.discount/100))) - transactions.discount,2)';
		}
		$query = [
			'select' => [
				["transactions.auto_no, transactions.invoice_related_nb, transactions.trans_date, transactions.value_date"],
				["account1.account_name AS account1, account2.account_name AS account2"],
				["currencies.currency_code, transactions.currency_rate, count(ti.id) as count, $total AS total"],
				["users.user_name, transactions.status, transactions.id"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items', 'ti.item_id = items.id', 'left'],
				['users', 'transactions.user_id = users.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", $transType],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']],
            //'limit' => '10000'
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}


	public function load_receiving_data_tables($transType, $use_cost)
	{
		if ($use_cost === true) {
			$total = 'ROUND(SUM(( ti.qty * ((ti.price * (1 - ti.discount/100))+ (ti.price * (ti.cost/100))))) - transactions.discount,2)';
		} else {
			$total = 'ROUND(SUM((ti.qty * ti.price * (1 - ti.discount/100))) - transactions.discount,2)';
		}
		$dt = [
			'columns' => [
				'transactions.auto_no', 'transactions.invoice_related_nb', 'transactions.trans_date', 'transactions.value_date',
				['account1.account_name', 'account1'], ['account2.account_name', 'account2'],
				'currencies.currency_code', 'transactions.currency_rate',
				['count(ti.id)', 'count'], [$total, 'total'], 'users.user_name', 'transactions.status', 'transactions.id'
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
					['items', 'ti.item_id = items.id', 'left'],
					['users', 'transactions.user_id = users.id', 'left']
				],
				'where' => [
					["{$this->_table}.trans_type", $transType],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'transactions.currency_rate', 'transactions.discount', 'transactions.account_id', 'transactions.account2_id', 'account1.account_name', 'account2.account_name', 'items.EAN', 'items.artical_number', 'items.barcode']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function load_all_pickup_report_data($auto_no, $type, $account_id)
	{
		$query = [
			'select' => "transactions.auto_no, transaction_items.*, items.description, items.EAN, items.artical_number, items.brand, accounts.account_name, accounts.account_number, warehouses.warehouse, warehouses.shelf",
			'join' => [
				['transaction_items', 'transaction_items.transaction_id = transactions.id', 'inner'],
				['accounts', 'transactions.account_id = accounts.id', 'inner'],
				['warehouses', 'transaction_items.warehouse_id = warehouses.id', 'inner'],
				['items', 'transaction_items.item_id = items.id', 'inner'],
			],
			'where' => [
				["{$this->_table}.trans_type", $type],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'order_by' => ['transactions.auto_no', 'DESC']
		];
		if ($auto_no) {
			$query['where'][] =	["transactions.auto_no", $auto_no];
		}
		if ($account_id) {
			$query['where'][] =	["transactions.account_id", $account_id];
		}
		return $this->load_all($query);
	}

	public function load_all_pfand_report_data($acc_id, $auto_no, $type)
	{
		$query = [
			'select' => "transactions.*, accounts.account_name, accounts.account_number, currencies.currency_code",
			'join' => [
				['accounts', 'transactions.account_id = accounts.id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
			],
			'where' => [
				["{$this->_table}.pfand > 0"],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		if ($type !== '') {
			if ($type === "All") {
				$query["where_in"][] = ["transactions.trans_type ", ['QU', 'SA', 'OS']];
			} else {
				$query["where"][] = ["transactions.trans_type ", $type];
			}
		}
		if ($acc_id !== '') {
			$query["where"][] = ["transactions.account_id ", $acc_id];
		}
		if ($auto_no !== '') {
			$query["where"][] = ["transactions.auto_no ", $auto_no];
		}
		return $this->load_all($query);
	}

	public function paginate_op($transType, $use_cost)
	{
		if ($use_cost === true) {
			$total = 'ROUND(SUM(( ti.qty * ((ti.price * (1 - ti.discount/100))+ (ti.price * (ti.cost/100))))) - transactions.discount,2)';
		} else {
			$total = 'ROUND(SUM((ti.qty * ti.price * (1 - ti.discount/100))) - transactions.discount,2)';
		}
		$query = [
			'select' => [
				["transactions.auto_no as auto_no, transactions.invoice_related_nb, transactions.trans_date, transactions.value_date"],
				["account1.account_name AS account1, account2.account_name AS account2"],
				["currencies.currency_code, transactions.currency_rate, count(ti.id) as count, $total AS total"],
				["users.user_name, transactions.status, transactions.id, transactions.relation_id as pu_no"]
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				// ['transactions as t1', 't1.relation_id = transactions.id', 'left'],
				['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
				['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
				['items', 'ti.item_id = items.id', 'left'],
				['users', 'transactions.user_id = users.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", $transType],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']],
            //'limit' => '10000'
		];
		return parent::paginate($query, ['urlPrefix' => '']);
	}


	public function load_op_data_tables($transType, $use_cost)
	{
		if ($use_cost === true) {
			$total = 'ROUND(SUM(( ti.qty * ((ti.price * (1 - ti.discount/100))+ (ti.price * (ti.cost/100))))) - transactions.discount,2)';
		} else {
			$total = 'ROUND(SUM((ti.qty * ti.price * (1 - ti.discount/100))) - transactions.discount,2)';
		}
		$dt = [
			'columns' => [
				['transactions.auto_no', 'auto_no'], 'transactions.invoice_related_nb', 'transactions.trans_date', 'transactions.value_date',
				['account1.account_name', 'account1'], ['account2.account_name', 'account2'],
				'currencies.currency_code', 'transactions.currency_rate',
				['count(ti.id)', 'count'], [$total, 'total'], 'users.user_name', 'transactions.status', 'transactions.id', ['transactions.relation_id', 'pu_no']
			],
			'query' => [
				'join' => [
					['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
					// ['transactions as t1', 't1.relation_id = transactions.id', 'left'],
					['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
					['accounts AS account2', 'account2.id = transactions.account2_id', 'inner'],
					['currencies', 'currencies.id = transactions.currency_id', 'inner'],
					['transaction_items AS ti', 'ti.transaction_id = transactions.id', 'left'],
					['items', 'ti.item_id = items.id', 'left'],
					['users', 'transactions.user_id = users.id', 'left']
				],
				'where' => [
					["{$this->_table}.trans_type", $transType],
					["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				],
				'group_by' => ['transactions.id'],
				'order_by' => [['auto_no', 'DESC']]
			],
			'search_in' => ['transactions.auto_no', 'transactions.currency_rate', 'transactions.discount', 'transactions.account_id', 'transactions.account2_id', 'account1.account_name', 'account2.account_name', 'items.EAN', 'items.artical_number', 'items.barcode']
		];
		return parent::load_datatables_pagedata($dt);
	}

	public function load_all_return_purchases_items_of_receiving($trans_id)
	{
		$query = [
			'select' => "transactions.auto_no, transactions.trans_date, transaction_items.qty, items.description ,items.category, items.barcode, items.brand, items.EAN, items.artical_number, warehouses.warehouse, warehouses.shelf",
			'join' => [
				['transaction_items', 'transactions.id = transaction_items.transaction_id', 'inner'],
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['warehouses', 'warehouses.id = transaction_items.warehouse_id', 'inner'],
			],
			'where' => [
				["transactions.relation_id", $trans_id],
				["transactions.trans_type", 'RP'],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			],
			'order_by' => [['transaction_items.id', 'ASC']]
		];
		return $this->load_all($query);
	}

	public function load_all_return_sale_items_of_invoice($trans_id)
	{
		$query = [
			'select' => "transactions.auto_no, transactions.trans_date, transaction_items.qty, transaction_items.price, transaction_items.discount, items.description ,items.category, items.barcode, items.brand, items.EAN, items.artical_number, warehouses.warehouse, warehouses.shelf",
			'join' => [
				['transaction_items', 'transactions.id = transaction_items.transaction_id', 'inner'],
				['items', 'items.id = transaction_items.item_id', 'inner'],
				['warehouses', 'warehouses.id = transaction_items.warehouse_id', 'inner'],
			],
			'where' => [
				["transactions.relation_id", $trans_id],
				["transactions.trans_type", 'RS'],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			],
			'order_by' => [['transaction_items.id', 'ASC']]
		];
		return $this->load_all($query);
	}

	public function load_transaction_by_date_and_type($trans_type, $date, $account_id)
	{
		$query = [
			'select' => "transactions.*",
			'where' => [
				["transactions.trans_date", $date],
				["transactions.trans_type", $trans_type],
				["transactions.account_id", $account_id],
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			]
		];
		return $this->load($query);
	}

	public function load_all_order_purchases_not_transfered_to_PU(){
		$query = [
			'select' => [
				["transactions.auto_no as auto_no, transactions.id, transactions.relation_id, accounts.account_name"],
			],
			'join' => [
				['fiscal_years', 'fiscal_years.id = transactions.fiscal_year_id', 'inner'],
				['accounts', 'accounts.id = transactions.account_id', 'inner'],
				// ['transactions as t1', 't1.relation_id = transactions.id', 'left']
			],
			'where' => [
				["{$this->_table}.trans_type", "OP"],
				['relation_id is NULL'],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],
			'group_by' => ['transactions.id'],
			'order_by' => [['auto_no', 'DESC']],		
		];
		return $this->load_all($query);
	}

	public function update_relation_id($trans_id, $relation_id)
	{
		$data = array(
			'relation_id' => $relation_id
		);
		$this->db->where('id', $trans_id);
		$this->db->update('transactions', $data);
	}

	public function load_all_ops_of_purchase($id){
		$query = [
			'select' => [
				["transactions.id"],
			],
			'where' => [
				["{$this->_table}.trans_type", "OP"],
				["{$this->_table}.relation_id", $id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],		
		];
		return $this->load_all($query);
	}

	public function fetch_related_order_purchase($order_sale_id){
		$query = [
			'select' => [
				["transactions.id"],
			],
			'where' => [
				["{$this->_table}.trans_type", "OP"],
				["{$this->_table}.order_id", $order_sale_id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			],		
		];
		return $this->load_all($query);
	}

	public function load_pickup_items_report_data($data){
		$query = [
			'select' => "transactions.auto_no, transactions.trans_type, DATE_FORMAT(transactions.trans_date,'%d-%m-%Y') as trans_date,
			currencies.currency_code, accounts.account_name, accounts.account_number,  transactions.account_id",
			'join' => [
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['accounts', 'accounts.id = transactions.account_id', 'inner'],
			],
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
			],
			'where_in' => ["transactions.trans_type", ["OS"]],
			'group_by' => [['accounts.id']],
			'order_by' => [['accounts.id', 'ASC']]
		];
		if ($data["trans_date"] !== '' && $data["to_date"] !== '') {
			$query["where"][] = ["transactions.trans_date >=", date("Y-m-d", strtotime($data["trans_date"]))];
			$query["where"][] = ["transactions.trans_date <=", date("Y-m-d", strtotime($data["to_date"]))];
		}
		// if ($data["customer_id"] !== '') {
		// 	$query['where'] = ["transactions.account_id", $data["customer_id"]];
		// }	
		// if ($data["order_nb"] !== '') {
		// 	$query['where'] = ["transactions.auto_no", $data["order_nb"]];
		// }
		return $this->load_all($query);
	}

	public function load_all_customer_sale_orders($acc_id, $from, $to){
		$query = [
			'select' => "transactions.auto_no, transactions.id as trans_id, transactions.trans_type, DATE_FORMAT(transactions.trans_date,'%d-%m-%Y') as trans_date,
			currencies.currency_code, accounts.account_name, accounts.account_number, transactions.account_id, tr1.id as sa_id, tr1.delivered as sa_delivered",
			'join' => [
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['accounts', 'accounts.id = transactions.account_id', 'inner'],
				['transactions as tr1', 'tr1.relation_id = transactions.id', 'left']
			],
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				["transactions.account_id", $acc_id]
			],
			'where_in' => ["transactions.trans_type", ["OS"]],
			'order_by' => [['transactions.auto_no', 'DESC']]
		];
		if ($from !== '' && $to !== '') {
			$query["where"][] = ["transactions.trans_date >=", date("Y-m-d", strtotime($from))];
			$query["where"][] = ["transactions.trans_date <=", date("Y-m-d", strtotime($to))];
		}
		return $this->load_all($query);
	}

	public function load_sale_of_oreder_sale_by_relation_id($os_id)
	{
		$query = [
			'select' => "transactions.*",
			'where' => [
				["{$this->_table}.trans_type", "SA"],
				["{$this->_table}.relation_id", $os_id],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load($query);
	}

	public function load_trans_data_by_auto_no_and_trans_type($auto_no, $trans_type)
	{
		$query = [
			'select' => "transactions.*, accounts.account_name, accounts.account_number",
			'join' => [
				['accounts', 'accounts.id = transactions.account_id', 'inner']
			],
			'where' => [
				["transactions.auto_no", $auto_no],
				["transactions.trans_type", $trans_type],
				["{$this->_table}.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
			]
		];
		return $this->load($query);
	}

	public function load_pickup_items_report_data_with_one_item($data){
		$query = [
			'select' => "transactions.auto_no, transactions.trans_type, DATE_FORMAT(transactions.trans_date,'%d-%m-%Y') as trans_date,
			currencies.currency_code, accounts.account_name, accounts.account_number,  transactions.account_id",
			'join' => [
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['accounts', 'accounts.id = transactions.account_id', 'inner'],
				['transaction_items', 'transaction_items.transaction_id = transactions.id', 'inner'],
			],
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				["transaction_items.item_id", $data['item_id']],
			],
			'where_in' => ["transactions.trans_type", ["OS"]],
			'group_by' => [['accounts.id']],
			'order_by' => [['transactions.trans_date', 'ASC']]
		];
		if ($data["trans_date"] !== '' && $data["to_date"] !== '') {
			$query["where"][] = ["transactions.trans_date >=", date("Y-m-d", strtotime($data["trans_date"]))];
			$query["where"][] = ["transactions.trans_date <=", date("Y-m-d", strtotime($data["to_date"]))];
		}
		return $this->load_all($query);
	}

	public function load_all_customer_sale_orders_with_specific_item($acc_id, $from, $to, $item_id){
		$query = [
			'select' => "transactions.auto_no, transactions.id as trans_id, transactions.trans_type, DATE_FORMAT(transactions.trans_date,'%d-%m-%Y') as trans_date,
			currencies.currency_code, accounts.account_name, accounts.account_number, transactions.account_id, tr1.id as sa_id, tr1.delivered as sa_delivered",
			'join' => [
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['accounts', 'accounts.id = transactions.account_id', 'inner'],
				['transactions as tr1', 'tr1.relation_id = transactions.id', 'left'],
				['transaction_items', 'transaction_items.transaction_id = transactions.id', 'inner'],
			],
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				["transactions.account_id", $acc_id],
				["transaction_items.item_id", $item_id],
			],
			'where_in' => ["transactions.trans_type", ["OS"]],
			'order_by' => [['transactions.auto_no', 'DESC']]
		];
		if ($from !== '' && $to !== '') {
			$query["where"][] = ["transactions.trans_date >=", date("Y-m-d", strtotime($from))];
			$query["where"][] = ["transactions.trans_date <=", date("Y-m-d", strtotime($to))];
		}
		return $this->load_all($query);
	}

	public function load_all_monthly_customer_unpaid_invoices($customer_id, $first_date, $last_date){
		$total = "ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount) * (1+ transactions.TVA/100)) + transactions.pfand ,2)";
		$total_no_tva = "ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount)) + transactions.pfand ,2)";
		$subtot = "ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0))) ,2)";
		$query = [
			'select' => "transactions.auto_no, DATE_FORMAT(transactions.trans_date,'%d-%m-%Y') as trans_date, transactions.VIN, transactions.model,
			currencies.currency_code, $total as total, $total_no_tva as total_no_tva, $subtot as subtot, transactions.id as trans_id, transactions.delivery_charge, transactions.pfand, transactions.TVA, transactions.status",
			'join' => [
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['accounts', 'accounts.id = transactions.account_id', 'inner'],
				['transaction_items as ti', 'ti.transaction_id = transactions.id', 'inner'],
			],
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				["transactions.account_id", $customer_id],
				["transactions.trans_type", 'SA'],
				["(transactions.status IS NULL OR transactions.status != 1)"],
			],
			'group_by' => [['transactions.id']],
			'order_by' => [['transactions.auto_no', 'ASC']],
		];
		if ($first_date !== '' && $last_date !== '') {
			$query["where"][] = ["transactions.trans_date >=", $first_date];
			$query["where"][] = ["transactions.trans_date <=", $last_date];
		}
		return $this->load_all($query);
	}
	
	public function load_all_customer_unpaid_invoices_before_specific_date($customer_id, $date){
		$total = "ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount) * (1+ transactions.TVA/100)) + transactions.pfand ,2)";
		$total_no_tva = "ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0) + transactions.delivery_charge - transactions.discount)) + transactions.pfand ,2)";
		$subtot = "ROUND(((COALESCE(SUM((ti.qty * ti.price * (1 - ti.discount/100))),0))) ,2)";
		$query = [
			'select' => "transactions.id, transactions.VIN, transactions.model, transactions.auto_no, DATE_FORMAT(transactions.trans_date,'%d-%m-%Y') as trans_date, count(ti.id) as count, currencies.currency_code, $total as total, $total_no_tva as total_no_tva, $subtot as subtot, transactions.TVA, transactions.pfand, transactions.delivery_charge",
			'join' => [
				['currencies', 'currencies.id = transactions.currency_id', 'inner'],
				['accounts', 'accounts.id = transactions.account_id', 'inner'],
				['transaction_items as ti', 'ti.transaction_id = transactions.id', 'inner'],
			],
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				["transactions.account_id", $customer_id],
				["transactions.trans_type", 'SA'],
				["transactions.trans_date <", $date],
				["(transactions.status IS NULL OR transactions.status != 1)"],
			],
			'group_by' => [['transactions.id']],
			// 'order_by' => [['transactions.auto_no', 'ASC']],
		];
		return $this->load_all($query);
	}

	public function load_all_purchases_linked_to_op($from, $to){
		$query = [
			'select' => "transactions.id as pu_id,  transactions.relation_id as op_id, transactions.auto_no as pu_nb",
			'where' => [
				["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()],
				["transactions.trans_type", 'PU'],
				["transactions.trans_date >=", date("Y-m-d", strtotime($from))],
				["transactions.trans_date <=", date("Y-m-d", strtotime($to))]
			],
			'order_by' => [['transactions.id', 'DESC']]
		];
		return $this->load_all($query);
	}

	public function search_tracking_nbs_suggestions($q = false)
	{
		(false === $q) and ($q = $this->input->post('term', true));
		$query = $this->db->select("id, auto_no, trans_type")
			->select("CONCAT_WS(' #', auto_no) AS description")
			->from($this->_table)
			->where(
				"(trans_type = 'SA') AND
				(CONCAT('#', auto_no)  LIKE '%$q%'
				or auto_no LIKE '%$q%')"
			)
			->get();
		if (false !== $query && $query->num_rows() > 0) {
			return $query->result_array();
		}
		return [];
	}
}
