<?php

defined('BASEPATH') or die('No direct script access allowed');

class Item extends MY_Model
{

    protected $modelName = 'Item';
    protected $_table = 'items';
    protected $_listFieldName = 'description';
    protected $_fieldsNames = ['id', 'barcode', 'description', 'category', 'open_cost', 'cost', 'purchase_cost', 'open_qty', 'qty', 'EAN', 'artical_number', 'description2', 'brand', 'item_range', 'weight', 'OE_nb', 'alternative', 'qty_multiplier'];
    protected $allowedNulls = [];

    public function __construct()
    {
        parent::__construct();
        // $this->validate = [
        // 	'barcode' => [
        // 		'required' => TRUE,
        // 		'allowEmpty' => FALSE,
        // 		'rule' => ['maxLength', 32],
        // 		'message' => sprintf($this->lang->line('required__max_length_rule'), $this->lang->line('barcode'), 32)
        // 	],
        // 	'description' => [
        // 		'required' => TRUE,
        // 		'allowEmpty' => FALSE,
        // 		'rule' => ['maxLength', 255],
        // 		'message' => sprintf($this->lang->line('required__max_length_rule'), $this->lang->line('description'), 255)
        // 	],
        // 	'profit' => [
        // 		'rule' => 'numeric',
        // 		'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('profit'), 255)
        // 	],
        // 	'price' => [
        // 		'rule' => 'numeric',
        // 		'message' => sprintf($this->lang->line('required__is_numeric_rule'), $this->lang->line('price'), 255)
        // 	],
        // ];
    }

    public function paginate_items()
    {
        /*$query = [
            'select' => "items.barcode, items.EAN as EAN,  items.brand as brand,
                                items.description as description, items.artical_number as artical_number,
                                items.purchase_cost as purchase_cost, items.cost as cost, items.qty as qty, items.id as id",
            'order_by' => [['description', 'ASC']]
        ];
        return parent::paginate($query, ['urlPrefix' => '']);*/
        //return $this->load_all($query);
        $query = [
            'select' => "items.*",
            'order_by' => [['description', 'ASC']]
        ];
        return parent::paginate($query, ['urlPrefix' => '']);
    }

    public function load_items_data_tables()
    {
        $dt = [
            'columns' => [
                'items.barcode', 'items.EAN', 'items.artical_number', 'items.description', 'items.brand', 'items.purchase_cost', 'items.cost', 'items.qty', 'items.id'
            ],
            'query' => [],
            'search_in' => ['items.barcode', 'items.description', 'items.category', 'items.brand', 'items.cost', 'items.open_cost', 'items.open_qty', 'items.qty', 'items.EAN', 'items.description2', 'items.artical_number']
        ];
        return parent::load_datatables_pagedata($dt);
    }

    public function search_suggestions($q = false)
    {
        (false === $q) and ($q = $this->input->post('term', true));
        $query = $this->db->select("{$this->_table}.*")
            ->select("CONCAT_WS(' - ', barcode, description) AS suggestion")
            ->from($this->_table)
            ->like('barcode', $q, 'after')
            ->or_like('category', $q, 'both')
            ->or_like("CONCAT(barcode, ' - ', description)", $q, 'both')
            ->or_like('description', $q, 'both')
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function fetch_item($id)
    {
        $query = [
            'select' => "items.*",
            'where' => [["items.id", $id]]
        ];
        return $this->load_all($query);
    }

    public function fetch_item_cost($id)
    {
        $query = [
            'select' => "items.cost",
            'where' => [["items.id", $id]]
        ];
        return $this->load_all($query);
    }

    public function generate_autonumber()
    {
        $query = $this->db->select_max('barcode', 'newbarcode')
            ->where('items.barcode >=', 9900000)
            ->where('items.barcode <', 9999999)
            ->get($this->_table);

        if ($query->row()->newbarcode == null) {
            $no = 9900000;
            return $no;
        } else {
            $no = $query->row()->newbarcode;
            return 1 + $no;
        }
    }


    public function get_barcode_by_id($id)
    {
        $query = $this->db->get_where($this->_table, array('id' => $id));
        return $query->row_array();
    }

    public function get_item_details($id)
    {
        $query = $this->db->select('*')
            ->from('transaction_items as t1')
            ->where('t1.item_id', $id)
            ->join('transactions as t2', 't1.transaction_id = t2.id', 'LEFT')
            ->get();
        return $query->row_array();
    }

    public function fetch_all_item_details($id)
    {
        $this->db->select('*');
        $this->db->from('items');
        $this->db->join('transaction_items', 'transaction_items.item_id = items.id');
        $this->db->join('transactions', 'transactions.id = transaction_items.transaction_id');
        $this->db->where('items.id', $id);
        $this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function fetch_qty_by_item_id($id)
    {
        $query = [
            'select' => "items.qty",
            'where' => [["items.id", $id]]
        ];
        return $this->load($query);
    }

    public function update_item_qty_by_item_id($id, $new_qty)
    {
        $data = [
            'qty' => $new_qty
        ];
        $this->db->where('id', $id);
        return $this->db->update('items', $data);
    }

    public function fetch_item_last_purchase($id)
    {
        $this->db->select('transaction_items.net_cost, transaction_items.id as trans_item_id, transactions.id as trans_id, items.id as item_id, items.open_cost');
        $this->db->from('items');
        $this->db->join('transaction_items', 'transaction_items.item_id = items.id');
        $this->db->join('transactions', 'transactions.id = transaction_items.transaction_id');
        $this->db->select_max('transactions.created_on');
        $this->db->where('items.id', $id);
        $this->db->where('transactions.trans_type', "PU");
        $this->db->where('transactions.fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function update_item_cost_by_item_id($id, $cost)
    {
        $data = [
            'cost' => $cost
        ];
        $this->db->where('id', $id);
        return $this->db->update('items', $data);
    }

    public function calculate_purchase_total_of_item($item_id, $trans_id)
    {
        $row = $this->db->get_where(
            'transaction_items',
            array(
                'transaction_items.item_id' => $item_id,
                'transaction_items.transaction_id' => $trans_id
            )
        )->result_array();

        $tot_item = floatval($row['0']['qty']) * (floatval($row['0']['price']) * (1 - (floatval($row['0']['discount']) / 100)) + floatval($row['0']['cost']));
        return $tot_item;
    }

    public function paginate_items_activity($item_id)
    {
        $query = [
            'select' => [
                ["items.description, transactions.trans_type, transactions.trans_date, transactions.auto_no"],
                ["account1.account_name, currencies.currency_code, currencies.currency_rate, w.warehouse, w.shelf, ti.qty, ti.price"],
                ["ROUND(( ti.qty * (IF( transactions.trans_type = 'PU', ti.cost, 0) + ((ti.price * (1 - ti.discount/100))))),2) AS total_of_item"],
                // ["journals.amount AS total"],
                ["transactions.id AS trans_id"]
            ],
            'join' => [
                ['transaction_items as ti', 'ti.item_id = items.id'],
                ['transactions', 'transactions.id = ti.transaction_id'],
                // ['journals', 'journals.transaction_id = transactions.id', 'inner'],
                ['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
                ['currencies', 'currencies.id = transactions.currency_id', 'inner'],
                ['warehouses as w', 'w.id = ti.warehouse_id', 'left']
            ],
            'where' => [
                ["ti.item_id", $item_id],
                ["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
            ],
            'order_by' => [['auto_no', 'ASC']]
        ];

        return parent::paginate($query, ['uri_segment' => 4, 'urlPrefix' => '']);
    }

    public function load_items_activity_data_tables($item_id)
    {
        $dt = [
            'columns' => [
                "account1.account_name", "transactions.trans_type", "transactions.trans_date",
                "transactions.auto_no", "currencies.currency_code", "currencies.currency_rate", "w.warehouse", "w.shelf", "ti.qty", "ti.price",
                ["ROUND(( ti.qty * (IF( transactions.trans_type = 'PU', ti.cost, 0) + ((ti.price * (1 - ti.discount/100))))),2)", "total_of_item"],
                // ["journals.amount", "total"],
                ["transactions.id", "trans_id"]

            ], 'query' => [
                'join' => [
                    ['transaction_items as ti', 'ti.item_id = items.id'],
                    ['transactions', 'transactions.id = ti.transaction_id'],
                    // ['journals', 'journals.transaction_id = transactions.id', 'inner'],
                    ['accounts AS account1', 'account1.id = transactions.account_id', 'inner'],
                    ['currencies', 'currencies.id = transactions.currency_id', 'inner'],
                    ['warehouses as w', 'w.id = ti.warehouse_id', 'left']
                ],
                'where' => [
                    ["items.id", $item_id],
                    ["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
                ]
            ],
            'search_in' => ['trans_date', 'account_name', 'trans_type', 'currency_code', 'warehouse']
        ];
        $this->remove_indexes_if_search_not_date($dt['search_in'], [0]);
        return parent::load_datatables_pagedata($dt);
    }

    public function update_open_qty($qty_cost_array, $currency_rate)
    {
        foreach ($qty_cost_array as $a) {
            $data = [
                'open_qty' => $a["qty"]
            ];
            $this->db->where('items.id', $a["item_id"]);
            $update = $this->db->update('items', $data);
        }
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function update_cost_and_price_in_opening_items($purchased_items, $profit_qty_cost_array, $currency_rate)
    {
        foreach ($purchased_items as $p) {
            foreach ($profit_qty_cost_array as $a) {
                if ($p["item_id"] === $a["item_id"]) {
                    if ($p["count"] < 1) {
                        if (floatval($a["profit"]) > 0) {
                            $data = [
                                'price' => (floatval($a["cost"]) * (1 + (floatval($a["profit"]) / 100))),
                                'cost' => ($a["cost"] * $currency_rate)
                            ];
                        } else {
                            $data = [
                                'cost' => ($a["cost"] * $currency_rate)
                            ];
                        }
                        $this->db->where('items.id', $a["item_id"]);
                        $update = $this->db->update('items', $data);
                    } else {
                        $update = true;
                    }
                }
            }
        }
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function fetch_profit_for_each_item_in_trans($items)
    {
        $query = [];
        foreach ($items as $i) {
            $this->db->select('profit');
            $this->db->from('items');
            $this->db->where('items.id', $i["item_id"]);
            $result = $this->db->get()->row_array();
            array_push($query, ["item_id" => $i["item_id"], "qty" => $i["qty"], "cost" => $i["cost"], "profit" => $result["profit"]]);
        }
        return $query;
    }

    public function find_missing_products($trans_items)
    {
        $missing_prod = [];
        foreach ($trans_items as $t) {
            $qty = $this->get_qty_of_item($t["item_id"]);
            if (doubleval($qty["qty"]) <= 0) {
                array_push($missing_prod, $t);
            } elseif ($qty["qty"] < $t["qty"]) {
                $t["qty"] = $t["qty"] - $qty["qty"];
                array_push($missing_prod, $t);
            }
        }
        return $missing_prod;
    }

    public function get_qty_of_item($item_id)
    {
        $this->db->select('qty');
        $this->db->from('items');
        $this->db->where('items.id', $item_id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function paginate_missing_products()
    {
        $query = [
            'select' => [
                ["items.EAN, items.artical_number, ti.qty, ti.id, ti.checkbox"]
            ],
            'join' => [
                ['transaction_items AS ti', 'ti.item_id= items.id', 'inner'],
                ['transactions', 'ti.transaction_id = transactions.id', 'inner']
            ],
            'where' => [
                ["transactions.trans_type", "MP"],
                ["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
            ],
        ];
        return parent::paginate($query, ['urlPrefix' => '']);
    }

    public function paginate_missing_products_not_checked()
    {
        $query = [
            'select' => [
                ["items.EAN, items.artical_number, ti.qty, ti.id, ti.checkbox"]
            ],
            'join' => [
                ['transaction_items AS ti', 'ti.item_id= items.id', 'inner'],
                ['transactions', 'ti.transaction_id = transactions.id', 'inner']
            ],
            'where' => [
                ["transactions.trans_type", "MP"],
                ["ti.checkbox =", NUll],
                ["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
            ],
        ];
        return parent::paginate($query, ['urlPrefix' => '']);
    }

    public function fetch_open_qty_of_item($item_id)
    {
        $this->db->select('open_qty');
        $this->db->from('items');
        $this->db->where('items.id', $item_id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function load_item_data($item_id)
    {
        $query = $this->db->select("{$this->_table}.*")
            ->from($this->_table)
            ->where('id', $item_id)
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function fetch_open_qty_by_item_id($id)
    {
        $query = [
            'select' => "items.open_qty ",
            'where' => [["items.id", $id]]
        ];
        return $this->load($query);
    }

    public function update_qty_in_op($trans_items)
    {
        foreach ($trans_items as $t) {
            $qty = $this->fetch_qty_by_item_id($t["item_id"])["qty"];
            $new_qty = $qty + $t["qty"];
            if ($new_qty > 0) {
                $data = [
                    'qty' => $new_qty
                ];
                $this->db->where('items.id', $t["item_id"]);
                $update = $this->db->update('items', $data);
            }
        }
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function check_barcode($barcode)
    {
        $this->db->select('count(*) as count');
        $this->db->from('items');
        $this->db->where('items.barcode', $barcode);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function paginate_missing_products_checked()
    {
        $query = [
            'select' => [
                ["items.EAN, items.artical_number, ti.qty, ti.id, ti.checkbox"]
            ],
            'join' => [
                ['transaction_items AS ti', 'ti.item_id= items.id', 'inner'],
                ['transactions', 'ti.transaction_id = transactions.id', 'inner']
            ],
            'where' => [
                ["transactions.trans_type", "MP"],
                ["ti.checkbox !=", NUll],
                ["transactions.fiscal_year_id", $this->violet_auth->get_fiscal_year_id()]
            ],
        ];
        return parent::paginate($query, ['urlPrefix' => '']);
    }

    public function update_open_cost_and_open_qty_on_OP_delete($item_id)
    {

        $data = [
            'open_qty' => 0,
            'open_cost' => 0
        ];
        $this->db->where('items.id', $item_id);
        $update = $this->db->update('items', $data);
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function fetch_open_cost_of_item($item_id)
    {
        $this->db->select('open_cost');
        $this->db->from('items');
        $this->db->where('items.id', $item_id);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function update_item_purchase_cost_by_item_id($id, $purchase_cost)
    {
        $data = [
            'purchase_cost' => $purchase_cost
        ];
        $this->db->where('id', $id);
        return $this->db->update('items', $data);
    }

    public function update_qty_on_delete_op($trans_items)
    {
        foreach ($trans_items as $t) {
            $qty = $this->fetch_qty_by_item_id($t["item_id"])["qty"];
            $new_qty = $qty - $t["qty"];
            if ($new_qty > 0) {
                $data = [
                    'qty' => $new_qty
                ];
                $this->db->where('items.id', $t["item_id"]);
                $this->db->update('items', $data);
            }
        }
    }

    public function load_all_items_ids()
    {
        $this->db->select('id');
        $this->db->from('items');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function check_EAN($EAN)
    {
        $this->db->select('count(*) as count');
        $this->db->from('items');
        $this->db->where('items.EAN', $EAN);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function check_artical_number($artical_number)
    {
        $this->db->select('count(*) as count');
        $this->db->from('items');
        $this->db->where('items.artical_number', $artical_number);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function fetch_item_data_by_EAN($EAN)
    {
        $this->db->select('*');
        $this->db->from('items');
        $this->db->where('items.EAN', $EAN);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function search_suggestions_EAN_and_artical_number($q = false)
    {
        (false === $q) and ($q = $this->input->post('term', true));
        $query = $this->db->select("{$this->_table}.*")
            ->select("CONCAT_WS(' - ', EAN, artical_number, brand) AS suggestion")
            ->from($this->_table)
            ->like('EAN', $q, 'after')
            ->or_like('brand', $q, 'both')
            ->or_like('artical_number', $q, 'both')
            ->or_like("CONCAT(EAN, ' - ', artical_number)", $q, 'both')
            ->or_like("CONCAT(artical_number, ' - ', brand)", $q, 'both')
            ->or_like("CONCAT(EAN, ' - ', artical_number, ' - ', brand)", $q, 'both')
            ->get();
        if (false !== $query && $query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function check_if_item_artical_number_exists($artical_number)
    {
        $this->db->select('count(*) as count');
        $this->db->from('items');
        $this->db->where('items.artical_number', $artical_number);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function add_new_item_in_import_excel_PU($barcode, $EAN, $artical_number, $description)
    {
        $data = [
            'barcode' => $barcode,
            'EAN' => $EAN,
            'artical_number' => $artical_number,
            'description' => $description,
        ];
        $this->db->insert('items', $data);
    }

    public function fetch_item_data_by_artical_nb($artical_number)
    {
        $this->db->select('*');
        $this->db->from('items');
        $this->db->where('items.artical_number', $artical_number);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function load_all_items_brands()
    {
        $this->db->select('brand');
        $this->db->from('items');
        $this->db->group_by('brand');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function update_group_of_items($items_id, $operation, $profit)
    {
        foreach ($items_id as $item_id) {
            $data = [
                'profit' => $profit
            ];
            $this->db->where('id', $item_id);
            $update = $this->db->update('items', $data);
        }
        return $update;
    }

    public function load_items_names_by_brand($brand)
    {
        $this->db->select('description');
        $this->db->from('items');
        $this->db->group_by('description');
        $this->db->where('brand', $brand);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function load_all_items_by_brand_and_description($brand, $description)
    {
        $this->db->select('id, barcode, EAN, artical_number, cost, profit, price');
        $this->db->from('items');
        $this->db->where('brand', $brand);
        $this->db->where('description', $description);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function group_update_of_profit_and_price($post)
    {
        foreach ($post["id"] as $k => $id) {
            $data = [
                'profit' => $post["profit"][$k],
                'price' => $post["price"][$k]
            ];
            $this->db->where('id', $id);
            $update = $this->db->update('items', $data);
        }
    }

    public function fetch_item_alternative($artical_number)
    {
        $this->db->select('alternative');
        $this->db->from('items');
        $this->db->where('artical_number', $artical_number);
        $query = $this->db->get()->row_array();
        return $query;
    }

    public function load_all_item_alternative($artical_number)
    {
        $this->db->select('id as item_id, artical_number, brand');
        $this->db->from('items');
        $this->db->where('alternative', $artical_number);
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function fetch_nb_of_items()
    {
        $this->db->select('COUNT(*) AS count');
        $this->db->from('items');
        $query = $this->db->get()->row_array();
        return $query;
    }
}
