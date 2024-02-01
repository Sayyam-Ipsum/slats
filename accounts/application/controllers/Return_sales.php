<?phpdefined('BASEPATH') or die('No direct script access allowed');/** * @property Account $Account * @property Currency $Currency * @property Item $Item * @property Transaction $Transaction */class Return_sales extends MY_Controller{    public $Transaction = NULL;    public function __construct()    {        parent::__construct();        $this->pageTitle = $this->lang->line('return_invoices');        $this->load->model('Transaction');    }    public function index()    {        if ($this->input->is_ajax_request()) {            $this->_render_json($this->Transaction->load_transactions_data_tables(Transaction::ReturnSaleTransType, false));        } else {            $this->session->unset_userdata('previous_url');            $this->session->set_userdata('previous_url', 'return_sales/index');            $this->pageTitle = $this->lang->line('return_invoices');            $this->load->model('Transaction_item');            $data['records'] = $this->Transaction->paginate_return_sales();            $data['title'] = $this->lang->line('return_invoices');            $this->load->view('templates/header', [                '_page_title' => "Return Invoices",                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']            ]);            $this->load->view('return_sales/index', $data);            $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.datetime.format', 'jquery.dataTable.pagination.input', 'return_sales/index']]);        }    }    public function add()    {        $this->save(Transaction::ReturnSaleTransType, 0);    }    public function edit($id = '0')    {        $this->save(Transaction::ReturnSaleTransType, $id);    }    private function save($transType, $id = '0')    {        $fetched = ($id > 0 ? $this->Transaction->fetch(_gnv($id)) : false);        $post = $this->input->post(['trans', 'transItems', 'submitBtn'], true);        if ((!$fetched) and ($this->Transaction->set_next_auto_number($transType))) {            $this->Transaction->set_field('auto_no', $this->Transaction->set_next_auto_number($transType));        }        if ($post['submitBtn']) {            if ((!$fetched) and ($this->Transaction->set_next_auto_number($transType))) {                $post['trans']['auto_no'] = $this->Transaction->set_next_auto_number($transType);                $this->Transaction->set_field('user_id', $this->violet_auth->get_user_id());            }            $this->Transaction->set_fields($post['trans']);            $this->Transaction->set_field('trans_type', $transType);            $this->Transaction->set_field('fiscal_year_id', $this->violet_auth->get_fiscal_year_id());            $this->Transaction->set_field('user2_id', $this->violet_auth->get_user_id());            if ($fetched) {                $this->load->model('Transaction_item');                $trans = $this->Transaction->load_trans_data_by_trans_id($id);                $trans_items = $this->Transaction_item->load_all_trans_items($id);            }            $saved = $fetched ? $this->Transaction->update() : $this->Transaction->insert();            if ($saved) {                if (!$fetched) {                    $this->Transaction->save_trans_items_with_cost($post['transItems'], 1);                    $total = $this->Transaction->calculate_transaction_total($post['transItems'], $post['trans']['discount'], 0);                    $this->load->model('Account');                    $trans_id = $this->Transaction->fetch_transaction_id_by_autono($post['trans']['auto_no'], $transType);                    $this->Transaction->save_transaction_in_journals($post['trans'], $trans_id["0"]["id"], $total, "RS");                    $journal_id = $this->Transaction->fetch_journal_id_by_transaction_id($trans_id["0"]["id"], $transType);                    $name1 = $this->Account->fetch_account_name_by_id($post['trans']['account_id']);                    $name2 = $this->Account->fetch_account_name_by_id($post['trans']['account2_id']);                    $this->Transaction->save_in_journal_accounts($journal_id["0"]["id"], $post['trans']['account_id'], $post['trans']['auto_no'], $total, "-1", $name2["0"]["account_name"], "Retrurn Sale");                    $this->Transaction->save_in_journal_accounts($journal_id["0"]["id"], $post['trans']['account2_id'], $post['trans']['auto_no'], $total, "1", $name1["0"]["account_name"], "Retrurn Sale");                    //update items qty                    $this->Transaction->update_items_qty($post['transItems']);                    $this->load->model('Journal');                    //update balance debit credit for account 1                    $balance = $this->Journal->calculate_account_balance($post['trans']['account_id'])["total"];                    $credit = $this->Journal->calculate_account_credit($post['trans']['account_id'])["total"];                    $debit = $this->Journal->calculate_account_debit($post['trans']['account_id'])["total"];                    $this->Account->update_account_credit_debit_balance($post['trans']['account_id'], $balance, $credit, $debit);                    //update balance debit credit for account 2                    $balance = $this->Journal->calculate_account_balance($post['trans']['account2_id'])["total"];                    $credit = $this->Journal->calculate_account_credit($post['trans']['account2_id'])["total"];                    $debit = $this->Journal->calculate_account_debit($post['trans']['account2_id'])["total"];                    $this->Account->update_account_credit_debit_balance($post['trans']['account2_id'], $balance, $credit, $debit);                    redirect('return_sales/index');                } elseif ($fetched) {                    //update balance debit credit                    $this->load->model('Account');                    $total = $this->Transaction->calculate_transaction_total($post['transItems'], $post['trans']['discount'], 0);                    //delete journal                    $this->load->model('Journal');                    $journal_id = $this->Journal->fetch_journal_id_by_transaction_id($this->Transaction->get_field('id'));                    $this->Journal->delete($journal_id["id"]);                    //delete journal_acc                    $this->load->model('Journal_account');                    $journal_acc_ids = $this->Journal_account->fetch_journal_accounts_id_by_journal_id($journal_id["id"]);                    foreach ($journal_acc_ids as $j) {                        $this->Journal_account->delete($j["id"]);                    }                    //insert journal                    $total = $this->Transaction->calculate_transaction_total($post['transItems'], $post['trans']['discount'], 0);                    $this->Transaction->save_transaction_in_journals($post['trans'], $this->Transaction->get_field('id'), $total, "RS");                    //get new journal id                    $new_journal_id = $this->Journal->fetch_journal_id_by_transaction_id($this->Transaction->get_field('id'));                    //insert journal accounts                    $this->load->model('Account');                    $name1 = $this->Account->fetch_account_name_by_id($post['trans']['account_id']);                    $name2 = $this->Account->fetch_account_name_by_id($post['trans']['account2_id']);                    $this->Transaction->save_in_journal_accounts($new_journal_id["id"], $post['trans']['account_id'], $post['trans']['auto_no'], $total, "-1", $name2["0"]["account_name"], "Retrurn Sale");                    $this->Transaction->save_in_journal_accounts($new_journal_id["id"], $post['trans']['account2_id'], $post['trans']['auto_no'], $total, "1", $name1["0"]["account_name"], "Retrurn Sale");                    //delete trans_items                    $this->load->model('Transaction_item');                    $trans_items_id = $this->Transaction_item->fetch_trans_items_id_for_edit($this->Transaction->get_field('id'));                    foreach ($trans_items_id as $t) {                        $this->Transaction_item->delete($t["id"]);                    }                    //insert trans_items                    $this->Transaction->save_trans_items_with_cost($post['transItems'], 1);                    //update items qty                    $this->Transaction->update_items_qty($trans_items);                    $this->Transaction->update_items_qty($post['transItems']);                    $this->load->model('Journal');                    //update balance debit credit for account 1                    $balance = $this->Journal->calculate_account_balance($post['trans']['account_id'])["total"];                    $credit = $this->Journal->calculate_account_credit($post['trans']['account_id'])["total"];                    $debit = $this->Journal->calculate_account_debit($post['trans']['account_id'])["total"];                    $this->Account->update_account_credit_debit_balance($post['trans']['account_id'], $balance, $credit, $debit);                    //update balance debit credit for account 2                    $balance = $this->Journal->calculate_account_balance($post['trans']['account2_id'])["total"];                    $credit = $this->Journal->calculate_account_credit($post['trans']['account2_id'])["total"];                    $debit = $this->Journal->calculate_account_debit($post['trans']['account2_id'])["total"];                    $this->Account->update_account_credit_debit_balance($post['trans']['account2_id'], $balance, $credit, $debit);                    if ($trans['account_id'] !== $post['trans']['account_id']) {                        $balance = $this->Journal->calculate_account_balance($trans['account_id'])["total"];                        $credit = $this->Journal->calculate_account_credit($trans['account_id'])["total"];                        $debit = $this->Journal->calculate_account_debit($trans['account_id'])["total"];                        $this->Account->update_account_credit_debit_balance($trans['account_id'], $balance, $credit, $debit);                    }                    if ($trans['account2_id'] !== $post['trans']['account2_id']) {                        $balance = $this->Journal->calculate_account_balance($trans['account2_id'])["total"];                        $credit = $this->Journal->calculate_account_credit($trans['account2_id'])["total"];                        $debit = $this->Journal->calculate_account_debit($trans['account2_id'])["total"];                        $this->Account->update_account_credit_debit_balance($trans['account2_id'], $balance, $credit, $debit);                    }                    redirect($this->session->userdata('previous_url'));                }            } elseif ($this->Transaction->is_valid()) {                redirect($this->session->userdata('previous_url'));            }        }        $data = $this->_load_related_models($fetched, $transType, "Return Invoice");        //		$this->includes('js/transactions.form', 'js');        $this->load->view('templates/header', [            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],            '_page_title' => $data['transTypeText']        ]);        $this->load->view('return_sales/return_sales_form', $data);        $this->load->view('templates/footer', [            '_moreJs' => [                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',                'jquery.autocomplete.min', 'transactions/return_sale', 'accounts/account_modal', 'items/item_modal'            ]        ]);    }    private function _load_related_models($fetched, $transType, $transTypeText)    {        $data = [];        $this->load->model(['Currency', 'User']);        $data['transTypeText'] = ($fetched ? 'Edit ' : 'Add New ') . $transTypeText;        $data['transType'] = $this->Transaction->get_transaction_types_list()[$transType];        $data['currenciesList'] = $this->Currency->load_currencies_list();        $data['types'] = array(            "Individual" => "Individual", "Carage" => "Carage"        );        $data['account_type'] = array(            "Customer" => "Customer", "Supplier" => "Supplier",            "Cash" => "Cash", "Expenses" => "Expenses",            "Bank" => "Bank", "Sale VAT" => "Sale VAT",            "Purchase VAT" => "Purchase VAT"        );        $this->load->model('Configuration');        $TVA1 = $this->Configuration->fetch_TVA1()["valueStr"];        $TVA2 = $this->Configuration->fetch_TVA2()["valueStr"];        $TVA = [0, doubleval($TVA1), doubleval($TVA2)];        $data['TVA'] = array_combine($TVA, $TVA);        if ($fetched) {            $this->load->model('Account');            $account = $this->Account->load($this->Transaction->get_field('account_id'));            $data['account'] = "{$account['account_number']} - {$account['account_name']}";            $account2 = $this->Account->load($this->Transaction->get_field('account2_id'));            $data['account2'] = "{$account2['account_number']} - {$account2['account_name']}";            $this->load->model('Transaction_item');            $data['trans_items'] = $this->Transaction_item->load_all_trans_items($this->Transaction->get_field('id'));            $this->load->model('Warehouse');            $data['warehouse'] = [];            $data['shelf'] = [];            $data['shelf_list'] = [];            foreach ($data['trans_items'] as $t) {                $res = $this->Warehouse->fetch_warehouse_and_shelf($t["warehouse_id"]);                array_push($data['warehouse'], $res['warehouse']);                array_push($data['shelf'], $res['shelf']);                $s = $this->Warehouse->fetch_all_warehouse_shelfs($res['warehouse']);                $s = array_combine($s, $s);                array_push($data['shelf_list'], $s);            }            $w = $this->Warehouse->load_warehouses_list();            $data['warehouses_list'] = array_combine($w, $w);            $data['trans_date'] = $this->Transaction->get_field('trans_date');            $data['value_date'] = $this->Transaction->get_field('value_date');            $data['user_add'] = $this->User->get_user_name($this->Transaction->get_field('user_id'))['user_name'];            $data['user_edit'] = $this->User->get_user_name($this->Transaction->get_field('user2_id'))['user_name'];            $data['created_on'] = $this->Transaction->get_transaction_created_on_field($this->Transaction->get_field('id'))['created_on'];        } else {            $data['account'] = '';            $data['account2'] = '';            $data['trans_items'] = [];            $this->load->model('Warehouse');            $w = $this->Warehouse->load_warehouses_list();            $data['warehouses_list'] = array_combine($w, $w);            $data['trans_date'] = date("d-m-Y");            $data['value_date'] = date("d-m-Y");            $data['user_add'] = '';            $data['user_edit'] = '';            $data['created_on'] = '';        }        return $data;    }    public function delete($id)    {        $this->load->model('Account');        $this->load->model('Transaction_item');        $trans = $this->Transaction->load_trans_data_by_trans_id($id);        $trans_items = $this->Transaction_item->load_all_trans_items($id);        if ($this->Transaction->delete($id)) {            //update qty            $this->Transaction->update_items_qty($trans_items);            $this->load->model('Journal');            //update balance debit credit for account 1            $balance = $this->Journal->calculate_account_balance($trans['account_id'])["total"];            $credit = $this->Journal->calculate_account_credit($trans['account_id'])["total"];            $debit = $this->Journal->calculate_account_debit($trans['account_id'])["total"];            $this->Account->update_account_credit_debit_balance($trans['account_id'], $balance, $credit, $debit);            //update balance debit credit for account 2            $balance = $this->Journal->calculate_account_balance($trans['account2_id'])["total"];            $credit = $this->Journal->calculate_account_credit($trans['account2_id'])["total"];            $debit = $this->Journal->calculate_account_debit($trans['account2_id'])["total"];            $this->Account->update_account_credit_debit_balance($trans['account2_id'], $balance, $credit, $debit);            redirect('return_sales/index');        } else {            // $this->add_msg($this->lang->line('record_not_deleted'), 'warning');            redirect('return_sales/index');        }    }    public function lookup_accounts()    {        $this->load->model('Account');        $this->_render_json(            $this->Account->search_suggestions(trim($this->input->get('query', true)))        );    }    public function lookup_items()    {        $this->load->model('Item');        $this->_render_json(            $this->Item->search_suggestions(trim($this->input->get('query', true)))        );    }    public function preview($id)    {        $this->load->model('Account');        $this->load->model('Transaction_item');        $this->load->model('Configuration');        $this->load->model('Currency');        $this->load->model('Warehouse');        $data['trans'] = $this->Transaction->load_trans_data_by_trans_id($id);        $data['customer_info'] = $this->Account->fetch_account_info($data['trans']["account_id"]);        $data['sales_info'] = $this->Account->fetch_account_info($data['trans']["account2_id"]);        $data['trans_items'] = $this->Transaction_item->load_all_trans_items($id);        $total = 0;        $subtotal = 0;        $tva_amount = 0;        foreach ($data['trans_items'] as $k => $t) {            $res = $this->Warehouse->fetch_warehouse_and_shelf($t['warehouse_id']);            $data['trans_items'][$k]["warehouse"] = $res["warehouse"];            $data['trans_items'][$k]["shelf"] = $res["shelf"];            $data['trans_items'][$k]["total"] = floatval($t["price"]) * floatval($t["qty"]) * (1 - (floatval($t["discount"]) / 100));            $total += $data['trans_items'][$k]["total"];            $subtotal += (floatval($t["cost"]) * (1 + floatval($t["item_profit"]) / 100)) * floatval($t["qty"]) * (1 - (floatval($t["discount"]) / 100));            $tva_amount += ((floatval($t["cost"]) * (1 + floatval($t["item_profit"]) / 100)) * (floatval($data['trans']['TVA']) / 100) * floatval($t["qty"]));        }        $data['tva_amount'] = $tva_amount;        $data['sub_total'] = $subtotal;        $data['total'] = floatval($total) - floatval($data['trans']["discount"]);        $data['company_name'] = $this->Configuration->fetch_company_name()["valueStr"];        $data['company_address'] = $this->Configuration->fetch_company_address()["valueStr"];        $data['company_phone'] = $this->Configuration->fetch_company_phone()["valueStr"];        $data['company_email'] = $this->Configuration->fetch_company_email()["valueStr"];        $data['company_website'] = $this->Configuration->fetch_company_website()["valueStr"];        $data['currency'] = $this->Currency->fetch_currency_code($data['trans']["currency_id"])["currency_code"];        $data['title'] = "Return Sale Invoice";        $this->load->view('templates/header', [            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],            '_page_title' => "Print"        ]);        $this->load->view('sales/preview', $data);        $this->load->view('templates/footer', [            '_moreJs' => [                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',                'jquery.autocomplete.min', 'sales/preview'            ]        ]);    }}