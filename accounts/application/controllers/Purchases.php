<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * @property Account $Account
 * @property Currency $Currency
 * @property Item $Item
 * @property Transaction $Transaction
 * @property Warehouse $Warehouse
 * @property Transaction_item $Transaction_item
 * @property Journal $Journal
 */
class Purchases extends MY_Controller
{

    public $Transaction = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = $this->lang->line('transactions');
        $this->load->model('Transaction');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->_render_json($this->Transaction->load_receiving_data_tables(Transaction::PurchaseTransType, true));
        } else {
            $this->session->unset_userdata('previous_url');
            $this->session->set_userdata('previous_url', 'purchases/index');
            $this->pageTitle = $this->lang->line('transactions');
            $this->load->model('Transaction_item');
            $data['records'] = $this->Transaction->paginate_receiving(Transaction::PurchaseTransType, true);
            $data['title'] = $this->lang->line('receiving');
            $this->load->view('templates/header', [
                '_page_title' => "Purchases",
                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
            ]);
            $this->load->view('transactions/index', $data);
            $this->load->view('templates/footer', ['_moreJs' => ['jquery.dataTables.min', 'dataTables.bootstrap.min', 'jquery.dataTable.pagination.input', 'purchases/index']]);

        }

    }

    public function add()
    {
        $this->save(Transaction::PurchaseTransType, 0);
    }

    public function edit($id = '0')
    {
        if ($this->violet_auth->get_user_type() !== 'Master Admin') {
            $trans = $this->Transaction->check_if_user_can_edit($id);
            if ($trans["check"] === 1) {
                $this->save(Transaction::PurchaseTransType, $id);
            } else {
                $this->load->model('User');
                $user_name = $this->User->get_user_name($trans["edit_user_id"])['user_name'];
                $this->session->set_flashdata('message_error_header', 'Warning: this Receiving (#' . $trans["auto_no"] . ') is locked by ' . $user_name . '.');
                redirect('purchases/index');
            }
        } else {
            $this->save(Transaction::PurchaseTransType, $id);
        }
    }

    private function save($transType, $id = '0')
    {
        $fetched = ($id > 0 ? $this->Transaction->fetch(_gnv($id)) : false);
        $post = $this->input->post(['trans', 'transItems', 'submitBtn'], true);
        if ((!$fetched) and ($this->Transaction->set_next_auto_number($transType))) {
            $this->Transaction->set_field('auto_no', $this->Transaction->set_next_auto_number($transType));
        }
        if ($post['submitBtn']) {
            if ((!$fetched) and ($this->Transaction->set_next_auto_number($transType))) {
                $post['trans']['auto_no'] = $this->Transaction->set_next_auto_number($transType);
                $this->Transaction->set_field('user_id', $this->violet_auth->get_user_id());
            }
            $this->Transaction->set_fields($post['trans']);
            $this->Transaction->set_field('trans_type', $transType);
            $this->Transaction->set_field('fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
            $this->Transaction->set_field('user2_id', $this->violet_auth->get_user_id());
            if ($fetched) {
                $this->load->model('Transaction_item');
                $trans = $this->Transaction->load_trans_data_by_trans_id($id);
                $transitems = $this->Transaction_item->load_all_trans_items($id);
            }
            $saved = $fetched ? $this->Transaction->update() : $this->Transaction->insert();
            if ($saved) {
                if (!$fetched) {
                    $this->load->model('Account');
                    $this->Transaction->save_purchases_items($post['transItems'], 1);

                    $total = $this->Transaction->calculate_purchase_total($post['transItems'], $post['trans']['discount']);

                    $trans_id = $this->Transaction->fetch_transaction_id_by_autono($post['trans']['auto_no'], $transType);
                    $this->Transaction->save_transaction_in_journals($post['trans'], $trans_id["0"]["id"], $total, "PU");

                    $journal_id = $this->Transaction->fetch_journal_id_by_transaction_id($trans_id["0"]["id"], $transType);
                    $name1 = $this->Account->fetch_account_name_by_id($post['trans']['account_id']);
                    $name2 = $this->Account->fetch_account_name_by_id($post['trans']['account2_id']);
                    $this->Transaction->save_in_journal_accounts($journal_id[0]["id"], $post['trans']['account_id'], $post['trans']['auto_no'], $total, "-1", $name2["0"]["account_name"], "Purchase");
                    $this->Transaction->save_in_journal_accounts($journal_id[0]["id"], $post['trans']['account2_id'], $post['trans']['auto_no'], $total, "1", $name1["0"]["account_name"], "Purchase");

                    //update cost
                    foreach ($post['transItems'] as $t) {
                        $last_trans_date = $this->Transaction->fetch_last_trans_date_of_purchase_or_transfer_of_item($t["item_id"]);
                        if ($last_trans_date) {
                            if ($last_trans_date !== null) {
                                $last_trans_item_id = $this->Transaction->fetch_last_purchase_or_transfer_of_item_using_trans_date($t["item_id"], $last_trans_date["trans_date"]);
                                $last = $this->Transaction_item->fetch_trans_item_data($last_trans_item_id["transaction_item_id"]);
                                $this->load->model('Currency');
                                $currency_code = $this->Currency->fetch_currency_code($last['currency_id'])["currency_code"];
                                $this->Transaction->update_cost_and_price_of_item($last, $last['currency_rate'], $currency_code);
                            } else {
                                $this->load->model('Item');
                                $open_cost = $this->Item->fetch_open_cost_of_item($t["item_id"])["open_cost"];
                                $this->Item->update_item_cost_by_item_id($t["item_id"], $open_cost);
                                $this->Item->update_item_purchase_cost_by_item_id($t["item_id"], 0);
                            }
                        } else {
                            $this->load->model('Item');
                            $open_cost = $this->Item->fetch_open_cost_of_item($t["item_id"])["open_cost"];
                            $this->Item->update_item_cost_by_item_id($t["item_id"], $open_cost);
                            $this->Item->update_item_purchase_cost_by_item_id($t["item_id"], 0);
                        }
                    }
                    //update items qty
                    $this->Transaction->update_items_qty($post['transItems']);
                    $this->load->model('Journal');
                    //update balance debit credit for account 1
                    $balance = $this->Journal->calculate_account_balance($post['trans']['account_id'])["total"];
                    $credit = $this->Journal->calculate_account_credit($post['trans']['account_id'])["total"];
                    $debit = $this->Journal->calculate_account_debit($post['trans']['account_id'])["total"];
                    $this->Account->update_account_credit_debit_balance($post['trans']['account_id'], $balance, $credit, $debit);
                    //update balance debit credit for account 2
                    $balance = $this->Journal->calculate_account_balance($post['trans']['account2_id'])["total"];
                    $credit = $this->Journal->calculate_account_credit($post['trans']['account2_id'])["total"];
                    $debit = $this->Journal->calculate_account_debit($post['trans']['account2_id'])["total"];
                    $this->Account->update_account_credit_debit_balance($post['trans']['account2_id'], $balance, $credit, $debit);
                    $this->session->unset_userdata('previous_url');
                    $this->session->set_userdata('previous_url', 'purchases/index');
                    // redirect('purchases/edit/' . $this->Transaction->get_field('id'));
                    if ($post['submitBtn'] === "Save") {
                        redirect('purchases/edit/' . $this->Transaction->get_field('id'));
                    } else {
                        redirect('purchases/exit/' . $this->Transaction->get_field('id'));
                    }
                } elseif ($fetched) {
                    //update balance debit credit
                    $this->load->model('Account');

                    $total = $this->Transaction->calculate_purchase_total($post['transItems'], $post['trans']['discount']);
                    //delete journal
                    $this->load->model('Journal');
                    $journal_id = $this->Journal->fetch_journal_id_by_transaction_id($this->Transaction->get_field('id'));
                    $this->Journal->delete($journal_id["id"]);
                    //delete journal_acc
                    $this->load->model('Journal_account');
                    $journal_acc_ids = $this->Journal_account->fetch_journal_accounts_id_by_journal_id($journal_id["id"]);
                    foreach ($journal_acc_ids as $j) {
                        $this->Journal_account->delete($j["id"]);
                    }
                    //insert journal
                    $this->Transaction->save_transaction_in_journals($post['trans'], $this->Transaction->get_field('id'), $total, "PU");
                    //get new journal id
                    $new_journal_id = $this->Journal->fetch_journal_id_by_transaction_id($this->Transaction->get_field('id'));
                    //insert journal accounts
                    $this->load->model('Account');
                    $name1 = $this->Account->fetch_account_name_by_id($post['trans']['account_id']);
                    $name2 = $this->Account->fetch_account_name_by_id($post['trans']['account2_id']);
                    $this->Transaction->save_in_journal_accounts($new_journal_id["id"], $post['trans']['account_id'], $post['trans']['auto_no'], $total, "-1", $name2["0"]["account_name"], "Purchase");
                    $this->Transaction->save_in_journal_accounts($new_journal_id["id"], $post['trans']['account2_id'], $post['trans']['auto_no'], $total, "1", $name1["0"]["account_name"], "Purchase");
                    //delete trans_items
                    $trans_items = $this->Transaction_item->load_all_trans_items($this->Transaction->get_field('id'));
                    $trans_items_id = $this->Transaction_item->fetch_trans_items_id_for_edit($this->Transaction->get_field('id'));
                    foreach ($trans_items_id as $t) {
                        $this->Transaction_item->delete($t["id"]);
                    }
                    //insert trans_items
                    $this->Transaction->save_purchases_items($post['transItems'], 1);

                    //update cost
                    $items_ids = [];
                    foreach ($post['transItems'] as $p) {
                        array_push($items_ids, $p["item_id"]);
                    }
                    foreach ($trans_items as $t) {
                        array_push($items_ids, $t["item_id"]);
                    }
                    foreach (array_unique($items_ids) as $t) {
                        $last_trans_date = $this->Transaction->fetch_last_trans_date_of_purchase_or_transfer_of_item($t);
                        if ($last_trans_date) {
                            if ($last_trans_date["trans_date"] !== NULL) {
                                $last_trans_item_id = $this->Transaction->fetch_last_purchase_or_transfer_of_item_using_trans_date($t, $last_trans_date["trans_date"]);
                                $last = $this->Transaction_item->fetch_trans_item_data($last_trans_item_id["transaction_item_id"]);
                                $this->load->model('Currency');
                                $currency_code = $this->Currency->fetch_currency_code($last['currency_id'])["currency_code"];
                                $this->Transaction->update_cost_and_price_of_item($last, $last['currency_rate'], $currency_code);
                            } else {
                                $this->load->model('Item');
                                $open_cost = $this->Item->fetch_open_cost_of_item($t)["open_cost"];
                                $this->Item->update_item_cost_by_item_id($t, $open_cost);
                                $this->Item->update_item_purchase_cost_by_item_id($t, 0);
                            }
                        } else {
                            $this->load->model('Item');
                            $open_cost = $this->Item->fetch_open_cost_of_item($t)["open_cost"];
                            $this->Item->update_item_cost_by_item_id($t, $open_cost);
                            $this->Item->update_item_purchase_cost_by_item_id($t, 0);
                        }
                    }
                    //update items qty
                    $this->Transaction->update_items_qty($transitems);
                    $this->Transaction->update_items_qty($post['transItems']);
                    $this->load->model('Journal');
                    //update balance debit credit for account 1
                    $balance = $this->Journal->calculate_account_balance($post['trans']['account_id'])["total"];
                    $credit = $this->Journal->calculate_account_credit($post['trans']['account_id'])["total"];
                    $debit = $this->Journal->calculate_account_debit($post['trans']['account_id'])["total"];
                    $this->Account->update_account_credit_debit_balance($post['trans']['account_id'], $balance, $credit, $debit);
                    //update balance debit credit for account 2
                    $balance = $this->Journal->calculate_account_balance($post['trans']['account2_id'])["total"];
                    $credit = $this->Journal->calculate_account_credit($post['trans']['account2_id'])["total"];
                    $debit = $this->Journal->calculate_account_debit($post['trans']['account2_id'])["total"];
                    $this->Account->update_account_credit_debit_balance($post['trans']['account2_id'], $balance, $credit, $debit);
                    if ($trans['account_id'] !== $post['trans']['account_id']) {
                        $balance = $this->Journal->calculate_account_balance($trans['account_id'])["total"];
                        $credit = $this->Journal->calculate_account_credit($trans['account_id'])["total"];
                        $debit = $this->Journal->calculate_account_debit($trans['account_id'])["total"];
                        $this->Account->update_account_credit_debit_balance($trans['account_id'], $balance, $credit, $debit);
                    }
                    if ($trans['account2_id'] !== $post['trans']['account2_id']) {
                        $balance = $this->Journal->calculate_account_balance($trans['account2_id'])["total"];
                        $credit = $this->Journal->calculate_account_credit($trans['account2_id'])["total"];
                        $debit = $this->Journal->calculate_account_debit($trans['account2_id'])["total"];
                        $this->Account->update_account_credit_debit_balance($trans['account2_id'], $balance, $credit, $debit);
                    }
                    if ($post['submitBtn'] === "Save") {
                        redirect('purchases/edit/' . $this->Transaction->get_field('id'));
                    } else {
                        redirect('purchases/exit/' . $this->Transaction->get_field('id'));
                    }
                }
            } elseif ($this->Transaction->is_valid()) {
                // redirect($this->session->userdata('previous_url'));
                redirect('purchases/edit/' . $this->Transaction->get_field('id'));
            }
        }
        $data = $this->_load_related_models($fetched, $transType);
        $this->load->view('templates/header', [
            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],
            '_page_title' => $data['transTypeText']
        ]);
        $this->load->view('transactions/purchase_form', $data);
        $this->load->view('templates/footer', [
            '_moreJs' => [
                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',
                'jquery.autocomplete.min', 'transactions/purchase', 'accounts/account_modal', 'items/item_modal'
            ]
        ]);
    }

    private function _load_related_models($fetched, $transType)
    {
        $data = [];
        $this->load->model(['Currency', 'User']);
        $data['transTypeText'] = ($fetched ? 'Edit ' : 'Add New ') . 'Receiving';
        $data['transType'] = $this->Transaction->get_transaction_types_list()[$transType];
        $data['currenciesList'] = $this->Currency->load_currencies_list();
        $data['types'] = array(
            "Individual" => "Individual", "Carage" => "Carage"
        );
        $data['account_type'] = array(
            "Customer" => "Customer", "Supplier" => "Supplier",
            "Cash" => "Cash", "Expenses" => "Expenses",
            "Bank" => "Bank", "Sale VAT" => "Sale VAT",
            "Purchase VAT" => "Purchase VAT"
        );
        $this->load->model('Configuration');
        $TVA1 = $this->Configuration->fetch_TVA1()["valueStr"];
        $TVA2 = $this->Configuration->fetch_TVA2()["valueStr"];
        $TVA = [0, doubleval($TVA1), doubleval($TVA2)];
        $data['TVA'] = array_combine($TVA, $TVA);
        if ($fetched) {
            $this->load->model('Account');
            $account = $this->Account->load($this->Transaction->get_field('account_id'));
            $data['account'] = "{$account['account_number']} - {$account['account_name']}";
            $account2 = $this->Account->load($this->Transaction->get_field('account2_id'));
            $data['account2'] = "{$account2['account_number']} - {$account2['account_name']}";
            $this->load->model('Transaction_item');
            $data['trans_items'] = $this->Transaction_item->load_all_trans_items($this->Transaction->get_field('id'));
            $this->load->model('Warehouse');
            $data['warehouse'] = [];
            $data['shelf'] = [];
            $data['shelf_list'] = [];
            foreach ($data['trans_items'] as $t) {
                $res = $this->Warehouse->fetch_warehouse_and_shelf($t["warehouse_id"]);
                $data['trans_warehouse'] = $res['warehouse'];
                $data['trans_shelf'] = $res['shelf'];
                array_push($data['warehouse'], $res['warehouse']);
                array_push($data['shelf'], $res['shelf']);
                $s = $this->Warehouse->fetch_all_warehouse_shelfs($res['warehouse']);
                $s = array_combine($s, $s);
                array_push($data['shelf_list'], $s);
                $data['trans_shelf_list'] = $s;
                $pu_ids[] = $t['item_id'];
            }
            $w = $this->Warehouse->load_warehouses_list();
            $data['warehouses_list'] = array_combine($w, $w);
            $data['trans_date'] = $this->Transaction->get_field('trans_date');
            $data['value_date'] = $this->Transaction->get_field('value_date');
            $data["status"] = $this->Transaction->get_field('status');
            $data['user_add'] = $this->User->get_user_name($this->Transaction->get_field('user_id'))['user_name'];
            if ($this->User->get_user_name($this->Transaction->get_field('user2_id')) !== null && isset($this->User->get_user_name($this->Transaction->get_field('user2_id'))['user_name'])) {
                $data['user_edit'] = $this->User->get_user_name($this->Transaction->get_field('user2_id'))['user_name'];
            } else {
                $data['user_edit'] = "N/A";
            }
            $data['created_on'] = $this->Transaction->get_transaction_created_on_field($this->Transaction->get_field('id'))['created_on'];
            // $data['order_pu'] = $this->Transaction_item->load_all_trans_items($this->Transaction->get_field('relation_id'));
            $data['order_pu'] = $this->Transaction_item->load_all_trans_items_grouped_by_item($this->Transaction->get_field('relation_id'));
            // var_dump($data['order_pu'], $this->Transaction_item->load_all_trans_items_grouped_by_item($this->Transaction->get_field('relation_id')));exit;
            $data['extra_items'] = [];
            $data['missing_items'] = [];
            if ($data['order_pu']) {
                $trans_items1 = $this->Transaction_item->load_all_trans_items_grouped_by_item($this->Transaction->get_field('id'));
                foreach ($data['order_pu'] as $op) {
                    $op_ids[] = $op['item_id'];
                }
                $missing = array_diff($op_ids, $pu_ids);
                $extra = array_diff($pu_ids, $op_ids);
                $common = array_intersect($op_ids, $pu_ids);
                $missing_items = [];
                foreach ($data['order_pu'] as $op) {
                    foreach ($missing as $m) {
                        if ($m == $op['item_id']) {
                            $missing_items[] = $op;
                            break;
                        }
                    }
                }
                $extra_items = [];
                foreach ($trans_items1 as $t) {
                    foreach ($extra as $e) {
                        if ($e == $t['item_id']) {
                            $extra_items[] = $t;
                            break;
                        }
                    }
                }
                $common_items = [];
                foreach ($common as $k => $c) {
                    $qty_pu = $qty_op = 0;
                    foreach ($trans_items1 as $t) {
                        if ($c == $t['item_id']) {
                            $common_items[$k] = $t;
                            $qty_pu = $t['qty'];
                            break;
                        }
                    }
                    foreach ($data['order_pu'] as $op) {
                        if ($c == $op['item_id']) {
                            $qty_op = $op['qty'];
                            break;
                        }
                    }
                    $diff = $qty_op - $qty_pu;
                    if ($diff > 0) {
                        $common_items[$k]['qty'] = $diff;
                        $common_items[$k]['qty_diff'] = 1;
                        $missing_items[] = $common_items[$k];
                    }
                    if ($diff < 0) {
                        $common_items[$k]['qty'] = abs($diff);
                        $common_items[$k]['qty_diff'] = 1;
                        $extra_items[] = $common_items[$k];
                    }
                }
                $data['extra_items'] = $extra_items;
                $data['missing_items'] = $missing_items;
            }
            $data['show_tables'] = 1;
            $data['returns'] = $this->Transaction->load_all_return_purchases_items_of_receiving($this->Transaction->get_field('id'));
        } else {
            $data['account'] = '';
            $data['account2'] = '';
            $data['trans_items'] = [];
            $this->load->model('Warehouse');
            $w = $this->Warehouse->load_warehouses_list();
            $data['warehouses_list'] = array_combine($w, $w);
            $data['trans_date'] = date("d-m-Y");
            $data['value_date'] = date("d-m-Y");
            $data["status"] = 0;
            $data['user_add'] = '';
            $data['user_edit'] = '';
            $data['trans_warehouse'] = '';
            $data['trans_shelf_list'] = '';
            $data['trans_shelf'] = '';
            $data['created_on'] = '';
            $data['extra_items'] = [];
            $data['missing_items'] = [];
            $data['show_tables'] = 0;
            $data['returns'] = [];
        }
        return $data;
    }

    public function delete($id)
    {
        $user_type = $this->violet_auth->get_user_type();
        if ($user_type == 'Master Admin') {
            //update balance debit credit
            $this->load->model('Account');
            $this->load->model('Transaction_item');
            $this->load->model('Item');
            $trans = $this->Transaction->load_trans_data_by_trans_id($id);
            $trans_items = $this->Transaction_item->load_all_trans_items($id);
            $related_ops = $this->Transaction->load_all_ops_of_purchase($id);
            //delete
            $deleted = $this->Transaction->delete($id);
            //update qty
            $this->Transaction->update_items_qty($trans_items);
            $this->load->model('Journal');
            //update balance debit credit for account 1
            $balance = $this->Journal->calculate_account_balance($trans['account_id'])["total"];
            $credit = $this->Journal->calculate_account_credit($trans['account_id'])["total"];
            $debit = $this->Journal->calculate_account_debit($trans['account_id'])["total"];
            $this->Account->update_account_credit_debit_balance($trans['account_id'], $balance, $credit, $debit);
            //update balance debit credit for account 2
            $balance = $this->Journal->calculate_account_balance($trans['account2_id'])["total"];
            $credit = $this->Journal->calculate_account_credit($trans['account2_id'])["total"];
            $debit = $this->Journal->calculate_account_debit($trans['account2_id'])["total"];
            $this->Account->update_account_credit_debit_balance($trans['account2_id'], $balance, $credit, $debit);
            //update cost
            foreach ($trans_items as $t) {
                $last_PU_date = $this->Transaction->fetch_last_trans_date_of_purchase_of_item($t["item_id"]);
                if ($last_PU_date === NULL) {
                    $this->Item->update_item_purchase_cost_by_item_id($t["item_id"], 0);
                }
                $last_trans_date = $this->Transaction->fetch_last_trans_date_of_purchase_or_transfer_of_item($t["item_id"]);
                if ($last_trans_date["trans_date"] !== NULL) {
                    $last_trans_item_id = $this->Transaction->fetch_last_purchase_or_transfer_of_item_using_trans_date($t["item_id"], $last_trans_date["trans_date"]);
                    $last = $this->Transaction_item->fetch_trans_item_data($last_trans_item_id["transaction_item_id"]);
                    $this->load->model('Currency');
                    $currency_code = $this->Currency->fetch_currency_code($last['currency_id'])["currency_code"];
                    $this->Transaction->update_cost_and_price_of_item($last, $last['currency_rate'], $currency_code);
                } else {
                    $open_cost = $this->Item->fetch_open_cost_of_item($t["item_id"])["open_cost"];
                    $this->Item->update_item_cost_by_item_id($t["item_id"], $open_cost);
                    $this->Item->update_item_purchase_cost_by_item_id($t["item_id"], 0);
                }
            }
            if ($related_ops) {
                foreach ($related_ops as $op_id) {
                    $this->Transaction->update_relation_id($op_id['id'], NULL);
                }
            }
            if ($deleted) {
                $this->session->set_flashdata('message_success', 'Deleted Successfully');
                redirect('purchases/index');
            } else {
                $this->session->set_flashdata('message', 'Sorry, Something Went Wrong!');
                redirect('purchases/index');
            }
        } else {
            $this->session->set_flashdata('message', 'Sorry, you have no permission to delete Order Purchase!');
            redirect('purchases/index');
        }
    }

    public function lookup_accounts()
    {
        $this->load->model('Account');
        $this->_render_json(
            $this->Account->search_suggestions(trim($this->input->get('query', true)))
        );
    }

    public function lookup_items()
    {
        $this->load->model('Item');
        $this->_render_json(
            $this->Item->search_suggestions(trim($this->input->get('query', true)))
        );
    }

    public function get_account_currency()
    {
        $acc_id = $this->input->post('whatselected');
        $this->load->model('Account');
        $currency_id = $this->Account->fetch_account_currency_id($acc_id);
        echo($currency_id["currency_id"]);
    }

    public function get_warehouse_shelfs()
    {
        $warehouse = $this->input->post('whatselected');
        $this->load->model('Warehouse');
        $shelfs = $this->Warehouse->fetch_all_warehouse_shelfs($warehouse);
        $this->_render_json(
            $shelfs
        );
    }

    public function get_item_data()
    {
        $item_id = $this->input->post('whatselected');
        $this->load->model('Item');
        $this->_render_json(
            $this->Item->load_item_data($item_id)
        );
    }

    public function get_warehouse_shelfs_for_OP()
    {
        $warehouse = $this->input->post('whatselected');
        $item_id = $this->input->post('item_id');
        $this->load->model('Warehouse');
        $OP_shelfs = $this->Warehouse->fetch_item_warehouse_shelfs_for_OP($warehouse, $item_id);
        $all_shelfs = $this->Warehouse->fetch_all_warehouse_shelfs($warehouse);
        $shelfs = array_diff($all_shelfs, $OP_shelfs);
        $list = [];
        foreach ($shelfs as $s) {
            array_push($list, $s);
        }
        $this->_render_json(
            $list
        );
    }

    public function lookup_purchases_accounts($currency_id)
    {
        $this->load->model('Account');
        $this->_render_json(
            $this->Account->search_purchases_suggestions(trim($this->input->get('query', true)), $currency_id)
        );
    }

    public function preview($id)
    {
        $this->load->model('Account');
        $this->load->model('Transaction_item');
        $this->load->model('Configuration');
        $this->load->model('Currency');
        $this->load->model('Warehouse');
        $data['trans'] = $this->Transaction->load_trans_data_by_trans_id($id);
        $data['customer_info'] = $this->Account->fetch_account_info($data['trans']["account_id"]);
        $data['sales_info'] = $this->Account->fetch_account_info($data['trans']["account2_id"]);
        $data['trans_items'] = $this->Transaction_item->load_all_trans_items($id);
        $total = 0;
        foreach ($data['trans_items'] as $k => $t) {
            $res = $this->Warehouse->fetch_warehouse_and_shelf($t['warehouse_id']);
            $data['trans_items'][$k]["warehouse"] = $res["warehouse"];
            $data['trans_items'][$k]["shelf"] = $res["shelf"];
            $data['trans_items'][$k]["total"] = (floatval($t["price"]) * (1 - (floatval($t["discount"]) / 100)) + floatval($t["cost"])) * floatval($t["qty"]);
            $total += $data['trans_items'][$k]["total"];
        }
        $data['sub_total'] = $total;
        $data['total'] = floatval($total) - floatval($data['trans']["discount"]);
        $data['company_name'] = $this->Configuration->fetch_company_name()["valueStr"];
        $data['company_address'] = $this->Configuration->fetch_company_address()["valueStr"];
        $data['company_phone'] = $this->Configuration->fetch_company_phone()["valueStr"];
        $data['company_email'] = $this->Configuration->fetch_company_email()["valueStr"];
        $data['company_website'] = $this->Configuration->fetch_company_website()["valueStr"];
        $data['currency'] = $this->Currency->fetch_currency_code($data['trans']["currency_id"])["currency_code"];
        $data['title'] = "Purchase Invoice";
        $this->load->view('templates/header', [
            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],
            '_page_title' => "Print"
        ]);
        $this->load->view('transactions/preview', $data);
        $this->load->view('templates/footer', [
            '_moreJs' => [
                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',
                'jquery.autocomplete.min', 'sales/preview'
            ]
        ]);
    }

    public function check($id)
    {
        $this->load->model('Transaction_item');
        $this->load->model('Item');
        $data['imported_items'] = [];
        $data['trans'] = $this->Transaction->load_trans_data_by_trans_id($id);
        $data['trans_items'] = $this->Transaction_item->load_all_trans_items_grouped_by_item_id($id);
        $post = $this->input->post(['trans', 'transItems', 'submitBtn', 'import'], true);
        if ($post['import']) {
            if ($_FILES["file"]["name"] !== "") {
                $path = $_FILES["file"]["tmp_name"];
                require_once APPPATH . "libraries/PHPExcel/Classes/PHPExcel.php";
                $object = PHPExcel_IOFactory::load($path);
                foreach ($object->getWorksheetIterator() as $worksheet) {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    for ($row = 2; $row <= $highestRow; $row++) {
                        $artical_number = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        if ($artical_number !== NULL) {
                            $qty = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                            $price = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                            if (!is_numeric($qty)) {
                                $this->session->set_flashdata('message', '*Rejected: all Qty values in Excel File must be Numeric*');
                                redirect("purchases/check/" . $id);
                            }
                            $excel_data[] = array(
                                'artical_number' => $artical_number,
                                'qty' => $qty
                            );
                        }
                    }
                }
                if ($highestColumn === "E") {
                    $this->load->model('Item');
                    foreach ($excel_data as $d) {
                        $check = $this->Item->check_if_item_artical_number_exists($d["artical_number"])['count'];
                        if ($check === "0") {
                            $this->session->set_flashdata('message', '*Rejected: the imported Excel file contains a product with artical number "' . $d["artical_number"] . '" not defined in stock*');
                            redirect("purchases/check/" . $id);
                        }
                    }
                    foreach ($excel_data as $k => $e) {
                        $item = $this->Item->fetch_item_data_by_artical_nb($e["artical_number"]);
                        $data['imported_items'][$k]["item_id"] = $item["id"];
                        $data['imported_items'][$k]["EAN"] = $item["EAN"];
                        $data['imported_items'][$k]["artical_number"] = $e["artical_number"];
                        $data['imported_items'][$k]["qty"] = $e["qty"];
                    }
                }
            }
            // var_dump("ali");
            // exit;
        }
        $data['missing_products'] = [];
        $data['extra_products'] = [];
        $data['posted_products'] = [];
        if ($post['submitBtn']) {
            foreach ($post['transItems'] as $k => $p) {
                $item = $this->Item->fetch_item($p["item_id"]);
                $data['posted_products'][$k] = ["item_id" => $p["item_id"], "EAN" => $item[0]["EAN"], "artical_number" => $item[0]["artical_number"], "qty" => $p["qty"]];
            }
            $items_ids = [];
            foreach ($post['transItems'] as $k => $p) {
                $items_ids[$k] = $p["item_id"];
            }
            $array_item_ids = array_unique($items_ids);
            $items_posted = [];
            foreach ($array_item_ids as $id) {
                $qty = 0;
                foreach ($post['transItems'] as $p) {
                    if ($id === $p["item_id"]) {
                        $qty += $p["qty"];
                    }
                }
                array_push($items_posted, ["item_id" => $id, "qty" => $qty]);
            }

            foreach ($data['trans_items'] as $t) {
                $count = 0;
                $qty = 0;
                foreach ($items_posted as $p) {
                    if ($t["item_id"] === $p["item_id"]) {
                        if (floatval($t["qty"]) > floatval($p["qty"])) {
                            $qty = floatval($t["qty"]) - floatval($p["qty"]);
                            $count = 0;
                            break;
                        } else {
                            $count++;
                        }
                    } else {
                        $qty = floatval($t["qty"]);
                    }
                }
                if ($count === 0) {
                    $item = $this->Item->fetch_item($t["item_id"]);
                    array_push($data['missing_products'], ["item_id" => $t["item_id"], "EAN" => $item[0]["EAN"], "artical_number" => $item[0]["artical_number"], "qty" => $qty]);
                }
            }
            foreach ($items_posted as $p) {
                $count = 0;
                $qty = 0;
                $barcode = "";
                $desc = "";
                foreach ($data['trans_items'] as $t) {
                    if ($p["item_id"] === $t["item_id"]) {
                        if (floatval($t["qty"]) < floatval($p["qty"])) {
                            $qty = floatval($p["qty"]) - floatval($t["qty"]);
                            $count = 0;
                            break;
                        } else {
                            $count++;
                        }
                    } else {
                        $qty = floatval($p["qty"]);
                    }
                }
                if ($count === 0) {
                    $item = $this->Item->fetch_item($p["item_id"]);
                    array_push($data['extra_products'], ["item_id" => $p["item_id"], "EAN" => $item[0]["EAN"], "artical_number" => $item[0]["artical_number"], "qty" => $qty]);
                }
            }
            if ($data['extra_products'] === [] && $data['missing_products'] === []) {
                $this->session->set_flashdata('message', '*All Items are Found*');
            }
        }
        $data['title'] = "Check Purchase items";
        $this->load->view('templates/header', [
            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],
            '_page_title' => $data['title']
        ]);
        $this->load->view('transactions/check_items', $data);
        $this->load->view('templates/footer', [
            '_moreJs' => [
                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',
                'jquery.autocomplete.min', 'transactions/check'
            ]
        ]);
    }

    public function lookup_suppliers_accounts()
    {
        $this->load->model('Account');
        $this->_render_json(
            $this->Account->search_suppliers_suggestions(trim($this->input->get('query', true)))
        );
    }

    public function get_purchases_account_with_the_same_currency()
    {
        $currency_id = $this->input->post('whatselected');
        $this->load->model('Account');
        $this->_render_json(
            $this->Account->fetch_account_by_type_and_currency_id("Purchases", $currency_id)
        );
    }

    public function return_received_item($trans_item_id)
    {
        $data = $this->_load_related_models_return_received_item($trans_item_id);
        $post = $this->input->post(['trans', 'transItems', 'submitBtn'], true);
        if ($post['submitBtn']) {
            $transType = Transaction::ReturnPurchaseTransType;
            $post['trans']['auto_no'] = $this->Transaction->set_next_auto_number($transType);
            $this->Transaction->set_field('user_id', $this->violet_auth->get_user_id());
            $this->Transaction->set_fields($post['trans']);
            $this->Transaction->set_field('trans_type', $transType);
            $this->Transaction->set_field('relation_id', $data['trans']['id']);
            $this->Transaction->set_field('fiscal_year_id', $this->violet_auth->get_fiscal_year_id());
            $this->Transaction->set_field('user2_id', $this->violet_auth->get_user_id());
            $saved = $this->Transaction->insert();
            if ($saved) {
                $this->Transaction->save_trans_items_with_cost($post['transItems'], -1);
                $total = $this->Transaction->calculate_transaction_total($post['transItems'], $post['trans']['discount'], 0);
                $this->load->model('Account');
                $trans_id = $this->Transaction->fetch_transaction_id_by_autono($post['trans']['auto_no'], $transType);
                $this->Transaction->save_transaction_in_journals($post['trans'], $trans_id["0"]["id"], $total, "RP");
                $journal_id = $this->Transaction->fetch_journal_id_by_transaction_id($trans_id["0"]["id"], $transType);
                $name1 = $this->Account->fetch_account_name_by_id($post['trans']['account_id']);
                $name2 = $this->Account->fetch_account_name_by_id($post['trans']['account2_id']);
                $this->Transaction->save_in_journal_accounts($journal_id["0"]["id"], $post['trans']['account2_id'], $post['trans']['auto_no'], $total, "-1", $name1["0"]["account_name"], "Retrurn Purchase");
                $this->Transaction->save_in_journal_accounts($journal_id["0"]["id"], $post['trans']['account_id'], $post['trans']['auto_no'], $total, "1", $name2["0"]["account_name"], "Retrurn Purchase");
                //update items qty
                $this->Transaction->update_items_qty($post['transItems']);
                $this->load->model('Journal');
                //update balance debit credit for account 1
                $balance = $this->Journal->calculate_account_balance($post['trans']['account_id'])["total"];
                $credit = $this->Journal->calculate_account_credit($post['trans']['account_id'])["total"];
                $debit = $this->Journal->calculate_account_debit($post['trans']['account_id'])["total"];
                $this->Account->update_account_credit_debit_balance($post['trans']['account_id'], $balance, $credit, $debit);
                //update balance debit credit for account 2
                $balance = $this->Journal->calculate_account_balance($post['trans']['account2_id'])["total"];
                $credit = $this->Journal->calculate_account_credit($post['trans']['account2_id'])["total"];
                $debit = $this->Journal->calculate_account_debit($post['trans']['account2_id'])["total"];
                $this->Account->update_account_credit_debit_balance($post['trans']['account2_id'], $balance, $credit, $debit);
                redirect('return_purchases/index');
            }
        }
        $data['transTypeText'] = $this->Transaction->get_transaction_types_list()['RP'] . ' From Receiving #' . $data['trans']['auto_no'];
        $data['title'] = $this->Transaction->get_transaction_types_list()['RP'];
        $this->load->view('templates/header', [
            '_moreCss' => ['js/air-datepicker/css/datepicker.min'],
            '_page_title' => $data['title']
        ]);
        $this->load->view('transactions/return_purchase_from_pu', $data);
        $this->load->view('templates/footer', [
            '_moreJs' => [
                'air-datepicker/js/datepicker.min', 'air-datepicker/js/i18n/datepicker.en',
                'jquery.autocomplete.min', 'transactions/return_purchase', 'accounts/account_modal', 'items/item_modal'
            ]
        ]);
    }

    private function _load_related_models_return_received_item($trans_item_id)
    {
        $data = [];
        $this->load->model(['Currency', 'User']);
        $data['currenciesList'] = $this->Currency->load_currencies_list();
        $data['types'] = array(
            "Individual" => "Individual", "Carage" => "Carage"
        );
        $data['account_type'] = array(
            "Customer" => "Customer", "Supplier" => "Supplier",
            "Cash" => "Cash", "Expenses" => "Expenses",
            "Bank" => "Bank", "Sale VAT" => "Sale VAT",
            "Purchase VAT" => "Purchase VAT"
        );
        $this->load->model('Configuration');
        $TVA1 = $this->Configuration->fetch_TVA1()["valueStr"];
        $TVA2 = $this->Configuration->fetch_TVA2()["valueStr"];
        $TVA = [0, doubleval($TVA1), doubleval($TVA2)];
        $data['TVA'] = array_combine($TVA, $TVA);
        $data['account'] = '';
        $data['account2'] = '';
        $data['trans_items'] = [];
        $data['trans_date'] = date("d-m-Y");
        $data['value_date'] = date("d-m-Y");
        $data['user_add'] = '';
        $data['user_edit'] = '';
        $data['created_on'] = '';
        $this->load->model('Transaction_item');
        $data['trans'] = $this->Transaction_item->fetch_trans_data_by_trans_item_id($trans_item_id);
        $this->load->model('Account');
        $account = $this->Account->load($data['trans']['account_id']);
        $data['account'] = "{$account['account_number']} - {$account['account_name']}";
        $account2 = $this->Account->load($data['trans']['account2_id']);
        $data['account2'] = "{$account2['account_number']} - {$account2['account_name']}";
        $this->load->model('Transaction_item');
        $data['trans_items'][] = $this->Transaction_item->fetch_trans_item_data($trans_item_id);
        $this->load->model('Warehouse');
        $data['warehouses'] = [];
        $data['shelfs'] = [];
        foreach ($data['trans_items'] as $t) {
            $w_s = $this->Warehouse->fetch_warehouse_and_shelf($t["warehouse_id"]);
            array_push($data['warehouses'], $w_s["warehouse"]);
            array_push($data['shelfs'], $w_s["shelf"]);
            $warehouse_ids = $this->Warehouse->get_warehouse_ids_of_item($t["item_id"]);
            $w = [];
            foreach ($warehouse_ids as $w_id) {
                $result = $this->Warehouse->fetch_warehouse_and_shelf($w_id["warehouse_id"]);
                array_push($w, $result["warehouse"]);
            }
            $data['warehouses_list'] = array_combine($w, $w);
        }
        return $data;
    }

    public function exit($id)
    {
        // $trans = $this->Transaction->check_if_user_can_edit($id);
        // if ($trans["edit_user_id"] === $this->violet_auth->get_user_id()) {
        $this->Transaction->update_edit_user_id_and_locked($id, 0, '');
        // }
        redirect('purchases/index');
    }

    public function change_status()
    {
        $itemId = $this->input->post('itemId');
        $status = 0;
        $item = $this->Transaction->load_trans_data_by_trans_id($itemId);
        if ($item['status'] == 0) {
            $status = 1;
        } elseif ($item['status'] == 1) {
            $status = 0;
        }

        $data = array(
            'status' => $status
        );
        $this->db->where('id', $itemId);
        $this->db->update('transactions', $data);
    }
}
