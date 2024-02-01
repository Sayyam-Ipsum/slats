<?php

defined('BASEPATH') or die('No direct script access allowed');

class Account extends MY_Model
{

    protected $modelName = 'Account';
    protected $_table = 'accounts';
    protected $_listFieldName = 'account_number';
    protected $_fieldsNames = ['id', 'currency_id', 'account_number', 'account_name', 'account_type', 'opening_date', 'phone', 'email', 'address', 'debit', 'credit', 'balance', 'open_balance', 'type', 'payment_method'];
    protected $_dateFields = ['opening_date'];
    protected $allowedNulls = ['currency_id', 'account_type', 'phone', 'email', 'address', 'debit', 'credit', 'balance', 'open_balance', 'payment_method'];

    public function __construct()
    {
        parent::__construct();
        $this->validate = [
            'currency_id' => [
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'rule' => 'numeric',
                'message' => sprintf($this->lang->line('is_numeric_rule'), $this->lang->line('currency'))
            ],
            'account_number' => [
                'required' => TRUE,
                'allowEmpty' => FALSE,
                'rule' => 'numeric',
                'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('account_number'))
            ],
            'account_name' => [
                'required' => TRUE,
                'allowEmpty' => FALSE,
                'rule' => ['maxLength', 255],
                'message' => sprintf($this->lang->line('required__max_length_rule'), $this->lang->line('account_name'), 255)
            ],
            'account_type' => [
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'rule' => ['maxLength', 255],
                'message' => sprintf($this->lang->line('max_length_rule'), $this->lang->line('account_type'), 255)
            ],
            'opening_date' => [
                'required' => TRUE,
                'allowEmpty' => FALSE,
                'rule' => ['date', 'dmy'],
                'message' => sprintf($this->lang->line('required_date_rule'), $this->lang->line('opening_date'))
            ],
            'phone' => [
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'rule' => ['maxLength', 255],
                'message' => sprintf($this->lang->line('max_length_rule'), $this->lang->line('phone'), 255)
            ],
            'email' => [
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'rule' => ['maxLength', 255],
                'message' => sprintf($this->lang->line('max_length_rule'), $this->lang->line('email'), 255)
            ],
            'address' => [
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'rule' => ['maxLength', 255],
                'message' => sprintf($this->lang->line('max_length_rule'), $this->lang->line('address'), 255)
            ],
            'debit' => [
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'rule' => 'numeric',
                'message' => sprintf($this->lang->line('is_numeric_rule'), $this->lang->line('debit'))
            ],
            'credit' => [
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'rule' => 'numeric',
                'message' => sprintf($this->lang->line('is_numeric_rule'), $this->lang->line('credit'))
            ],
            'balance' => [
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'rule' => 'numeric',
                'message' => sprintf($this->lang->line('is_numeric_rule'), $this->lang->line('balance'))
            ],
            'open_balance' => [
                'required' => FALSE,
                'allowEmpty' => TRUE,
                'rule' => 'numeric',
                'message' => sprintf($this->lang->line('is_numeric_rule'), $this->lang->line('open_balance'))
            ]
        ];
    }

    public function paginate_accounts()
    {
        $query = [
            'select' => "accounts.*, currencies.currency_code AS currency",
            'join' => [['currencies', 'currencies.id = accounts.currency_id', 'left']],
            'where' => [['accounts.id != 1']],
            'order_by' => [['account_number', 'ASC']]
        ];
        //return parent::paginate($query, ['urlPrefix' => '']);
        return $this->load_all($query);
    }

    public function load_accounts_data_tables()
    {
        $dt = [
            'columns' => [
                'account_number', 'account_name', 'account_type',
                ['currencies.currency_code', 'currency'],
                'phone', 'debit', 'credit', 'balance', 'accounts.id'
            ], 'query' => [
                'join' => [
                    ['currencies', 'currencies.id = accounts.currency_id', 'left']
                ],
                'where' => [['accounts.id != 1']]
            ],
            'search_in' => ['account_number', 'account_name', 'account_type', 'phone', 'currencies.currency_code']
        ];
        // $this->remove_indexes_if_search_not_date($dt['search_in'], [0]);
        return parent::load_datatables_pagedata($dt);
    }

    public function search_suggestions($q = false)
    {
        (false === $q) and ($q = $this->input->post('term', true));
        $query = $this->db->select("id, account_number, account_name")
            ->select("CONCAT_WS(' - ', account_number, account_name) AS description")
            ->from($this->_table)
            ->where(
                "accounts.id != 1
				and (account_number LIKE '%$q%' 
				or CONCAT(account_number, ' - ', account_name)  LIKE '%$q%'
				or account_name LIKE '%$q%')"
            )
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function fetch_debit_and_balance($account_id)
    {
        $query = [
            'select' => "accounts.debit, accounts.balance",
            'where' => [["accounts.id", $account_id]]
        ];
        return $this->load_all($query);
    }

    public function fetch_credit_and_balance($account_id)
    {
        $query = [
            'select' => "accounts.credit, accounts.balance",
            'where' => [["accounts.id", $account_id]]
        ];
        return $this->load_all($query);
    }


    public function update_account_debit_and_balance($new_debit, $account_debit, $account_id)
    {
        $new_account_debit = floatval($account_debit['0']['debit']) + floatval($new_debit);
        $balance = floatval($account_debit['0']['balance']) + floatval($new_debit);
        $data = array(
            'debit' => $new_account_debit,
            'balance' => $balance
        );
        $this->db->where('id', $account_id);
        return $this->db->update('accounts', $data);
    }

    public function update_account_credit_and_balance($new_credit, $account_credit, $account_id)
    {
        $new_account_credit = floatval($account_credit['0']['credit']) + floatval($new_credit);
        $balance = floatval($account_credit['0']['balance']) - floatval($new_credit);
        $data = array(
            'credit' => $new_account_credit,
            'balance' => $balance
        );
        $this->db->where('id', $account_id);
        return $this->db->update('accounts', $data);
    }

    public function fetch_account_name_by_id($id)
    {
        $query = [
            'select' => "accounts.account_name",
            'where' => [
                ["accounts.id", $id]
            ]
        ];
        return $this->load_all($query);;
    }

    public function generate_autonumber($transType = 'Customer')
    {
        // var_dump($transType);
        // exit;
        $query = $this->db->select_max('account_number', 'nextAutoNo')
            ->where('account_type', $transType)
            ->get($this->_table);
        if ($query->row()->nextAutoNo == null) {
            switch ($transType) {
                case "Customer":
                    $no = 41100000;
                    return $no;
                    break;
                case "Supplier":
                    $no = 40100000;
                    return $no;
                    break;
                case "Purchases":
                    $no = 60100000;
                    return $no;
                    break;
                case "Sales":
                    $no = 70100000;
                    return $no;
                    break;
                case "Cash":
                    $no = 53000000;
                    return $no;
                    break;
                case "Expenses":

                    $no = 66100000;
                    return $no;
                    break;
                case "Bank":
                    $no = 51200000;
                    return $no;
                    break;
                case "Sale VAT":
                    $no = 44270000;
                    return $no;
                    break;
                case "Purchase VAT":
                    $no = 44260000;
                    return $no;
                    break;
                default:
                    return 0;
                    break;
            }
        } else {
            $no = $query->row()->nextAutoNo;
            return 1 + $no;
        }
        exit;
    }

    public function fetch_all_account_details($id)
    {
        $this->db->select('*');
        $this->db->from('accounts');
        $this->db->join('journal_accounts', 'journal_accounts.account_id = accounts.id');
        //$this->db->join('transactions', 'transactions.id = transaction_items.transaction_id');
        $this->db->where('accounts.id', $id);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function edit_account_debit_and_balance($account, $old_total, $acc_id)
    {
        $new_account_credit = floatval($account['0']['debit']) - floatval($old_total);
        $balance = floatval($account['0']['balance']) - floatval($old_total);
        $data = array(
            'debit' => $new_account_credit,
            'balance' => $balance
        );
        $this->db->where('id', $acc_id);
        return $this->db->update('accounts', $data);
    }

    public function edit_account_credit_and_balance($account, $old_total, $acc_id)
    {
        $new_account_credit = floatval($account['0']['credit']) - floatval($old_total);
        $balance = floatval($account['0']['balance']) + floatval($old_total);
        $data = array(
            'credit' => $new_account_credit,
            'balance' => $balance
        );
        $this->db->where('id', $acc_id);
        return $this->db->update('accounts', $data);
    }

    public function fetch_account_currency_id($acc_id)
    {
        $this->db->select('currency_id');
        $this->db->from('accounts');
        $this->db->where('accounts.id', $acc_id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function fetch_account_info($id)
    {
        $this->db->select('account_name, phone, email, address, account_number');
        $this->db->from('accounts');
        $this->db->where('accounts.id', $id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function search_sales_suggestions($currency_id, $q = false)
    {
        (false === $q) and ($q = $this->input->post('term', true));
        $query = $this->db->select("id, account_number, account_name")
            ->select("CONCAT_WS(' - ', account_number, account_name) AS description")
            ->from($this->_table)
            ->where(
                "accounts.id != 1
				and accounts.currency_id = $currency_id
				and (account_number LIKE '701%$q%' 
				or CONCAT(account_number, ' - ', account_name)  LIKE '701%$q%'
				or account_name LIKE '701%$q%')"
            )
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function search_purchases_suggestions($currency_id, $q = false)
    {
        (false === $q) and ($q = $this->input->post('term', true));
        $query = $this->db->select("id, account_number, account_name")
            ->select("CONCAT_WS(' - ', account_number, account_name) AS description")
            ->from($this->_table)
            ->where(
                "accounts.id != 1
				and accounts.currency_id = $currency_id
				and (account_number LIKE '601%$q%' 
				or CONCAT(account_number, ' - ', account_name)  LIKE '601%$q%'
				or account_name LIKE '601%$q%')"
            )
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function search_suggestions_same_currency($currency_id, $q = false)
    {
        (false === $q) and ($q = $this->input->post('term', true));
        $query = $this->db->select("id, account_number, account_name")
            ->select("CONCAT_WS(' - ', account_number, account_name) AS description")
            ->from($this->_table)
            ->where(
                "accounts.id != 1
				and accounts.currency_id = $currency_id
				and (account_number LIKE '%$q%' 
				or CONCAT(account_number, ' - ', account_name)  LIKE '%$q%'
				or account_name LIKE '%$q%')"
            )
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function search_suppliers_suggestions($q = false)
    {
        (false === $q) and ($q = $this->input->post('term', true));
        $query = $this->db->select("id, account_number, account_name")
            ->select("CONCAT_WS(' - ', account_number, account_name) AS description")
            ->from($this->_table)
            ->where(
                "accounts.id != 1
				and accounts.account_type = 'Supplier'
				and (account_number LIKE '%$q%' 
				or CONCAT(account_number, ' - ', account_name)  LIKE '%$q%'
				or account_name LIKE '%$q%')"
            )
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function search_customers_suggestions($q = false)
    {
        (false === $q) and ($q = $this->input->post('term', true));
        $query = $this->db->select("id, account_number, account_name")
            ->select("CONCAT_WS(' - ', account_number, account_name) AS description")
            ->from($this->_table)
            ->where(
                "accounts.id != 1
				and accounts.account_type = 'Customer'
				and (account_number LIKE '%$q%' 
				or CONCAT(account_number, ' - ', account_name)  LIKE '%$q%'
				or account_name LIKE '%$q%')"
            )
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function fetch_nb_of_accounts_by_type($type)
    {
        $this->db->select('COUNT(*) AS count');
        $this->db->from('accounts');
        $this->db->where('accounts.account_type', $type);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function add_new_account($account_data)
    {
        return $this->db->insert('accounts', $account_data);
    }

    public function fetch_nb_of_accounts_having_currency_id($currency_id)
    {
        $this->db->select('COUNT(*) AS count');
        $this->db->from('accounts');
        $this->db->where('accounts.currency_id', $currency_id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function fetch_account_by_type_and_currency_id($account_type, $currency_id)
    {
        $this->db->select("id")
            ->select("CONCAT_WS(' - ', account_number, account_name) AS description");
        $this->db->from('accounts');
        $this->db->where('accounts.currency_id', $currency_id);
        $this->db->where('accounts.account_type', $account_type);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function paginate_filtered_accounts($account_type)
    {
        $query = [
            'select' => "accounts.*, currencies.currency_code AS currency",
            'join' => [['currencies', 'currencies.id = accounts.currency_id', 'left']],
            'where' => [
                ['accounts.id != 1'],
                ['accounts.account_type =', $account_type]
            ],
            'order_by' => [['account_number', 'ASC']]
        ];
        return parent::paginate($query, ['urlPrefix' => '']);
    }

    public function load_filtered_accounts_data_tables($account_type)
    {
        $dt = [
            'columns' => [
                'account_number', 'account_name', 'account_type',
                ['currencies.currency_code', 'currency'],
                'opening_date', 'phone', 'email', 'address', 'debit',
                'credit', 'balance', 'open_balance', 'accounts.id'
            ], 'query' => [
                'join' => [
                    ['currencies', 'currencies.id = accounts.currency_id', 'left']
                ],
                'where' => [
                    ['accounts.id != 1'],
                    ['accounts.account_type =', $account_type]
                ],

            ],
            'search_in' => ['opening_date', 'account_number', 'account_name', 'account_type', 'phone', 'email', 'address']
        ];
        $this->remove_indexes_if_search_not_date($dt['search_in'], [0]);
        return parent::load_datatables_pagedata($dt);
    }

    public function fetch_account_type($id)
    {
        $this->db->select('account_type');
        $this->db->from('accounts');
        $this->db->where('accounts.id', $id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function fetch_account_data($id)
    {
        $this->db->select('accounts.*');
        $this->db->from('accounts');
        $this->db->where('accounts.id', $id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function fetch_account_id($account_number)
    {
        $this->db->select('accounts.id');
        $this->db->from('accounts');
        $this->db->where('accounts.account_number', $account_number);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function update_account_credit_debit_balance($account_id, $balance, $credit, $debit)
    {
        $open_balance = $this->fetch_account_data($account_id)['open_balance'];
        if ($balance === NULL) {
            $balance = 0;
        }
        if ($credit === NULL) {
            $credit = 0;
        }
        if ($debit === NULL) {
            $debit = 0;
        }
        $data = array(
            'credit' => $credit,
            'debit' => $debit,
            'balance' => doubleval($balance) + doubleval($open_balance)
        );
        $this->db->where('id', $account_id);
        return $this->db->update('accounts', $data);
    }

    public function fetch_account_balance($acc_id)
    {
        $this->db->select('accounts.balance');
        $this->db->from('accounts');
        $this->db->where('accounts.id', $acc_id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function load_all_accounts_by_account_type($account_type)
    {
        $this->db->select('accounts.*');
        $this->db->from('accounts');
        $this->db->where('accounts.account_type', $account_type);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function load_account_data($id)
    {
        $this->db->select('accounts.*');
        $this->db->from('accounts');
        $this->db->where('accounts.id', $id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function load_account_by_account_type_and_currency_id($account_type, $currency_id)
    {
        $this->db->select('accounts.*');
        $this->db->from('accounts');
        $this->db->where('accounts.account_type', $account_type);
        $this->db->where('accounts.currency_id', $currency_id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function search_monthly_customers_suggestions($q = false)
    {
        (false === $q) and ($q = $this->input->post('term', true));
        $query = $this->db->select("id, account_number, account_name")
            ->select("CONCAT_WS(' - ', account_number, account_name) AS description")
            ->from($this->_table)
            ->where(
                "accounts.id != 1 
				and accounts.payment_method = 'Monthly'
				and accounts.account_type = 'Customer'
				and (account_number LIKE '%$q%' 
				or CONCAT(account_number, ' - ', account_name)  LIKE '%$q%'
				or account_name LIKE '%$q%')"
            )
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function fetch_all_accounts_by_account_type($account_type)
    {
        $this->db->select('id, account_number, account_name, CONCAT(CAST(account_number AS CHAR), "-", CAST(account_name AS CHAR)) AS description');
        $this->db->from('accounts');
        $this->db->where('accounts.id != 1');
        $this->db->where('accounts.account_type', $account_type);
        $query = $this->db->get()->result_array();
        return $query;
    }

}
