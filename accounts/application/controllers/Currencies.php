<?php

defined('BASEPATH') or die('No direct script access allowed');

/**
 * @property Currency $Currency
 * @property Configuration $Configuration
 * @property Account $Account
 */
class Currencies extends MY_Controller
{

    public $Currency = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = $this->lang->line('currencies');
        $this->load->model('Currency');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $this->_render_json($this->Currency->load_currencies_data_tables());
        } else {
            $this->pageTitle = $this->lang->line('currencies');
            $data['records'] = $this->Currency->paginate_currencies();
            $this->load->view('templates/header', [
                '_page_title' => $this->lang->line('currencies'),
                '_moreCss' => ['css/dataTables.bootstrap.min', 'css/fixedHeader.dataTables.min']
            ]);
            $this->load->view('currencies/index', $data);
            $this->load->view('templates/footer', [
                '_moreJs' => [
                    'jquery.dataTables.min', 'dataTables.bootstrap.min', 'dataTables.fixedHeader.min', 'currencies/index'
                ]
            ]);
        }
    }

    public function add()
    {
        $this->save($this->lang->line('add_currency'), 0);
    }

    public function edit($id = '0')
    {
        $this->save($this->lang->line('edit_currency'), $id);
    }

    private function save($page_title, $id = '0')
    {
        // $this->load->model('Account');
        // $account_nb= $this->Account->generate_autonumber("Sales");
        // var_dump($account_nb);
        // exit;
        $fetched = ($id > 0 ? $this->Currency->fetch(_gnv($id)) : false);
        $post = $this->input->post(null, true);
        if (!empty($post)) {
            $this->Currency->set_fields($post);
            $saved = $fetched ? $this->Currency->update() : $this->Currency->insert();
            if ($saved) {
                if (!$fetched) {
                    $this->load->model('Account');
                    $account_nb = $this->Account->generate_autonumber("Purchases");
                    $currency_id = $this->Currency->get_field('id');
                    $purchase_account = array(
                        'account_number' => $account_nb,
                        'account_name' => "Purchase " . $post["currency_code"],
                        'account_type' => "Purchases",
                        'currency_id' => $currency_id,
                        'opening_date' => date("Y-m-d")
                    );
                    $this->Account->add_new_account($purchase_account);
                    $account_nb = $this->Account->generate_autonumber("Sales");
                    $sale_account = array(
                        'account_number' => $account_nb,
                        'account_name' => "Sale " . $post["currency_code"],
                        'account_type' => "Sales",
                        'currency_id' => $currency_id,
                        'opening_date' => date("Y-m-d")
                    );
                    $this->Account->add_new_account($sale_account);
                    $account_nb = $this->Account->generate_autonumber("Cash");
                    $cash_account = array(
                        'account_number' => $account_nb,
                        'account_name' => "Cash " . $post["currency_code"],
                        'account_type' => "Cash",
                        'currency_id' => $currency_id,
                        'opening_date' => date("Y-m-d")
                    );
                    $this->Account->add_new_account($cash_account);
                }
                redirect('currencies/index');
            } elseif ($this->Currency->is_valid()) {
                redirect('currencies/index');
            }
        }
        $data["title"] = $page_title;
        $this->load->view('templates/header', [
            '_page_title' => $page_title
        ]);
        $this->load->view('currencies/form', $data);
        $this->load->view('templates/footer', [
            '_moreJs' => ['currencies/form']
        ]);
    }

    public function delete($id)
    {
        $this->load->model('Account');
        $accounts_with_cur_id = $this->Account->fetch_nb_of_accounts_having_currency_id($id);
        $active = $this->Currency->check_if_currency_is_active($id);
        if ($active["count"] === "0") {
            if ($accounts_with_cur_id["count"] === "0") {
                if ($this->Currency->delete($id)) {
                    redirect('currencies/index');
                } else {
                    redirect('currencies/index');
                }
            } else {
                if ($accounts_with_cur_id["count"] === "2") {
                    $purch_acc = $this->Account->fetch_account_by_type_and_currency_id("Purchases", $id)["id"];
                    $sales_acc = $this->Account->fetch_account_by_type_and_currency_id("Sales", $id)["id"];
                    if ($purch_acc !== null && $sales_acc !== null) {
                        $this->Account->delete($purch_acc);
                        $this->Account->delete($sales_acc);
                        if ($this->Currency->delete($id)) {
                            redirect('currencies/index');
                        } else {
                            redirect('currencies/index');
                        }
                    } else {
                        $this->session->set_flashdata('message', '*Can not be deleted: this currency already used by accounts*');
                        redirect('currencies/index');
                    }
                } else {
                    $this->session->set_flashdata('message', '*Can not be deleted: this currency already used by accounts*');
                    redirect('currencies/index');
                }
            }
        } else {
            $this->session->set_flashdata('message', '*Can not be deleted: this currency already used*');
            redirect('currencies/index');
        }
    }

    public function get_currency_rate()
    {

        $id = $this->input->post('whatselected');
        $currency_rate = $this->Currency->fetch_currency_rate($id);
        $currency_system_id = $this->Currency->get_sys_config_cur();
        if ($currency_system_id['valueint'] == $id) {
            echo($currency_rate["currency_rate"] . '-' . 'readonly');
        } else {
            echo($currency_rate["currency_rate"] . '-' . 'no');
        }
    }

    public function get_local_currency()
    {
        $this->load->model('Configuration');
        $LC = $this->Configuration->fetch_local_currency()["valueInt"];
        echo($LC);
    }

    public function get_currecny_rate_by_name()
    {
        $currency_name = $this->input->post('currency_name');
        $rate = $this->Currency->fetch_currency_rate_currency_name($currency_name)['currency_rate'];
        echo $rate;
    }
}
